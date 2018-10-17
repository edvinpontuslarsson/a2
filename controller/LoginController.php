<?php

require_once('model/SessionModel.php');

class LoginController {
    private $sessionModel;
    private $userRequest;
    private $mainView;

    public function __construct($userRequest, $mainView) {
        $this->sessionModel = new SessionModel();
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function prepareStart(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            $this->mainView->renderNotAuthenticatedView();
        } else {
            $this->mainView->renderAuthenticatedView();
        }
    }

    public function handleLogin() {
        $userCredentials = 
            $this->mainView->getUserCredentials();
        $this->sessionModel->setSession($userCredentials);
        $this->mainView->renderAuthenticatedView(true);
    }

    public function handleLogOut(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            $this->mainView->renderNotAuthenticatedView();
        } else {
            $this->sessionModel->destroySession();
            $this->mainView->renderNotAuthenticatedView(
                false, true
            );
        }
    }
}