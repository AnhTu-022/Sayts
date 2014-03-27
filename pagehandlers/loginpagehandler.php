<?php

namespace PageHandlers;

class LoginPageHandler extends PageHandler {

    public function handle() {
        if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
            $handler = new CsvImportPageHandler();
            $handler->handle();
            return $handler;
        }

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $con = \Connection::getConnection();
            $stmt = $con->prepare('SELECT user_id, username, password, salt FROM users WHERE username=:username LIMIT 1');
            if ($stmt->execute(array(':username' => $_POST['username']))) {
                $user = $stmt->fetchObject();
                if ($this->comparePassword($user, $_POST['password'])) {
                    $_SESSION['username'] = $user->username;
                    $_SESSION['userid'] = $user->user_id;

                    // redirect internally
                    $pageHandler = new CsvImportPageHandler();
                    $pageHandler->handle();
                    return $pageHandler;
                }
            }

            // only reached in case of unsuccessful login
            $this->showLoginValidationError();
        }

        $this->setPhpTemplate('login');
        return $this;
    }

    public function showLoginValidationError() {
        $this->setPageData('loginError', true);
    }

    public function loginRequired() {
        return false;
    }

    private function comparePassword($user, $password) {
        if (sha1($password . $user->salt) == $user->password)
            return true;
        return false;
    }

}
