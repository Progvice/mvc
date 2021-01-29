<?php

class registerpostController extends Controller {
    public function register() {
        if(!isset($_POST['username'], $_POST['password'])) {
            header('Location: /404');
        }
        plugin::load('models');
        $userdata = [
            'username' => htmlspecialchars($_POST['username']),
            'password' => password_hash($_POST['password'], PASSWORD_ARGON2I)
        ];
        $model = new Core\App\Models\MainModel;
        $model->CallModel('user');
        $response = $model->Insert($userdata);
        if($response === true) {
            header('Location: /register?success=true');
            return;
        }
        header('Location: /register?success=false');
    }
}

?>