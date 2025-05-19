<?php 

use Core\App\Models\MainModel;
use Core\App\View;
use Core\App\Template;
use Core\App\Auth;

class appointmentsController extends Controller {
    function appointments() {
        $times = [
            1 => 7.5,
            2 => 7.75,
            3 => 8,
            4 => 8.25,
            5 => 8.5,
            6 => 8.75,
            7 => 9,
            8 => 9.25,
            9 => 9.5,
            10 => 9.75,
            11 => 10,
            12 => 10.25,
            13 => 10.5,
            14 => 10.75,
            15 => 11,
            16 => 11.25,
            17 => 11.5,
            18 => 11.75,
            19 => 12,
            20 => 12.25,
            21 => 12.5,
            22 => 12.75,
            23 => 13,
            24 => 13.25,
            25 => 13.5,
            26 => 13.75,
            27 => 14,
            28 => 14.25,
            29 => 14.5,
            30 => 14.75,
            31 => 15,
            32 => 15.25,
            33 => 15.5,
            34 => 15.75,
            35 => 16,
            36 => 16.25,
            37 => 16.5,
            38 => 16.75,
            39 => 17,
            40 => 17.25,
            41 => 17.5,
            42 => 17.75,
            43 => 18
        ];
        Plugin::load('view, templateloader, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $model = new MainModel();
        $model->CallModel('appointments');
        $date = date_create(date('Y-m-d'));
        date_sub($date, date_interval_create_from_date_string("1 days"));

        $appointments = $model->Select([
            'values' => [
                'bigger' => [
                    'appointment_day' => date_format($date, 'Y-m-d')
                ]
            ]
        ]);
        $view = new View;
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $finalAppointments = [];
        foreach ($appointments as $appointment) {
            foreach ($appointment as $column => $value) {
                if ($column === 'slot_from') {
                    $slot_from_arr = $times[$value];
                    $appointment['slot_from'] = sprintf('%02d:%02d:%02d', (($slot_from_arr*3600) / 3600), (($slot_from_arr*3600) / 60 % 60), (($slot_from_arr*3600) % 60));
                }
                else if ($column === 'slot_to') {
                    $slot_val = $value;
                    $slot_to_arr = $times[$slot_val];
                    $appointment['slot_to'] = sprintf('%02d:%02d:%02d', (($slot_to_arr*3600) / 3600), (($slot_to_arr*3600) / 60 % 60), (($slot_to_arr*3600) % 60));
                }
            }
            $finalAppointments[] = $appointment;
        }
        $view->variables = [
            'appointments' => $finalAppointments
        ];
        $view->index($this->view);
    }
    function viewAppointment() {
        Plugin::load('view, templateloader, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if (!Auth::CheckPerm('appointment_other')) {
            header('Location: /admin/forbidden');
            return;
        }
        $model = new MainModel();
        $model->CallModel('appointments');
        $appointment = $model->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        $times = [
            1 => 7.5,
            2 => 7.75,
            3 => 8,
            4 => 8.25,
            5 => 8.5,
            6 => 8.75,
            7 => 9,
            8 => 9.25,
            9 => 9.5,
            10 => 9.75,
            11 => 10,
            12 => 10.25,
            13 => 10.5,
            14 => 10.75,
            15 => 11,
            16 => 11.25,
            17 => 11.5,
            18 => 11.75,
            19 => 12,
            20 => 12.25,
            21 => 12.5,
            22 => 12.75,
            23 => 13,
            24 => 13.25,
            25 => 13.5,
            26 => 13.75,
            27 => 14,
            28 => 14.25,
            29 => 14.5,
            30 => 14.75,
            31 => 15,
            32 => 15.25,
            33 => 15.5,
            34 => 15.75,
            35 => 16,
            36 => 16.25,
            37 => 16.5,
            38 => 16.75,
            39 => 17,
            40 => 17.25,
            41 => 17.5,
            42 => 17.75,
            43 => 18
        ];
        foreach ($appointment[0] as $column => $value) {
            if ($column === 'slot_from') {
                $slot_from_arr = $times[$value];
                $appointment[0]['slot_from'] = sprintf('%02d:%02d:%02d', (($slot_from_arr*3600) / 3600), (($slot_from_arr*3600) / 60 % 60), (($slot_from_arr*3600) % 60));
            }
            else if ($column === 'slot_to') {
                $slot_val = $value;
                $slot_to_arr = $times[$slot_val];
                $appointment[0]['slot_to'] = sprintf('%02d:%02d:%02d', (($slot_to_arr*3600) / 3600), (($slot_to_arr*3600) / 60 % 60), (($slot_to_arr*3600) % 60));
            }
        }
        $userid = $appointment[0]['userid'];
        $model->CallModel('users');
        $userdata = $model->Select([
            'columns' => 'firstname, lastname, email, phonenumber',
            'values' => [
                'normal' => [
                    'uuid' => $userid
                ]
            ]
        ]);
        $view = new View();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'user' => $userdata[0],
            'appointment' => $appointment[0]
        ];
        $view->index($this->view);
    }
}

?>