<?php 

use Core\App\Auth;
use Core\App\View;
use Core\App\Response;
use Core\App\Models\MainModel;

class etsiController extends Controller {
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
        $view->index($this->view);
    }
    public function postSearch() {
        Plugin::load('response, models, auth');
        $response = new Response();
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if (!Auth::CheckPerm('user_read')) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Sinulla ei ole oikeuksia hakea käyttäjätietoja'
            ]);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $dbArr = [];
        foreach ($data as $field => $value) {
            if (!empty($value)) {
                $dbArr[$field] = $value; 
            }
        }
        $models = new MainModel();
        $models->CallModel('users');
        $users = $models->Select([
            'columns' => 'email, firstname, lastname, uuid, permgroup',
            'values' => [
                'normal' => $dbArr
            ]
        ]); 
        $response->Send('json', [
            'status' => true,
            'msg' => 'Käyttäjät haettu onnistuneesti',
            'data' => $users
        ]);
    }
}

?>