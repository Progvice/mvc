<?php

use Core\App\Response;
use Core\App\UUID;
use Core\App\Models\MainModel;
use Core\App\Recaptcha2;

class kyyditController extends Controller
{
    public function kyydit()
    {
        Plugin::load('response, recaptcha_two, models, uuid, email');
        $response = new Response();
        $recaptcha = new Recaptcha2();
        $uuid = new UUID();
        $data = json_decode(file_get_contents("php://input"));
        $address_from = htmlspecialchars($data->data->address_from);
        $address_to = htmlspecialchars($data->data->address_to);
        $firstname = htmlspecialchars($data->data->firstname);
        $lastname = htmlspecialchars($data->data->lastname);
        $email = htmlspecialchars($data->data->email);
        $phonenumber = htmlspecialchars($data->data->phonenumber);
        $passengers = htmlspecialchars($data->data->passengers);
        $order_desc = htmlspecialchars($data->data->order_desc);
        $order_date = htmlspecialchars($data->data->order_date);
        $order_time = htmlspecialchars($data->data->order_time);
        $token = $data->token;
        $rc_result = $recaptcha->Confirm($token);
        if (!$rc_result['status']) {
            $response->Send('json', $rc_result);
            return;
        }
        $userid = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
        if (isset($_SESSION['login'])) {
            $userid = $_SESSION['login']['uuid'];
        }
        $orderid = $uuid->Create();
        $model = new MainModel();
        $model->CallModel('guestrides');
        $model->Insert([
            'uuid' => $orderid,
            'userid' => $userid,
            'address_from' => $address_from,
            'address_to' => $address_to,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phonenumber' => $phonenumber,
            'passengers' => intval($passengers),
            'order_desc' => $order_desc,
            'order_date' => $order_date,
            'order_time' => $order_time,
            'order_status' => 'unconfirmed'
        ]);

        $subject = 'localhost - Kyytitilaus lähetetty';
        $emailHandler = new Core\App\Email();
        $emailHandler->SetHeader('from', 'localhost <noreply@localhost>');
        $emailHandler->SetHeader('mime', '1.0');
        $emailHandler->SetHeader('content-type', 'text/html; charset="utf-8"');
        $emailHandler->enableBCC = true;
        $emailHandler->SetRecipients([$email, 'tilaus@localhost']);
        $emailHandler->SetSubject($subject);
        $emailHandler->Template('ordersent', [
            'subject' => $subject,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phonenumber' => $phonenumber,
            'email' => $email,
            'passengers' => $passengers,
            'address_from' => $address_from,
            'address_to' => $address_to,
            'order_date' => $order_date,
            'order_desc' => $order_desc,
            'order_time' => $order_time
        ]);
        $didEmailSend = $emailHandler->Send();
        if (!$didEmailSend['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'orderfail'
            ]);
            return;
        }
        $response->Send('json', [
            'status' => true,
            'msg' => LANG['ordersuccess']
        ]);
    }
}
?>