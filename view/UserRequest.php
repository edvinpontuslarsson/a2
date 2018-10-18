<?php

require_once('model/CustomException.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');
require_once('AuthenticatedView.php');

class UserRequest {
    private $registerView;
    private $loginView;
    private $authenticatedView;

    public function __construct() {
        $this->registerView = new RegisterView();
        $this->loginView = new LoginView();
        $this->authenticatedView = new AuthenticatedView();
    }

    public function userHasCookie() : bool {
        /**
         * TODO: implement this
         */
    }

    public function userWantsToStart() : bool {
        return $_SERVER["REQUEST_METHOD"] === "GET" &&
            !isset($_GET[
                $this->registerView->getRegisterQuery()
            ]);
    }

    public function registrationGET() : bool {
        return $_SERVER["REQUEST_METHOD"] === "GET" &&
            isset($_GET[
                $this->registerView->getRegisterQuery()
            ]);
    }

    public function wantsToLogIn() : bool {
        return isset($_POST[$this->loginView->getLogin()]);
    }

    public function wantsLogOut() : bool { 
        return isset($_POST[
            $this->authenticatedView->getLogoutField()
        ]); 
    }

    public function registrationPOST() : bool {
        return isset($_GET[
            $this->registerView->getRegisterQuery()
        ]) && $_SERVER["REQUEST_METHOD"] === "POST";
    }

    public function getRegisterUsername() : string {
        $username = "";
        if (isset($_POST[
            $this->registerView->getUsernameField()
        ])) {
            $username = $_POST[
                $this->registerView->getUsernameField()
            ];
        }
        return $username;
    }

    public function getRegisterPassword() : string {
        $password = "";
        if (isset($_POST[
            $this->registerView->getPasswordField()
        ])) {
            $password = $_POST[
                $this->registerView->getPasswordField()
            ];
        }
        
        $passwordRepeat = "";
        if (isset($_POST[
            $this->registerView->getRepeatPasswordField()
        ])) {
            $passwordRepeat = $_POST[
                $this->registerView->getRepeatPasswordField()
            ];
        }

        if ($password !== $passwordRepeat) {
            throw new PasswordsDoNotMatchException();
        }

        return $password;
    }

    public function getLoginUsername() : string {
        $username = "";
        if (isset($_POST[
            $this->loginView->getName()
        ])) {
            $username = $_POST[
                $this->loginView->getName()
            ];
        }
        return $username;
    }

    public function getLoginPassword() : string {
        $password = "";
        if (isset($_POST[
            $this->loginView->getPassword()
        ])) {
            $password = $_POST[
                $this->loginView->getPassword()
            ];
        }
        return $password;
    }
}