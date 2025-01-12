<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Form;
use Nette\Http\Request;
use Ondra\App\Adverts\Application\Command\Messages\CreateAdvertCommandRequest;
use Ondra\App\Adverts\Application\Command\Messages\DeleteAdvertCommandRequest;
use Ondra\App\Adverts\Application\Command\Messages\UpdateAdvertCommandRequest;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertQuery;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetSubordinateCategoriesQuery;
use Ondra\App\Adverts\UI\Http\Web\forms\AdvertFormFactory;
use Ondra\App\Shared\Application\Exceptions\MissingContentException;
use Ondra\App\Shared\Application\Exceptions\PermissionException;
use Ondra\App\Shared\UI\Http\Web\FrontendPresenter;
use Ondra\App\Shared\UI\Http\Web\UsersOnly;

class AdvertPresenter extends FrontendPresenter
{
	use UsersOnly;
	public function __construct(private readonly AdvertFormFactory $formFactory, private readonly Request $httpRequest)
	{
	}

	public function actionDelete(string $id): void
	{
		try {
			$this->sendCommand(new DeleteAdvertCommandRequest($id));
		} catch (\Exception $e) {
			if ($e->getPrevious() instanceof PermissionException & $e->getPrevious()->getCode() === 0) {
				$this->error('Tuto nabídku nesmíte upravovat, protože není vaše.', 403);
			}
            if ($e->getPrevious() instanceof MissingContentException){
                $this->error('Hledaná nabídka (už) neexistuje.', 404);
            }
		}
        $this->flashMessage("Úspěšně smazáno");
		$this->redirect(":Users:Profile:default");
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
        $form->getComponent('subcategoryId')->setItems($this->sendQuery(new GetSubordinateCategoriesQuery($categoryId))->subordinate);
        if (isset($subcategoryId)) {
            $form->getComponent('subsubcategoryId')->setItems($this->sendQuery(new GetSubordinateCategoriesQuery($subcategoryId))->subordinate);
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
            'keepImages' => '[]'
		]);

	}
	public function createComponentAdvertForm(): Form
	{
		$form = $this->formFactory->create();
		$form->onSuccess[] = function (Form $form, \stdClass $data): void {
			$id = $this->getParameter('id');
            $imageMask = json_decode($data->keepImages);
            $images = [];

            $imageFiles = [];
			if ($this->httpRequest->getFiles()['images'][0] !== null) {
                $imageFiles = $this->httpRequest->getFiles()['images'];
            }
            foreach ($imageMask as $key => $imageId) {
                $images[$key] = ($imageId === 'uploaded') ? array_shift($imageFiles) : (int) $imageId;
            }
            if (count($imageMask) != count($images) or !empty($imageFiles)) {
                $this->error('Odeslána neplatná data.', 400);
            }
            if (isset($data->subsubcategoryId)) {
                $lowestCategoryId = $data->subsubcategoryId;
            }
            elseif (isset($data->subcategoryId)) {
                $lowestCategoryId = $data->subcategoryId;
            }
            else {
                $lowestCategoryId = $data->categoryId;
            }
			try {
				if (isset($id)) {
					$this->sendCommand(
						new UpdateAdvertCommandRequest(
							$id,
							$data->name,
							$data->stateId,
							$data->price,
							$lowestCategoryId,
							$data->quantity,
							$data->details,
							$images,
							$data->brand,
						),
					);
				} else {
					$this->sendCommand(
						new CreateAdvertCommandRequest(
							$data->name,
							$data->stateId,
							$data->price,
                            $lowestCategoryId,
							$data->quantity,
							$data->details,
							$images,
							$data->brand,
						),
					);
				}
			} catch (\Exception $e) {
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
                else {
                    throw $e->getPrevious();
                }
			}
			$this->flashMessage('Úspěšně uloženo');
			$this->redirect(":Users:Profile:default");
		};
		return $form;
	}

    public function actionCategories(string $superordinate): void {
        $this->sendJson($this->sendQuery(new GetSubordinateCategoriesQuery((int) ($superordinate)))->subordinate);
    }
}
