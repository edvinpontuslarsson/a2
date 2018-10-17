<?php

require_once('model/SessionModel.php');
require_once('controller/RegisterController.php');
require_once('controller/LoginController.php');

class MainController {
    private $sessionModel;
    private $userRequest;
    private $mainView;
    private $registerController;
    private $loginController;

    public function __construct($userRequest, $mainView) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;

        $this->sessionModel = new SessionModel();

        $this->registerController = new RegisterController(
            $this->userRequest, $this->mainView
        );
        $this->loginController = new LoginController(
            $this->userRequest, $this->mainView
        );
    }

    public function initialize() {
        $isLoggedIn = $this->sessionModel->isLoggedIn();

        if ($this->userRequest->registrationPOST()) {
            $this->registerController->handleRegistration();
        }
        elseif ($this->userRequest->registrationGET()) {
            $this->registerController->prepareRegistration();
        }
        elseif ($this->userRequest->wantsToLogIn()) {
            $this->loginController->handleLogin();
        }
        elseif ($this->userRequest->wantsLogOut()) {
            $this->loginController->handleLogOut($isLoggedIn);
        }
        else { // TODON'T do it like this, check if get or post
            $this->loginController->prepareStart($isLoggedIn);
        }
    }
}