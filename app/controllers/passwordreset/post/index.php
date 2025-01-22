<?php 

class passwordresetController extends Controller {
    public function passwordreset() {
        plugin::load('response, recaptcha_two');
        $response = new Core\App\Response();
        $data = json_decode(file_get_contents('php://input'));
        if(!isset($data->email)) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['emailnotset']
            ]);
            return;
        }
        if(!isset($data->token)) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['captchatokennotset']
            ]);
            return;
        }
        $recaptcha = new Core\App\Recaptcha();
        $rc_result = $recaptcha->Confirm($data->token);
        if(!$rc_result['status']) {
            $response->Send('json', $rc_result);
            return;
        }
        plugin::load('models');
        $models = new Core\App\Models\MainModel();
        $models->CallModel('users');
        $usersexists = $models->Select([
            'values' => [
                'normal' => [
                    'email' => $data->email
                ]
            ],
            'columns' => 'uuid'
        ]);
        if(count($usersexists) < 1) {
            $response->Send('json', [
                'status' => true,
                'msg' => LANG['passwordresetsent']
            ]);
            return;
        }
        
        plugin::load('uuid');
        $uuid = new Core\App\UUID();
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