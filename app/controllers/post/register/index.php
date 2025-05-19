<?php

use Core\App\Response;
use Core\App\Models\MainModel;
use Core\App\UUID;
use Core\App\Recaptcha2;

class registerController extends Controller
{
    public function register()
    {
        if (isset($_SESSION['login'])) {
            header('Location: /');
            return;
        }

        Plugin::load('response');
        $response = new Response();

        $required = [
            'firstname',
            'lastname',
            'email',
            'password',
            'passwordagain',
            'phonenumber'
        ];
        $data = json_decode(file_get_contents("php://input"), true);
        // At beginning we want to assume that all requirements are set
        $requirements = true;
        // Array that stores empty fieldnames. 
        $empty_fields = [];
        foreach ($required as $req_field) {
            if (!isset($data['data'][$req_field])) {
                $empty_fields[] = LANG[$req_field];
                $requirements = false;
            }
        }
        if (!$requirements) {
            $empty_fields_str = implode(', ', $empty_fields);
            $response->Send('json', [
                'status' => false,
                'msg' => $empty_fields_str . ' ' . LANG['arenotset']
            ]);
            return;
        }
        if ($data['data']['password'] !== $data['data']['passwordagain']) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['errorlist']['passwordnotmatch']
            ]);
            return;
        }
        if (!isset($data['token'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['captchatokennotset']
            ]);
            return;
        }
        Plugin::load('recaptcha_two');
        $recaptcha = new Recaptcha2();
        $rc_result = $recaptcha->Confirm($data['token']);
        if (!$rc_result['status']) {
            $response->Send('json', $rc_result);
            return;
        }
        Plugin::load('models, uuid, email');
        $models = new MainModel();
        $models->CallModel('users');
        $uuid = new UUID();
        $user_data = [
            'uuid' => $uuid->Create(),
            'permgroup' => 'default',
            'firstname' => htmlspecialchars($data['data']['firstname']),
            'lastname' => htmlspecialchars($data['data']['lastname']),
            'email' => htmlspecialchars($data['data']['email']),
            'phonenumber' => htmlspecialchars($data['data']['phonenumber']),
            'password' => $data['data']['password'],
            'verified' => 0
        ];
        $msg = $models->Insert($user_data);
        if ($msg['status']) {
            $models->CallModel('userverification');
            $verification_code = $uuid->Create();
            $models->Insert([
                'uuid' => $verification_code,
                'userid' => $user_data['uuid'],
                'tstamp' => time(),
                'verified' => 'false'
            ]);
            $response->Send('json', [
                'status' => true,
                'msg' => LANG['registersuccess']
            ]);
            $email = new Core\App\Email();
            $email->SetHeader('from', 'localhost <noreply@localhost>');
            $email->SetHeader('mime', '1.0');
            $email->SetHeader('content-type', 'text/html; charset="utf-8"');
            $email->enableBCC = true;
            $email->SetRecipients([$user_data['email']]);
            $email->SetSubject('localhost - Käyttäjätunnukset vahvistaminen');
            $email->Template('verifyaccount', [
                'subject' => 'Käyttäjätunnuksen vahvistaminen',
                'verification_code' => $verification_code,
                'firstname' => $user_data['firstname']
            ]);
            $email->Send();
            return;
        }
        $response->Send('json', [
            'status' => false,
            'msg' => LANG['registerfail'] . ' ' . $msg['msg']
        ]);
    }
}
