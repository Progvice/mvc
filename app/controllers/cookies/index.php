<?php 

use Core\App\Response;

class cookiesController extends Controller {
    public function cookies() {}
    public function consent() {
        Plugin::load('response');
        $response = new Response();
        switch($this->params[0]) {
            case 'acceptall':
                $expire = time() + 15552000;
                $cookieSet = setcookie('consent', 'all', $expire, '/', CONFIG['server']['host']);
                if (!$cookieSet) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['cookiesnotset']
                    ]);
                    return;
                }

                $response->Send('json', [
                    'status' => true,
                    'msg' => LANG['allcookiesaccepted']
                ]);
            break;
            case 'acceptnecessary':
                $expire = time() + 15552000;
                $cookieSet = setcookie('consent', 'necessary', $expire, '/', CONFIG['server']['host']);
                if (!$cookieSet) {
                    $response->Send('json', [
                        'status' => false,
                        'msg' => LANG['cookiesnotset']
                    ]);
                    return;
                }
                $response->Send('json', [
                    'status' => true,
                    'msg' => LANG['necessarycookiesaccepted']
                ]);
            break;
            default:
                $response->Send('json', [
                    'status' => false,
                    'msg' => 'Invalid cookie consent'
                ]);
            break;
        }
    }
}

?>