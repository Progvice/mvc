<?php
    class appointmentsController extends Controller {
        public function appointments() {
            plugin::load('response, models');
            $models = new Core\App\Models\MainModel();
            $response = new Core\App\Response();
            $models->CallModel('appointments');
            $data = json_decode(file_get_contents('php://input'));
            if(!isset($data->date)) {
                $response->Send('json', [
                    'status' => false,
                    'msg' => 'dateisnotset'
                ]);
                return;
            }
            $appointments = $models->Select([
                'values' => [
                    'normal' => [
                        'appointment_day' => $data->date
                    ]
                ],
                'columns' => 'slot_from, slot_to'
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
            $times_second = [];
            $timetable = [];
            foreach($times as $slot => $time) {
                $times_second[$slot] = $time*3600;
            }
            if(empty($appointments)) {
                $fcounter = 1;
                foreach($times_second as $slot => $time) {
                    $slotint = intval($slot);
                    $timetable[$slotint] = [
                        'slot' => $slot,
                        'time' => $times[$fcounter],
                        'available' => true
                    ];
                    $fcounter++;
                }
                $response->Send('json', [
                    'status' => true,
                    'data' => $timetable
                ]);
                return;
            }
            foreach($times_second as $slot => $time) {
                foreach($appointments as $appointment) {
                    $slot_from = intval($appointment['slot_from']);
                    $slot_to = intval($appointment['slot_to']);
                    if($slot_from === $slot || $slot_to === $slot) {
                        $counter_to = $slot_to;
                        $counter_from = $slot_from;
                        for($counter_from; $counter_from <= $counter_to; $counter_from++) {
                            if($counter_from !== $counter_to) {
                                $timetable[$counter_from] = [
                                    'slot' => $counter_from,
                                    'time' => $times[$counter_from],
                                    'available' => false
                                ];
                                continue;
                            }
                            $timetable[$counter_from] = [
                                'slot' => $counter_from,
                                'time' => $times[$counter_from],
                                'available' => true
                            ];
                        }
                    }
                    else {
                        if(!isset($timetable[$slot])) {
                            $timetable[$slot] = [
                                'slot' => $slot,
                                'time' => $times[$slot],
                                'available' => true
                            ];
                        }
                    }
                }
            }
            $response->Send('json', [
                'status' => true,
                'data' => $timetable
            ]);
        }
    }
?>