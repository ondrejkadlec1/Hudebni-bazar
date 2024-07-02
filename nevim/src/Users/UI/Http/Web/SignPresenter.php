<?php

namespace Ondra\App\System\UI\Http\Web;

use Nette\Application\Attributes\Persistent;
use Nette\Security\User;
use Ondra\App\System\UI\Http\Web\forms\SignInFormFactory;
use Ondra\App\System\UI\Http\Web\forms\SignUpFormFactory;

final class SignPresenter extends FrontendPresenter
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
        $this->user->logout(true);
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
