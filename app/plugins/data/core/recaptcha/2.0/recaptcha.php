<?php
namespace Core\App;
class Recaptcha2 {
    public $json;
    public $secret_key;
    public $returnvalue;
    public function __construct() {
        $this->json = file_get_contents(PLUGIN_PATH . '/json/recaptcha.json');
        $this->json = json_decode($this->json);
        $this->secret_key = $this->json->private_key;
    }
    public function Confirm($client_token) {
        $user_ip_addr = '';
        // Check that if service is used behind Clourflare.
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $user_ip_addr = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        else {
            $user_ip_addr = $_SERVER['REMOTE_ADDR'];
        }
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $post_data = http_build_query([
            'secret' => $this->secret_key,
            'response' => $client_token,
            'remoteip' => $user_ip_addr
        ]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (!$res['success']) {
            $msg = '';
            switch($res['error-codes'][0]) {
                case 'missing-input-secret':
                    $msg = LANG['recaptcha']['missing_input_secret'];
                break;
                case 'invalid-input-secret':
                    $msg = LANG['recaptcha']['invalid_input_secret'];
                break;
                case 'missing-input-response':
                    $msg = LANG['recaptcha']['missing_input_response'];
                break;
                case 'invalid-input-response':
                    $msg = LANG['recaptcha']['invalid_input_response'];
                break;
                case 'bad-request':
                    $msg = LANG['recaptcha']['bad_request'];
                break;
                case 'timeout-or-duplicate':
                    $msg = LANG['recaptcha']['timeout_or_duplicate'];
                break;
                default:
                    $msg = LANG['recaptcha']['default'];
                break;
            }
            return [
                'status' => false,
                'msg' => $msg
            ];
        }
        return [
            'status' => true,
            'msg' => 'captchaok'
        ];
    }
}
?>