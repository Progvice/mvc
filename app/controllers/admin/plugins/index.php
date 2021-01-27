<?php
class pluginsController extends Controller {
    public function plugins() {
        $json = file_get_contents(PLUGIN_PATH . '/plugins.json');
        $json = json_decode($json, true);
        plugin::load('core:view:1.0');
        $pluginlist = [];
        foreach($json as $author => $plugins) {
            foreach($plugins as $plugin_name => $version) {
                foreach($version as $versionnumber => $final) {
                    $pluginlist[] = array($author, $plugin_name, $versionnumber);
                }
            }
        }
        $variables = [
            "plugins" => $pluginlist,
            "test_two" => "test_value_again"
        ];
        $view = new Core\App\View;
        $view->variables = $variables;
        $view->customelements = ['header' => 'admin'];
        $view->index($this->title, $this->view);
    }
}
?>