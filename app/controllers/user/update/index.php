<?php

use Core\App\Response;
use Core\App\ReCaptcha3;
use Core\App\Models\MainModel;
use Core\App\UUID;
use Core\App\View;
use Core\App\Recaptcha2;

class userController extends Controller
{
    public function user()
    {
        Plugin::load('response, models, recaptcha');
        $response = new Response;
        if (!isset($_SESSION['login'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['sessionexpired']
            ]);
            return;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $recaptcha = new Recaptcha3();
        if (!isset($data['token'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'invalidtoken'
            ]);
            return;
        }
        $recaptcha_verify = $recaptcha->Confirm($data['token']);
        if (!$recaptcha_verify['status']) {
            $response->Send('json', $recaptcha_verify);
            return;
        }
        $models = new MainModel();
        $models->CallModel('users');
        $update = $models->Update([
            'where' => [
                'uuid' => $_SESSION['login']['uuid']
            ],
            'data' => $data['data']
        ]);
        if (!$update['status']) {
            $msg = $update['msg'];
            $response->Send('json', [
                'status' => false,
                'msg' => $msg
            ]);
            return;
        }
        $response->Send('json', [
            'status' => true,
            'msg' => LANG['infoupdatesuccesful']
        ]);
    }
    public function changePassword()
    {
        Plugin::load('response, models');
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($_SESSION['login']['uuid'])) {
            header('Location: /404');
            return;
        }
        if ($data->newpassword !== $data->newpasswordagain) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['passwordsdonotmatch']
            ]);
            return;
        }
        if ($data->newpassword === $data->password) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['samepassword']
            ]);
            return;
        }
        $passwordCharacterCheck = preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{10,}$/', $data->newpassword);
        if (!$passwordCharacterCheck) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['invalidpasswordcriteria']
            ]);
            return;
        }
        $model = new MainModel();
        $model->CallModel('users');
        $user = $model->Select([
            'values' => [
                'normal' => [
                    'uuid' => $_SESSION['login']['uuid']
                ]
            ]
        ]);
        $checkPassword = password_verify($data->password, $user[0]['password']);
        if ($checkPassword) {
            $updateBool = $model->Update([
                'where' => [
                    'uuid' => $_SESSION['login']['uuid']
                ],
                'data' => [
                    'password' => $data->newpassword
                ]
            ]);
            if ($updateBool['status']) {
                $response->Send('json', [
                    'status' => true,
                    'msg' => LANG['passwordchanged']
                ]);
                return;
            }
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['passwordchangefailed']
            ]);
            return;
        }
        $response->Send('json', [
            'status' => false,
            'msg' => 'Incorrect password'
        ]);
    }
    public function createPasswordRestore()
    {
        Plugin::load('response, models, uuid, email');
        $response = new Response();
        $uuid = new UUID();

        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->email)) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['emaildoesnotexist']
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
        Plugin::load('recaptcha_two');
        $recaptcha = new Recaptcha2();
        $rc_result = $recaptcha->Confirm($data->token);
        if (!$rc_result['status']) {
            $response->Send('json', $rc_result);
            return;
        }
        $model = new MainModel();
        $model->CallModel('users');
        $user = $model->Select([
            'values' => [
                'normal' => [
                    'email' => $data->email
                ]
            ]
        ]);
        if (count($user) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['emaildoesnotexist']
            ]);
            return;
        }
        $model->CallModel('Passwordreset');
        $expire = time() + 3600;
        $restoreuuid = $uuid->Create();
        $restore = $model->Insert([
            'uuid' => $restoreuuid,
            'userid' => $user[0]['uuid'],
            'expires' => $expire,
            'used' => 0
        ]);
        $email = new Core\App\Email();
        $email->SetHeader('from', 'localhost <noreply@localhost>');
        $email->SetHeader('mime', '1.0');
        $email->SetHeader('content-type', 'text/html; charset="utf-8"');
        $email->enableBCC = true;
        $email->SetRecipients([$data->email]);
        $email->SetSubject('localhost - Salansana palautus');
        $email->Template('forgotpassword', [
            'firstname' => $user[0]['firstname'],
            'lastname' => $user[0]['lastname'],
            'uuid' => $restoreuuid,
            'subject' => 'localhost - Salansana palautus'
        ]);
        $didEmailSend = $email->Send();
        if (!$didEmailSend['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => $didEmailSend['msg']
            ]);
            return;
        }
        if ($restore['status']) {
            $response->Send('json', [
                'status' => true,
                'msg' => LANG['restoresuccess']
            ]);
            return;
        }
        $response->Send('json', [
            'status' => false,
            'msg' => 'Sending restore link to this email address',
            'data' => $data
        ]);
    }
    public function handlePasswordRestore()
    {
        Plugin::load('view, models');
        $model = new MainModel();
        $model->CallModel('passwordreset');
        $resetRequest = $model->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if (count($resetRequest) < 1) {
            header('Location: /404');
            return;
        }
        $expireTime = $resetRequest[0]['expires'];
        $view = new View();
        $view->customelements = [
            'header' => 'empty',
            'footer' => 'empty'
        ];
        if ($resetRequest[0]['used']) {
            $view->variables = [
                'msg' => LANG['codehasbeenused']
            ];
            $view->index($this->view);
            return;
        }
        if (time() > $expireTime) {
            $view->variables = [
                'msg' => LANG['restorelinkexpired']
            ];
            $view->index($this->view);
            return;
        }

        $view->variables = [
            'uuid' => $this->params[0]
        ];
        $view->index($this->view);
    }
    public function changePasswordWithCode()
    {
        $data = json_decode(file_get_contents('php://input'));
        Plugin::load('response, models, recaptcha_two');
        $response = new Response();
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
        if ($data->newpassword !== $data->newpasswordagain) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['passwordsdonotmatch']
            ]);
            return;
        }
        $passwordCharacterCheck = preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{10,}$/', $data->newpassword);
        if (!$passwordCharacterCheck) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['invalidpasswordcriteria']
            ]);
            return;
        }

        $model = new MainModel();
        $model->CallModel('passwordreset');
        $passwordchange = $model->Select([
            'values' => [
                'normal' => [
                    'uuid' => $data->code
                ]
            ]
        ]);
        if ($passwordchange[0]['used']) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['codehasbeenused']
            ]);
            return;
        }
        $userid = $passwordchange[0]['userid'];
        $model->CallModel('users');
        $update = $model->Update([
            'where' => [
                'uuid' => $userid
            ],
            'data' => [
                'password' => $data->newpassword
            ]
        ]);
        if (!$update['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['passwordchangefailed']
            ]);
            return;
        }
        $model->CallModel('passwordreset');
        $model->Update([
            'where' => [
                'uuid' => $data->code
            ],
            'data' => [
                'used' => 1
            ]
        ]);
        $response->Send('json', [
            'status' => true,
            'msg' => LANG['passwordchanged']
        ]);
    }
}
