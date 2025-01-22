<?php

class verifyaccountController extends Controller
{
    public function verifyaccount()
    {
        if (empty($_GET['msg'])) {
            header('HTTP/1.1 404 Not Found');
            header('Location: /404');
        }
        plugin::load('view');
        $view = new Core\App\View();
        $msg = [];
        switch ($_GET['msg']) {
            case 'success':
                $msg['title'] = LANG['verificationsuccess'];
                $msg['status'] = true;
                break;
            case 'failed':
                $msg['title'] = LANG['verificationfailed'];
                break;
            case 'invalid':
                $msg['title'] = LANG['verificationinvalid'];
                break;
            case 'used':
                $msg['title'] = LANG['verificationused'];
                break;
            case 'expired':
                $msg['title'] = LANG['verificationexpired'];
                $msg['desc'] = LANG['verificationexpiredmsg'];
                break;
            default:
                $msg['title'] = 'Invalid status';
                break;
        }
        $view->variables = [
            'msg' => $msg
        ];
        $view->index($this->view);
    }
    public function verify()
    {
        plugin::load('response, models');
        $response = new Core\App\Response();
        $models = new Core\App\Models\MainModel();
        $models->CallModel('userverification');
        $verification = $models->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if (count($verification) < 1) {
            header('Location: /verifyaccount?msg=invalid');
            return;
        }
        if ($verification[0]['verified'] === 'true') {
            header('Location: /verifyaccount?msg=used');
            return;
        }
        $time_diff = time() - $verification[0]['tstamp'];
        if ($time_diff > 7200) {
            plugin::load('uuid, email');
            $uuid = new Core\App\UUID();
            $newid = $uuid->Create();

            $models->Update([
                'where' => [
                    'uuid' => $verification[0]['uuid']
                ],
                'data' => [
                    'uuid' => $newid,
                    'tstamp' => time()
                ]
            ]);
            $models->CallModel('users');
            $user = $models->Select([
                'values' => [
                    'normal' => [
                        'uuid' => $verification[0]['userid']
                    ]
                ]
            ]);
            $useremail = $user[0]['email'];

            $email = new Core\App\Email();
            $email->SetHeader('from', 'localhost <noreply@localhost>');
            $email->SetHeader('mime', '1.0');
            $email->SetHeader('content-type', 'text/html; charset="utf-8"');
            $email->enableBCC = true;
            $email->SetRecipients([$useremail]);
            $email->SetSubject('localhost - Käyttäjätunnukset vahvistaminen');
            $email->Template('verifyaccount', [
                'subject' => 'localhost - Käyttäjätunnuksen vahvimastaminen',
                'verification_code' => $newid,
                'firstname' => $user[0]['firstname']
            ]);
            $didEmailSend = $email->Send();
            header('Location: /verifyaccount?msg=expired');
            return;
        }
        $models->Update([
            'where' => [
                'uuid' => $verification[0]['uuid']
            ],
            'data' => [
                'verified' => 'true'
            ]
        ]);
        $models->CallModel('users');
        $update = $models->Update([
            'where' => [
                'uuid' => $verification[0]['userid']
            ],
            'data' => [
                'verified' => 1
            ]
        ]);
        if ($update['status']) {
            header('Location: http://' . CONFIG['server']['host'] . '/verifyaccount?msg=success');
        }
    }
}
