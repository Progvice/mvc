<?php

use Core\App\Auth;

class kyytitilauksetController extends Controller
{
    public function kyytitilaukset()
    {
        plugin::load('view, templateloader, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $models = new Core\App\Models\MainModel();
        $models->CallModel('Guestrides');
        // ua = unaccepted
        $date = date_create(date('Y-m-d'));
        date_sub($date, date_interval_create_from_date_string("1 days"));
        $allRides = $models->Select([
            'values' => [
                'bigger' => [
                    'order_date' => date_format($date, 'Y-m-d')
                ]
            ]
        ]);
        $uaRides = [];
        $rides = [];
        $cancelledRides = [];
        foreach ($allRides as $ride) {
            switch ($ride['order_status']) {
                case 'cancelled':
                    $cancelledRides[] = $ride;
                    break;
                case 'unconfirmed':
                    $uaRides[] = $ride;
                    break;
                case 'confirmed':
                    $rides[] = $ride;
                    break;
            }
        }
        $view = new Core\App\View;
        $template = new Core\App\Template();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'uarides' => $uaRides,
            'cancelledrides' => $cancelledRides,
            'rides' => $rides
        ];
        $view->index($this->view);
    }
    public function getRideDetails()
    {
        plugin::load('view, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $model = new Core\App\Models\MainModel();
        $model->CallModel('guestrides');
        $order = $model->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        $view = new Core\App\View();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'order' => $order
        ];
        $view->index($this->view);
    }
    public function rejectRide()
    {
        plugin::load('models, response, auth, email');
        $response = new Core\App\Response();
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if (!Auth::CheckPerm('appointment_update')) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Sinulla ei ole oikeuksia tehdä tätä toimenpidettä'
            ]);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $reason = $data['reason'];
        $models = new Core\App\Models\MainModel();
        $models->CallModel('guestrides');
        $orderid = $models->Select([
            'columns' => 'email, order_date, order_time',
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if (count($orderid) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'orderdoesnotexist'
            ]);
            return;
        }

        $id = $this->params[0];
        $time = $orderid[0]['order_time'];
        $to = $orderid[0]['email'];
        $date = date('d.m.Y', strtotime($orderid[0]['order_date']));
        $subject = 'localhost - Tilaus peruutettu';

        $email = new Core\App\Email();
        $email->SetHeader('from', 'localhost <noreply@localhost>');
        $email->SetHeader('mime', '1.0');
        $email->SetHeader('content-type', 'text/html; charset="utf-8"');
        $email->enableBCC = true;
        $email->SetRecipients([$to, 'tilaus@localhost']);
        $email->SetSubject($subject);
        $email->Template('cancelorder', [
            'subject' => $subject,
            'id' => $id,
            'date' => $date,
            'time' => $time,
            'reason' => $reason
        ]);
        $didEmailSend = $email->Send();
        $update = $models->Update([
            'where' => [
                'uuid' => $this->params[0]
            ],
            'data' => [
                'order_status' => 'cancelled',
                'order_comment' => $reason
            ]
        ]);
        $response->Send('json', [
            'status' => true,
            'msg' => $update
        ]);
    }
    public function acceptRide()
    {
        plugin::load('models, auth, response, email');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $response = new Core\App\Response();
        $models = new Core\App\Models\MainModel();
        $models->CallModel('guestrides');
        $orderid = $models->Select([
            'columns' => 'email, order_date, order_time',
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if (count($orderid) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'orderdoesnotexist'
            ]);
            return;
        }
        $id = $this->params[0];
        $time = $orderid[0]['order_time'];
        $to = $orderid[0]['email'];
        $date = date('d.m.Y', strtotime($orderid[0]['order_date']));
        $subject = 'localhost - Tilaus hyväksytty';
        $email = new Core\App\Email();
        $email->SetHeader('from', 'localhost <noreply@localhost>');
        $email->SetHeader('mime', '1.0');
        $email->SetHeader('content-type', 'text/html; charset="utf-8"');
        $email->enableBCC = true;
        $email->SetRecipients([$to, 'tilaus@localhost']);
        $email->SetSubject($subject);
        $email->Template('confirmorder', [
            'subject' => $subject,
            'id' => $id,
            'date' => $date,
            'time' => $time
        ]);
        $didEmailSend = $email->Send();
        $models->Update([
            'where' => [
                'uuid' => $this->params[0]
            ],
            'data' => [
                'order_status' => 'confirmed'
            ]
        ]);
        header('Location: /admin/kyytitilaukset/' . $this->params[0]);
    }
    public function editRide()
    {
        var_dump($this->params);
    }
}
