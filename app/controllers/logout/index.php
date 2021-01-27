<?php

class logoutController {
    public function logout() {
        $_SESSION['loginsession'] = NULL;
        header('Location: /');
    }
}

?>