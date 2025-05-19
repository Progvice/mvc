<?php 

use Core\App\Auth;
use Core\App\View;
use Core\App\Models\MainModel;
use Core\App\Response;

class permissionsController extends Controller {
    public function permissions() {
        Plugin::load('view, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $models = new MainModel();
        $models->CallModel('perms');
        $perms = $models->Select([
            'columns' => 'perm_name'
        ]);
        $view = new View();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'perms' => $perms,
            'perm_create' => Auth::CheckPerm('groups_create')
        ];
        $view->index($this->view);
    }
    public function viewPerm() {
        Plugin::load('view, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if (!Auth::CheckPerm('groups_read')) {
            header('Location: /admin/forbidden');
            return;
        }
        $view = new View();
        $models = new MainModel();
        $models->CallModel('perms');
        $perm = $models->Select([
            'values' => [
                'normal' => [
                    'perm_name' => urldecode($this->params[0])
                ]
            ]
        ]);
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        if (count($perm) < 1) {
            $view->variables = [
                'error' => 'invalidperm'
            ];
            $view->index($this->view);
            return;
        }
        $view->variables = [
            'perm' => $perm,
            'perm_delete' => Auth::CheckPerm('groups_remove')
        ];
        $view->index($this->view);
    }
    public function modifyPerm() {
        Plugin::load('response, models, auth');
        $response = new Response();
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        if (!Auth::CheckPerm('groups_update')) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Ei oikeuksia muokata käyttöoikeuksia'
            ]);
            return;
        }
        $models = new MainModel();
        $data = json_decode(file_get_contents('php://input'), true);
        $priority = Auth::GetPerm('perm_priority');
        $perm = Auth::GetPerm('perm_name');
        if ($priority < $data['perm_priority'] && $perm !== 'admin') {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Et voi vaihtaa ' . $data['perm_name'] . ' oikeuksia, koska ' . $data['perm_name'] . ' on korkeampi arvoinen käyttöoikeus.'
            ]);
            return;
        }
        $perm_name = $data['perm_name'];
        unset($data['perm_name']);
        $models->CallModel('perms');
        $changeperm = $models->Update([
            'where' => [
                'perm_name' => $perm_name
            ],
            'data' => $data
        ]);
        $response->Send('json', [
            'status' => true,
            'msg' => 'Käyttöoikeuksien muuttaminen onnistui!'
        ]);
    }
    public function createPerm() {
        Plugin::load('models, auth, response');
        $response = new Response();
        if (!Auth::CheckPerm('groups_create')) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Sinulla ei ole oikeuksia luoda käyttöoikeuksia'
            ]);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $models = new MainModel();
        $models->CallModel('perms');
        $checkInsert = $models->Insert([
            'perm_name' => $data['name']
        ]);
        if (!$checkInsert['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Ryhmän luonti epäonnistui - ' . $checkInsert['msg']
            ]);
            return;
        }
        $response->Send('json', [
            'status' => true,
            'msg' => 'Uusi ryhmä lisätty onnistuneesti'
        ]);
    }
    public function removePerm() {
        Plugin::load('models, auth, response');
        $response = new Response();
        $models = new MainModel();
        $models->CallModel('perms');

        if (!Auth::CheckPerm('groups_remove')) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Sinulla ei ole oikeuksia poistaa käyttöoikeuksia'
            ]);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['name'] === 'default' || $data['name'] === 'admin') {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Ylläpitäjä- ja oletusryhmää ei voi poistaa'
            ]);
            return;
        }
        $perm = $models->Select([
            'values' => [
                'normal' => [
                    'perm_name' => $data['name']
                ]
            ]
        ]);
        if (count($perm) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Ryhmää ei ole olemassa'
            ]);
            return;
        }
        $priority = Auth::GetPerm('perm_priority');
        $currentPerm = Auth::GetPerm('perm_name');
        if ($priority < $perm[0]['perm_priority'] && $currentPerm !== 'admin') {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Et voi poistaa ' . $perm[0]['perm_name'] . ' ryhmää, koska ' . $perm[0]['perm_name'] . ' on korkeampi arvoinen käyttöoikeusryhmä.'
            ]);
            return;
        }
        $checkDelete = $models->Delete([
            'where' => [
                'perm_name' => $data['name']
            ]
        ]);
        if (!$checkDelete['status']) {
            $response->Send('json', [
                'status' => false,
                'msg' => 'Ryhmän poistaminen epäonnistui - ' . $checkDelete['msg']
            ]);
            return;
        }
        $response->Send('json', [
            'status' => true,
            'msg' => 'Ryhmä poistettu onnistuneesti'
        ]);
    }
}
?>