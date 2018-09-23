<?php

require_once('model/UserModel.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    private $loginView;
    private $dtv;
    private $layoutView;

    private $userModel;
    private $userValidation;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->userValidation = new UserValidation();

        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
    }

    public function initialize() {        
        $isRegisterQueryString = 
            $this->loginView->isRegisterQueryString();
        if ($isRegisterQueryString) {
            $this->loginView->wantsToRegister(true);
        }

        $reqType = $this->loginView->getRequestType();
        
        // TODO: put content in if in GetController
        if ($reqType === "GET") {
            $this->layoutView->render(false, $this->loginView, $this->dtv);
        }

        // $submit = $_POST["LoginView::Login"];
        // $submit = $_POST["DoRegistration"];

        // TODO: put content in if in PostController, with funcs
        if ($reqType === "POST") {
            if (!$isRegisterQueryString) {
                $this->loginUser();
            } else {
                $this->registerUser();
            }
        }
    }

    private function loginUser() {
        $rawUserName = $_POST["LoginView::UserName"];
        $rawPassword = $_POST["LoginView::Password"];

        $isLoginValid = $this->userValidation->
            isLoginValid($rawUserName, $rawPassword);

        if (!$isLoginValid) {
            echo "Login not valid :(";
        } else {
            echo "Login is valid :D";
        }
    }

    private function registerUser() {
        $rawUserName = $_POST["RegisterView::UserName"];
        $rawPassword = $_POST["RegisterView::Password"];
        $rawPasswordRepeat = $_POST["RegisterView::PasswordRepeat"];

        $isRegistrationValid = $this->userValidation->isRegistrationValid(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );
    
        if (!$isRegistrationValid) {
            $errorMessage = $this->userValidation->
                getErrorMessage();

            echo "$errorMessage";
        } else {
            $this->userModel->storeNewUser(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );

            $username = $this->userModel->getCleanUsername();
            echo "Welcome aboard $username";
        }
    }
}