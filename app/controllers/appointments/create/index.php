        <?php
        class appointmentsController extends Controller
        {
            public function appointments()
            {
                plugin::load('response, models, uuid');
                $response = new Core\App\Response();
                $data = json_decode(file_get_contents("php://input"));
                $models = new Core\App\Models\MainModel();
                $models->CallModel('appointments');
                if (!isset($_SESSION['login'])) {
                    header('Location: /login');
                    return;
                }
                $list = ['title', 'description', 'slotfrom', 'slotto', 'date'];
                $requirements = true;
                $notset = '';
                foreach ($list as $req) {
                    if (!isset($data->$req)) {
                        $requirements = false;
                        $notset = $req;
                        break;
                    }
                }
                if (!$requirements) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['allfieldsarenotset'],
                        'field' => $notset
                    ]);
                    return;
                }

                $title = LANG['appointmenttypes'][$data->title];
                $titleDB = $data->title;
                $description = htmlspecialchars($data->description);
                $slotfrom = intval(htmlspecialchars($data->slotfrom));
                $slotto = intval(htmlspecialchars($data->slotto));
                $date = htmlspecialchars($data->date);
                $licenseplate = $data->licenseplate ? $data->licenseplate : null;

                if (!preg_match('/^[A-Za-z0-9]{2,3}-[A-Za-z0-9]{2,3}$/', $licenseplate) && isset($licenseplate)) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['appointments']['licenseplateinvalid']
                    ]);
                    return;
                }
                if (!isset($title, $description, $slotfrom, $slotto, $date)) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['appointments']['allvaluesarenotset']
                    ]);
                    return;
                }

                if ($slotto >= 44) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['appointments']['overtime']
                    ]);
                    return;
                }

                if ($slotfrom < 1) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['appointments']['undertime']
                    ]);
                    return;
                }
                $slotcount = $slotto - $slotfrom;
                if ($slotcount >= 5) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['appointments']['toomanyslots']
                    ]);
                    return;
                }

                $appointments = $models->Select([
                    'values' => [
                        'normal' => [
                            'userid' => $_SESSION['login']['uuid']
                        ],
                        'bigger' => [
                            'appointment_day' => date("Y-m-d")
                        ]
                    ]
                ]);

                if (count($appointments) >= 3) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['appointments']['limitexceeded']
                    ]);
                    return;
                }
                $appointments = $models->Select([
                    'values' => [
                        'normal' => [
                            'appointment_day' => $date
                        ]
                    ],
                    'columns' => 'slot_from, slot_to'
                ]);
                $rval = [];

                if (count($appointments) > 0) {
                    $slotto_reserved = false;
                    $slotfrom_reserved = false;

                    foreach ($appointments as $appointment) {
                        if (!$slotto_reserved) {
                            if ($slotto >= $appointment['slot_to'] && $slotto < $appointment['slot_from']) {
                                $slotto_reserved = true;
                            }
                        }
                        if (!$slotfrom_reserved) {
                            if ($slotfrom < $appointment['slot_to'] && $slotfrom >= $appointment['slot_from']) {
                                $slotfrom_reserved = true;
                            }
                        }
                    }
                    if ($slotto_reserved || $slotfrom_reserved) {
                        $rval = [
                            'status' => false,
                            'msg' => LANG['appointments']['overlap']
                        ];
                    }
                    if ($rval) {
                        $response->Send('json', $rval);
                        return;
                    }
                }
                plugin::load('email');
                $models->CallModel('users');
                $user = $models->Select([
                    'columns' => 'email, phonenumber, firstname, lastname',
                    'values' => [
                        'normal' => [
                            'uuid' => $_SESSION['login']['uuid']
                        ]
                    ]
                ]);
                require_once(__DIR__ . '/../times.php');
                $times = appointmentTimes();
                $seconds = $times[$slotfrom] * 3600;
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $time = sprintf('%02d:%02d', $hours, $minutes);
                $subject = 'localhost - Ajanvaraus vahvistus';

                $formatDate = new DateTime($date);
                $finalDate = $formatDate->format('d.m.Y');

                $email = new Core\App\Email();
                $email->SetHeader('from', 'localhost <noreply@localhost>');
                $email->SetHeader('mime', '1.0');
                $email->SetHeader('content-type', 'text/html; charset="utf-8"');
                $email->enableBCC = true;
                $email->SetRecipients([$user[0]['email'], 'tilaus@localhost']);
                $email->SetSubject($subject);
                $email->Template('appointment', [
                    'subject' => $subject,
                    'title' => $title,
                    'fullname' => $user[0]['firstname'] . ' ' . $user[0]['lastname'],
                    'phonenumber' => $user[0]['phonenumber'],
                    'description' => $description,
                    'date' => $finalDate,
                    'time' => $time,
                    'licenseplate' => $licenseplate
                ]);
                $email->Send();
                $models->CallModel('appointments');
                if ($slotcount < 5) {
                    $uuid = new Core\App\UUID;
                    $retval = $models->Insert([
                        'uuid' => $uuid->Create(),
                        'userid' => $_SESSION['login']['uuid'],
                        'appointment_day' => $date,
                        'slot_from' => $slotfrom,
                        'slot_to' => $slotto,
                        'title' => $titleDB,
                        'description' => $description,
                        'status' => 'unconfirmed'
                    ]);
                    $response->Send('json', [
                        'status' => true,
                        'msg' => LANG['appointments']['success'],
                        'sql' => $retval
                    ]);
                    return;
                }
                $response->Send('json', [
                    'status' => false,
                    'msg' => LANG['appointments']['unknownerror']
                ]);
            }
        }
        ?>