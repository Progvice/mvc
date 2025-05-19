<?php

namespace Core\App;
use \Firebase\JWT\JWT;

class JwtExtra {
    private $jwts;
    private $alg;
    public function __construct() {
        require COMPOSER;
        $jwtf = json_decode(file_get_contents(APP_PATH . '/jwt.json'));
        $this->jwts = $jwtf->private;
        $this->alg = $jwtf->alg;
    }
    public function GetToken() {
        $headers = getallheaders();
        $finalheader = '';
        if (empty($headers['authorization']) && isset($headers['Authorization'])) {
            $finalheader = $headers['Authorization'];
        }
        else if (empty($headers['Authorization']) && isset($headers['authorization'])) {
            $finalheader = $headers['authorization'];
        }
        else {
            return false;
        }
        $auth = explode('Bearer', $finalheader);
        $token = trim($auth[1]);
	$token = str_replace(['\\', '"'], '', $token);
        return $token;
    }
    public function Decode($token) {
        $data = JWT::decode(trim($token), $this->jwts, array($this->alg));
        return $data;
    }
    public function Verify($token) {
        $data = $this->Decode($token);
        if ($data->expire < time()) {
            return false;
        }
        if ($data->iss !== $_SERVER['SERVER_NAME']) {
            return false;
        }
        return true;
    }
    /* 
     *  @param string key
     *
     *  
     * 
     */
    public function CheckExpiration($key) {

    }
}

?>
