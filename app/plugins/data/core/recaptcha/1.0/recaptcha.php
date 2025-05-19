<?php
namespace Core\App;
class Recaptcha {
    public $json;
    public $secret_key;
    public $response;
    public $returnvalue;
    public function __construct() {
        $this->json = file_get_contents(PLUGIN_PATH . '/json/recaptcha.json');
        $this->json = json_decode($this->json);
        $this->secret_key = $this->json->private_key;
        $this->confirm();
    }
    public function confirm() {
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $this->secret_key . '&response=' . $_POST['g-recaptcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
        $response = file_get_contents($url);
        $this->response = json_decode($response, true);
        if (isset($this->response['error-codes'][0])) {
            switch($this->response['error-codes'][0]) {
                case 'missing-input-secret':
                    $this->returnvalue = 'ReCaptcha secret key is missing. Please contact site admin!';
                break;
                case 'invalid-input-secret':
                    $this->returnvalue = "ReCaptcha secret key is invalid. Please contact site admin.";
                break;
                case 'missing-input-response':
                    $this->returnvalue = "toastr.warning('ReCaptcha validation failed')";
                break;
                case 'invalid-input-response':
                    $this->returnvalue = "ReCaptcha input validation is invalid or malformed";
                break;
                case 'bad-request':
                    $this->returnvalue = "ReCaptcha failed to process request at Google servers.";
                break;
                default:
                    $this->returnvalue = "Unknown error! Contact server admin";
                break;
            }
            echo $this->returnvalue;
            exit;
        }
    }
}
?>
