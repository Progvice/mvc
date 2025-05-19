<?php 

use Core\App\Auth;
use Core\App\Response;
use Core\App\Models\MainModel;
use Core\App\UUID;

class uploadController extends Controller {
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        Plugin::load('response, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }

        $response = new Response();
        if (!isset($_FILES['file'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Tiedostoa ei ole lähetetty'
            ]);
            return;
        }
        if (!getimagesize($_FILES['file']['tmp_name'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Tiedosto ei ole kuvatiedosto!'
            ]);
            return;
        }
        $filetype = explode('/', $_FILES['file']['type']);
        if ($filetype[0] !== 'image') {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Tiedosto ei ole kuvatiedosto!'
            ]);
            return;
        }
        $eName = explode('.', $_FILES['file']['name']);
        $fileExtension = end($eName);
        if (!empty($_POST['name'])) {
            if (!preg_match('/^([A-Za-z0-9ÄÖäö\s]){2,200}$/', $_POST['name'])) {
                $response->Send('json', [
                    'status' => false,
                    'msg' => 'Kuvan nimi on virheellinen. Kuvan nimessä voi olla vain kirjaimia ja numeroita.'
                ]);
                return;
            }
            $name = str_replace(' ', '_', $_POST['name']);
            if (file_exists(APP_PATH . '/../public/img/' . $name . '.' . $fileExtension)) {
                $name = $name . '_' . time();
            }
        }
        else {
            $date = new DateTime();
            $dstring = $date->format('Ymd');
            $name = $dstring . '_' . time();
        }
        $destination = APP_PATH . '/../public/img/' . $name . '.' . $fileExtension;
        $moveFile = move_uploaded_file($_FILES['file']['tmp_name'], $destination);
        if (!$moveFile) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Tiedostoa ei ladattu palvelimelle'
            ]);
            return;
        }
        $imgSize = getimagesize(APP_PATH . '/../public/img/' . $name . '.' . $fileExtension);
        if (!is_array($imgSize)) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Tiedosto ei ole kuvatiedosto.'
            ]);
            return;
        }
        Plugin::load('models, uuid');
        $uuid = new UUID();
        $models = new MainModel();
        $models->CallModel('galleryimg');
        $insertion = $models->Insert([
            'uuid' => $uuid->Create(),
            'gname' => $_POST['gname'],
            'imgname' => $name,
            'fname' => $name . '.' . $fileExtension,
            'imgpath' => '/img/' . $name . '.' . $fileExtension,
            'description' => $_POST['description']
        ]);
        if (!$insertion['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Kuvaa ei lisätty tietokantaan onnistuneesti. '
            ]);
            return;
        }
        $response->Send('json', [
            'status' => true,
            'msg' => 'Kuva lisättiin onnistuneesti galleriaan!'
        ]);
        
    }
}

?>