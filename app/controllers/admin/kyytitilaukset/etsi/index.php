<?php 

use Core\App\Auth;
use Core\App\View;
use Core\App\Response;
use Core\App\Models\MainModel;

class kyytitilauksetSearchController extends Controller {
    public function etsi() {
        Plugin::load('view, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new View();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'test' => 'test'
        ];
        $view->index($this->view);
    }
    public function postSearch() {
        Plugin::load('auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $dbArr = [];
        foreach ($data as $field => $value) {
            if ($field === 'uuid') {
                continue;
            }
            if (!empty($value)) {
                $dbArr[$field] = $value; 
            }
        }
        Plugin::load('response, models');
        $response = new Response();
        $models = new MainModel();
        $models->CallModel('guestrides');
        $dbFinalArray = [
            'values' => [
                'normal' => $dbArr
            ]
        ];
        if (!empty($data['uuid'])) {
            $dbFinalArray['values']['starts'] = ['uuid' => $data['uuid']];
        }
        if (count($dbFinalArray['values']['normal']) < 1 && empty($dbFinalArray['values']['starts'])) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Hakuehtoja ei ole määritetty'
            ]);
            return;
        }
        $rides = $models->Select($dbFinalArray);
        if (count($rides) > 0) {
            $response->Send('json', [
                'status' => true,
                'msg' => 'Tilauksia löytyi ' . count($rides) . ' kappaletta',
                'data' => $rides
            ]);
            return;
        }
        $response->Send('json', [
            'status' => true,
            'msg' => 'Tilauksia ei löytynyt'
        ]);
    }
}

?>