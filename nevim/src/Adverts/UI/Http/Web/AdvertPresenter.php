<?php

declare(strict_types=1);

namespace Ondra\App\Adverts\UI\Http\Web;

use Nette\Application\UI\Form;
use Nette\Http\Request;
use Ondra\App\Adverts\Application\Command\Messages\CreateAdvertCommandRequest;
use Ondra\App\Adverts\Application\Command\Messages\DeleteAdvertCommandRequest;
use Ondra\App\Adverts\Application\Command\Messages\UpdateAdvertCommandRequest;
use Ondra\App\Adverts\Application\Query\Messages\Request\GetAdvertQuery;
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
		$this->redirect(":Users:Profile:default");
	}
	public function renderUpdate(string $id): void
	{
		$existingAdvert = $this->sendQuery(new GetAdvertQuery($id))->dto;
		$this->getComponent('advertForm')->setDefaults([
			'name' => $existingAdvert->name,
			'stateId' => $existingAdvert->stateId,
			'price' => $existingAdvert->price,
			'details' => $existingAdvert->details,
			'quantity' => $existingAdvert->quantity,
			'categoryId' => $existingAdvert->categoryId,
			'subcategoryId' => $existingAdvert->subcategoryId,
			'subsubcategoryId' => $existingAdvert->subsubcategoryId,
			'brand' => $existingAdvert->brand,
		]);
	}
	public function createComponentAdvertForm(): Form
	{
		$form = $this->formFactory->create();
		$form->onSuccess[] = function (Form $form, \stdClass $data): void {
			$id = $this->getParameter('id');
			$imageFiles = [];
			if ($this->httpRequest->getFiles()['images'][0] !== null) {
				$imageFiles = $this->httpRequest->getFiles()['images'];
			}
			try {
				if (isset($id)) {
					$this->sendCommand(
						new UpdateAdvertCommandRequest(
							$id,
							$data->name,
							$data->stateId,
							$data->price,
							$data->subsubcategoryId,
							$data->quantity,
							$data->details,
							$imageFiles,
							$data->brand,
						),
					);
				} else {
					$this->sendCommand(
						new CreateAdvertCommandRequest(
							$data->name,
							$data->stateId,
							$data->price,
							$data->subsubcategoryId,
							$data->quantity,
							$data->details,
							$imageFiles,
							$data->brand,
						),
					);
				}
			} catch (\Exception $e) {
                if ($e->getPrevious() instanceof PermissionException) {
                    if ($e->getPrevious()->getCode() === 0) {
                        $this->error('Pro tuto akci se musíte přihlásit.', 403);
                    }
                    if ($e->getPrevious()->getCode() === 1) {
                        $this->error('Tuto nabídku nesmíte upravovat, protože není vaše.', 403);
                    }
                }
			}
			$this->flashMessage('Přidáno');
			$this->redirect(":Users:Profile:default");
		};
		return $form;
	}
}
