<?php

use Core\App\Models\MainModel;
use Core\App\View;

class ajanvarauksetController extends Controller {
    public function ajanvaraukset() {
        if (!isset($_SESSION['login'])) {
            header('Location: /login');
            return;
        }
        Plugin::load('view, models');
        $models = new MainModel();
        $models->CallModel('appointments');
        $appointments = $models->Select([
            'values' => [
                'normal' => [
                    'userid' => $_SESSION['login']['uuid']
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
        $appointments_new = [];
        $counter = 0;
        foreach ($appointments as $appointment) {
            $appointments_new[$counter] = $appointment;
            $slot_to = intval($appointment['slot_to']);
            $slot_from = intval($appointment['slot_from']);
            $slot_to_arr = $times[$slot_to];
            $slot_from_arr = $times[$slot_from];
            $appointments_new[$counter]['slot_from'] = sprintf('%02d:%02d:%02d', (($slot_from_arr*3600) / 3600), (($slot_from_arr*3600) / 60 % 60), (($slot_from_arr*3600) % 60));
            $appointments_new[$counter]['slot_to'] = sprintf('%02d:%02d:%02d', (($slot_to_arr*3600) / 3600), (($slot_to_arr*3600) / 60 % 60), (($slot_to_arr*3600) % 60));
            $counter++;
        }
        $view = new View();
        $view->variables = [
            'appointments' => $appointments_new
        ];
        $view->index($this->view);
    }
    public function getUsersOrders() {
        Plugin::load('view, models');
        $view = new View();
        $models = new MainModel();
        $models->CallModel('appointments');
        $appointment = $models->Select([
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
        if (count($appointment) < 1) {
            header('Location: /404');
            return;
        }
        if ($appointment[0]['userid'] !== $_SESSION['login']['uuid']) {
            header('Location: /404');
            return;
        }
        $appointment_new = [];
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
            /*$appointment_new[$counter] = $appointment;
            $slot_to = intval($appointment['slot_to']) + 1;
            $slot_from = intval($appointment['slot_from']);
            $slot_to_arr = $times[$slot_to];
            $slot_from_arr = $times[$slot_from];
            $appointment_new[$counter]['slot_from'] = sprintf('%02d:%02d:%02d', (($slot_from_arr*3600) / 3600), (($slot_from_arr*3600) / 60 % 60), (($slot_from_arr*3600) % 60));
            $appointment_new[$counter]['slot_to'] = sprintf('%02d:%02d:%02d', (($slot_to_arr*3600) / 3600), (($slot_to_arr*3600) / 60 % 60), (($slot_to_arr*3600) % 60));
            */
        }
        $view->variables = [
            'appointment' => $appointment[0]
        ];
        $view->index(VIEW_PATH . $this->base_uri . '/id' . '/index.php');
    }
}

?>