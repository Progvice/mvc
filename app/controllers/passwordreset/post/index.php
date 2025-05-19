<?php 

use Core\App\Response;
use Core\App\Models\MainModel;
use Core\App\UUID;
use Core\App\Recaptcha2;

class passwordresetController extends Controller {
    public function passwordreset() {
        Plugin::load('response, recaptcha_two');
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->email)) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['emailnotset']
            ]);
            return;
        }
        if (!isset($data->token)) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['captchatokennotset']
            ]);
            return;
        }
        $recaptcha = new Recaptcha2();
        $rc_result = $recaptcha->Confirm($data->token);
        if (!$rc_result['status']) {
            $response->Send('json', $rc_result);
            return;
        }
        Plugin::load('models');
        $models = new MainModel();
        $models->CallModel('users');
        $usersexists = $models->Select([
            'values' => [
                'normal' => [
                    'email' => $data->email
                ]
            ],
            'columns' => 'uuid'
        ]);
        if (count($usersexists) < 1) {
            $response->Send('json', [
                'status' => true,
                'msg' => LANG['passwordresetsent']
            ]);
            return;
        }
        
        Plugin::load('uuid');
        $uuid = new UUID();
        $models->CallModel('passwordreset');
        $time = time() + 3600;

        $models->Insert([
            'uuid' => $uuid->Create(),
            'userid' => $usersexists[0]->uuid,
            'expires' => $time,
            'used' => 0
        ]);
        $response->Send('json', [
            'status' => true,
            'msg' => LANG['passwordresetsent']
        ]);
    }
}

?>