<?php

use Core\App\Models\MainModel;
use Core\App\Response;
use Core\App\UUID;
use Core\App\ReCaptcha3;

class loginController extends Controller
{
    public function login()
    {
        if (isset($_SESSION['login'])) {
            header('Location: /');
            return;
        }
        Plugin::load('response, models, recaptcha');
        $response = new Response();
        $recaptcha = new Recaptcha3();
        $data = json_decode(file_get_contents("php://input"));
        $recaptcha_verify = $recaptcha->Confirm($data->token);
        if (empty($_COOKIE['consent'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['cookiesnotaccepted'],
                'cookies' => $_COOKIE
            ]);
            return;
        }
        if (!$recaptcha_verify['status']) {
            $response->Send('json', $recaptcha_verify);
            return;
        }
        if (!isset($data->email, $data->password)) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'requiredfieldsnotset'
            ]);
            return;
        }
        $models = new MainModel();
        $models->CallModel('users');
        $userdata = $models->Select([
            'values' => [
                'normal' => [
                    'email' => $data->email
                ]
            ]
        ]);

        if (count($userdata) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['emailorpasswordwrong']
            ]);
            return;
        }
        if ($userdata[0]['verified'] !== 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['accountnotactivated']
            ]);
            return;
        }
        $ip = '';
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $models->CallModel('loginattempts');
        $loginattempts = $models->Select([
            'values' => [
                'normal' => [
                    'userid' => $userdata[0]['uuid'],
                    'ip' => $ip
                ]
            ]
        ]);
        if (count($loginattempts) < 1) {
            Plugin::load('uuid');
            $uuid = new UUID();
            $models->Insert([
                'uuid' => $uuid->Create(),
                'userid' => $userdata[0]['uuid'],
                'tstamp' => time(),
                'logincounter' => 0,
                'ip' => $ip
            ]);
            $counter = 0;
        } else {
            $counter = $loginattempts[0]['logincounter'];
        }

        if (count($loginattempts) > 0) {
            $time = time() - $loginattempts[0]['tstamp'];
            if ($loginattempts[0]['logincounter'] > 4 && $time < 1800) {
                $counter = $counter + 1;
                $models->Update([
                    'where' => [
                        'userid' => $userdata[0]['uuid'],
                        'ip' => $ip
                    ],
                    'data' => [
                        'logincounter' => $counter
                    ]
                ]);
                $response->Send('json', [
                    'status' => false,
                    'msg' => LANG['toomanylogins']
                ]);
                return;
            }
            if ($time > 1800) {
                $counter = 0;
                $models->Update([
                    'where' => [
                        'userid' => $userdata[0]['uuid'],
                        'ip' => $ip
                    ],
                    'data' => [
                        'logincounter' => $counter
                    ]
                ]);
            }
        }

        $password_verify = password_verify($data->password, $userdata[0]['password']);
        if (!$password_verify) {
            $counter = $counter + 1;
            $models->Update([
                'where' => [
                    'userid' => $userdata[0]['uuid'],
                    'ip' => $ip
                ],
                'data' => [
                    'tstamp' => time(),
                    'logincounter' => $counter,
                ]
            ]);
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['emailorpasswordwrong']
            ]);
            return;
        }

        $models->CallModel('perms');
        $perms = $models->Select([
            'values' => [
                'normal' => [
                    'perm_name' => $userdata[0]['permgroup']
                ]
            ]
        ]);
        if (count($perms) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'invalidpermgroup'
            ]);
            return;
        }
        $_SESSION['login'] = [
            'uuid' => $userdata[0]['uuid'],
            'firstname' => $userdata[0]['firstname'],
            'permgroup' => $perms[0]
        ];
        $response->Send('json', [
            'status' => true,
            'msg' => LANG['loginsuccesful']
        ]);
    }
}
