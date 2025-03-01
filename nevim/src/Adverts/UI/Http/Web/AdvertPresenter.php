<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Exception;
use Nette\Application\UI\Form;
use Nette\Http\Request;
use Ondra\App\Adverts\Application\Command\DTOs\AdvertDTO;
use Ondra\App\Adverts\Application\Command\Messages\DeleteAdvertCommandRequest;
use Ondra\App\Adverts\Application\Command\Messages\HandleAdvertCommandRequest;
use Ondra\App\Adverts\Application\Command\Messages\UpdateAdvertCommandRequest;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetSubordinateCategoriesQuery;
use Ondra\App\Adverts\UI\Http\Web\forms\AdvertFormData;
use Ondra\App\Adverts\UI\Http\Web\forms\AdvertFormFactory;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\Application\Exceptions\PermissionException;
use Ondra\App\Shared\UI\Http\Web\base\FrontendPresenter;
use Ondra\App\Shared\UI\Http\Web\traits\UsersOnly;
use stdClass;

final class AdvertPresenter extends FrontendPresenter
{
    use UsersOnly;

    public function __construct(private readonly AdvertFormFactory $formFactory, private readonly Request $httpRequest)
    {
    }

    public function actionCategories(string $superordinate): void
    {
        $this->sendJson($this->sendQuery(new GetSubordinateCategoriesQuery((int)($superordinate)))->subordinate);
    }

    public function actionDelete(string $id): void
    {
        try {
            $this->sendCommand(new DeleteAdvertCommandRequest($id));
        } catch (Exception $e) {
            if (($e->getPrevious() instanceof PermissionException & $e->getPrevious()->getCode() === 0) !== 0) {
                $this->error('Tuto nabídku nesmíte upravovat, protože není vaše.', 403);
            }
            if ($e->getPrevious() instanceof MissingContentException) {
                $this->error('Hledaná nabídka (už) neexistuje.', 404);
            }
            throw $e;
        }
        $this->flashMessage("Úspěšně smazáno");
        $this->redirect(":Users:Profile:default");
    }

    public function createComponentAdvertForm(): Form
    {
        $form = $this->formFactory->create();
        $form->onSuccess[] = function (Form $form, AdvertFormData $data): void {
            $id = $this->getParameter('id');
            $imageMask = json_decode($data->keepImages, null, 512, JSON_THROW_ON_ERROR);
            $images = [];

            $imageFiles = [];
            if ($this->httpRequest->getFiles()['images'][0] !== null) {
                $imageFiles = $this->httpRequest->getFiles()['images'];
            }
            foreach ($imageMask as $key => $imageId) {
                if ($imageId === 'uploaded') {
                    $images[$key] = array_shift($imageFiles);
                    continue;
                }
                if (is_numeric($imageId)) {
                    $images[$key] = (int)$imageId;
                    continue;
                }
                $this->error('Odeslána neplatná data.', 400);

            }
            if (count($imageMask) !== count($images) or $imageFiles !== []) {
                $this->error('Odeslána neplatná data.', 400);
            }
            $lowestCategoryId = $this->lowestCategory($data);
            try {
                $this->sendCommand(
                    new HandleAdvertCommandRequest(new AdvertDTO(
                            $data->name,
                            $data->stateId,
                            $data->price,
                            $lowestCategoryId,
                            $data->quantity,
                            $images,
                            $data->details,
                            $id,
                            $data->brand,
                        )
                    ),
                );

            } catch (Exception $e) {
                if ($e->getPrevious() instanceof PermissionException) {
                    if ($e->getPrevious()->getCode() === 0) {
                        $this->error('Pro tuto akci se musíte přihlásit.', 401);
                    }
                    if ($e->getPrevious()->getCode() === 1) {
                        $this->error('Tuto nabídku nesmíte upravovat, protože není vaše.', 403);
                    }
                }
                if ($e->getPrevious() instanceof MissingContentException) {
                    $this->error('Tato nabídka neexistuje.', 404);
                }
                throw $e->getPrevious();
            }
            $this->flashMessage('Úspěšně uloženo');
            $this->redirect(":Users:Profile:default");
        };
        return $form;
    }

    public function renderDefault(): void
    {
        $this->template->imageNames = [];
    }

    public function renderUpdate(string $id): void
    {
        $existingAdvert = $this->sendQuery(new GetAdvertQuery($id))->dto;
        $this->template->imageNames = $existingAdvert->imageNames;
        $this->template->imageIds = $existingAdvert->imageIds;
        $categoryIds = array_keys($existingAdvert->categories);
        $categoryId = $categoryIds[0];
        $subcategoryId = $categoryIds[1] ?? null;
        $subsubcategoryId = $categoryIds[2] ?? null;
        $form = $this->getComponent('advertForm');
        $form->getComponent('subcategoryId')->setItems(
            $this->sendQuery(new GetSubordinateCategoriesQuery($categoryId))->subordinate,
        );
        if ($subcategoryId !== null) {
            $form->getComponent('subsubcategoryId')->setItems(
                $this->sendQuery(new GetSubordinateCategoriesQuery($subcategoryId))->subordinate,
            );
        }
        $form->setDefaults([
            'name' => $existingAdvert->name,
            'stateId' => $existingAdvert->stateId,
            'price' => $existingAdvert->price,
            'details' => $existingAdvert->details,
            'quantity' => $existingAdvert->quantity,
            'categoryId' => $categoryId,
            'subcategoryId' => $subcategoryId,
            'subsubcategoryId' => $subsubcategoryId,
            'brand' => $existingAdvert->brand,
            'keepImages' => '[]',
        ]);
    }

    private function lowestCategory(AdvertFormData $data): int
    {
        if ($data->subsubcategoryId !== null) {
            return $data->subsubcategoryId;
        }
        if ($data->subcategoryId !== null) {
            return $data->subcategoryId;
        }
        return $data->categoryId;
    }
}
