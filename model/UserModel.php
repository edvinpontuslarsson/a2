<?php

require_once('model/DatabaseModel.php');

class UserModel {

    private $databaseModel;
    private $userName;
    private $hashedPassword;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    // to prevent > 1 user with same username
    // https://stackoverflow.com/questions/9814191/mysql-row-locking-via-php

    public function storeNewUser(string $userName, string $rawPassword) {
        $this->userName = $userName;
        $this->hashedPassword = password_hash(
            $rawPassword, PASSWORD_DEFAULT
        );

        // TODO: Remove from final version
        $this->databaseModel->createDbTableIfNotExists(
            "Users",
            $this->getUsersSqlColumnsString()
        );

        $this->databaseModel->insertDataIntoExistingDbTable(
            $this->getSqlInsertQuery()
        );
    }

    public function authenticateUser(string $userName, string $rawPassword) {
        // $isUserExisting = DatabaseModel->fetchThingy($userName);
        /*
        $this->hashedPassword = DatabaseModel->fetchThingy(
            password of $userName
        );*/
        $isPasswordCorrect = password_verify(
            $rawPassword, $this->hashedPassword
        );

        if (!$isUserExisting || !$isPasswordCorrect) {
            echo 'Incorrect login info';
        }
    }

    // TODO: Remove from final version
    private function getUsersSqlColumnsString() : string {
        return "id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(25) NOT NULL,
        password VARCHAR(128) NOT NULL,
        reg_date TIMESTAMP
        ";
    }

    private function getSqlInsertQuery() : string { 
        return "INSERT INTO Users (username, password)
        VALUES ('$this->userName', '$this->hashedPassword')";
    }
}