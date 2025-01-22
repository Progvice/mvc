<?php 

use Core\App\Auth;
use Core\App\Response;
use Core\App\Models\MainModel;

class usersController extends Controller {
    public function users() {
        plugin::load('view, templateloader, models, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }

        $view = new Core\App\View;
        $template = new Core\App\Template();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->index($this->view);
    }
    public function getUserByID() {
        plugin::load('view, templateloader, models, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if(!Auth::CheckPerm('user_read')) {
            header('Location: /admin/forbidden');
            return;
        }
        $view = new Core\App\View;
        $userid = htmlspecialchars($this->params[0]);
        $models = new MainModel();
        $models->CallModel('users');
        $user = $models->Select([
            'columns' => 'firstname, lastname, email, permgroup, uuid, phonenumber',
            'values' => [
                'normal' => [
                    'uuid' => $userid
                ]
            ]
        ]); 
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        if(count($user) < 1) {
            $view->variables = [
                'error' => true,
                'msg' => 'Käyttäjää ei löytynyt'
            ];
            $view->index($this->view);
            return;
        }
        $models->CallModel('perms');
        $perms = $models->Select([
            'columns' => 'perm_name'
        ]);

        $view->variables = [
            'user' => $user,
            'perms' => $perms
        ];
        $view->index($this->view);
    }
    public function updateUser() {
        plugin::load('response, models, auth');
        $response = new Response;
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if(!Auth::CheckPerm('user_update')) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Sinulla ei ole oikeuksia muuttaa käyttäjätietoja'
            ]);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $models = new MainModel();
        $models->CallModel('users');
        $userperm = $models->Select([
            'columns' => 'permgroup',
            'values' => [
                'normal' => [
                    'uuid' => $data['uuid']
                ]
            ]
        ]);
        if(count($userperm) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'permnotset'
            ]);
            return;
        }

        $models->CallModel('perms');
        $perm = $models->Select([
            'columns' => 'perm_priority',
            'values' => [
                'normal' => [
                    'perm_name' => $userperm[0]['permgroup']
                ]
            ]
        ]);
        $currentPerm = Auth::GetPerm('perm_priority');
        $perm_name = Auth::GetPerm('perm_name');
        if($perm[0]['perm_priority'] >= $currentPerm && $perm_name !== 'admin') {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Sinulla ei ole oikeutta muokata käyttäjiä joilla on isompi tai yhtä suuri käyttöoikeus'
            ]);
            return;
        }
        $models->CallModel('users');
        $userupdate = $models->Update([
            'where' => [
                'uuid' => $data['uuid']
            ],
            'data' => $data['data']
        ]);
        if(!$userupdate['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => $userupdate['msg']
            ]);
            return;
        }
        $response->Send('json', [
            'status' => $userupdate['status'],
            'msg' => 'Käyttäjä ' . LANG[$userupdate['msg']] . ' onnistuneesti'
        ]);

    }
}

?>