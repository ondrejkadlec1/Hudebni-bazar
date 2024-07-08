<?php

namespace Ondra\App\Offers\UI\Http\Web\forms;

use Ondra\App\Offers\Application\Command\CreateOfferCommandRequest;
use Ondra\App\Shared\Application\BusProvider;
use Ondra\App\Shared\UI\Http\Web\forms\FormFactory;
use Nette\Application\UI\Form;
use Nette\Http\Request;
class OfferFormFactory extends FormFactory
{
    protected BusProvider $busProvider;
    private Request $httpRequest;
    public function __construct(BusProvider $busProvider, Request $httpRequest)
    {
        $this->busProvider = $busProvider;
        $this->httpRequest = $httpRequest;
    }

    public function create(): Form
    {
        $states = [1 =>'Nový', 2=> 'Zánovní', 3=> 'Používaný', 4=> 'Opotřebený', 5=> 'Poškozený', 6=> 'Nefunkční'];

        $form = new Form;
        $form->addText('name', 'Název')
            ->setRequired("Zadé název, ty jitrnico!");
        $form->addSelect('stateId', 'Stav', $states)
            ->setPrompt('Zvolte stav předmětu')
            ->setRequired("Tak je to rozflákaný nebo to valí?");
        $form->addInteger('price', 'Cena')
            ->setRequired("Kolik to má stát, debile?");
        $form->addText('details', 'Podrobnosti');
        $form->addInteger('quantity', 'počet')
            ->setDefaultValue(1)
            ->addRule($form::Range, 'Kolik že toho chceš prodat?', [1, 10000000000])
            ->setRequired("!!");
        $form->addMultiUpload('images', 'Nahrát obrázky')
            ->addRule($form::MaxLength, 'Maximální počet souborů je 10.', 10)
            ->addRule($form::Image, 'Soubory musí být .jpg, nemo .png');
        $form->addSubmit('send', 'Zveřejnit');
        $form->onSuccess[] = [$this, 'formSubmitted'];
        return $form;
    }
    public function formSubmitted(Form $form, \stdClass $data): void
    {
        $files = $this->httpRequest->getFiles();

        if(!isset($data->details)){
            $data->details = "";
        }
        try {
            $this->sendCommand(new CreateOfferCommandRequest($data->name, $data->stateId, $data->price, $data->quantity, $data->details, $files['images']));
        }
        catch(\Exception $exception){
            $form->addError($exception->getMessage());
        }
    }
}