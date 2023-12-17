<?php

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Presenter;
use Ondra\App\System\Application\Model\SignInFormFactory;
use Ondra\App\System\Application\Model\SignUpFormFactory;
use Nette\Security\User;

final class SignPresenter extends Presenter
{
    #[Persistent]
    public $backlink;
    public function __construct(
        public SignInFormFactory $inFactory,
        public SignUpFormFactory $upFactory,
        public User $user
    ){
    }

    protected function beforeRender()
    {
        parent::beforeRender();
        $this->backlink = $this->getParameter('backlink');

        if($this->user->isloggedIn())
            if(isset($this->backlink)){
                $this->restoreRequest($this->backlink);
            }
            else{
                $this->redirect('Home:default');
            }
    }
    public function actionOut($backlink){
        $this->user->logout();
        if(isset($backlink)){
            $this->restoreRequest($backlink);
        }
        else{
            $this->redirect('Home:default');
        }
    }
    public function createComponentSignInForm(){
        return $this->inFactory->create();
    }

    public function createComponentSignUpForm(){
        return $this->upFactory->create();
    }

}
