<?php

use Core\App\Auth;

class templatesController extends Controller {
    public function templates() {
        plugin::load('view, templateloader, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new Core\App\View;
        $template = new Core\App\Template();
        $list = $this->GetSiteData();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'sitedata' => $list
        ];
        $view->index($this->view);
    }
    private function GetSiteData() {
        $siteDataList = json_decode(file_get_contents(DATA_PATH . '/templatelist.json'));
        $sitedata = [];
        foreach($siteDataList as $sitename => $uri) {
            $siteFinalData = json_decode(file_get_contents(DATA_PATH . $uri . '/index.json'));
            $sitedata[$sitename]['uri'] = $uri;
            foreach($siteFinalData as $block_name => $data) {
                $sitedata[$sitename][$block_name] = $data;
            }
        }
        return $sitedata;
    }
    public function getTemplate() {
        plugin::load('view, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new Core\App\View;
        $view->variables = [
            'paramSet' => true,
            'paramsUri' => $this->params
        ];
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->index($this->view);
    }
    public function editTemplate() {
        plugin::load('view, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new Core\App\View;
        $view->variables = [
            'paramSet' => true,
            'paramsUri' => $this->params
        ];
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->index($this->view);
    }
}

?>