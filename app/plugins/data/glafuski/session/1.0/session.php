<?php

class SessionSecurity {
    public function Auth() {
        if (isset($_SESSION['login_hash'])) {
            Plugin::load('activerecord');
            $user = user::find_by_login_hash($_SESSION['login_hash']);
            $hash = hash('sha256', $_SERVER['REMOTE_ADDR'] . $user->login_stamp);
            if ($hash !== $_SESSION['login_hash']) {
                session_destroy();
            }
        }
        else {
            session_destroy();
            header('Location: /login');
        }
    }
}

?>