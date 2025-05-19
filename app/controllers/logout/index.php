<?php 

class logoutController extends Controller {
    public function logout() {
        if (isset($_SESSION['login'])) {
            unset($_SESSION['login']);
            header('Location: /');
        }
    }
}

?>