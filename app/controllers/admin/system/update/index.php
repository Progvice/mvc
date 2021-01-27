<?php

class updateController extends Controller {
    public function update() {
        $update = json_decode(file_get_contents('http://jjmvc.net/update'));
        $configuration = json_decode(file_get_contents(PLUGIN_PATH . '/plugins.json'));
        if(isset($configuration->version_stage)) {
            switch($configuration->version_stage) {
                case "alpha":
                    $cv_stage = 1;
                    break;
                case "beta":
                    $cv_stage = 2;
                    break;
                case "release":
                    $cv_stage = 3;
                    break;
                default:
                    echo 'No proper configuration version stage is set. Cancelling update.';
                    return;
                    break;
            }
        }
        else {
            echo 'Configuration does not have version stage set.';
            return;
        }
        if(isset($update->version_stage)) {
            switch($update->version_stage) {
                case "alpha":
                    $uv_stage = 1;
                    break;
                case "beta":
                    $uv_stage = 2;
                    break;
                case "release":
                    $uv_stage = 3;
                    break;
                default:
                    echo 'No proper update version stage is set. Cancelling update.';
                    return;
                    break;
            }
        }
        else {
            echo 'Update does not have version stage set.';
            return;
        }
    }
}

?>