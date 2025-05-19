<?php

namespace Core\App;

class ReCaptcha3
{
    public function Confirm($client_token)
    {
        if (!isset(CONFIG['recaptcha_secret'])) {
            return [
                'status' => false,
                'msg' => 'Invalid secret key.'
            ];
        };
        $user_ip_addr = '';
        // Check that if service is used behind Clourflare.
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $user_ip_addr = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $user_ip_addr = $_SERVER['REMOTE_ADDR'];
        }
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $post_data = http_build_query([
            'secret' => CONFIG['recaptcha_secret'],
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
            switch ($res['error-codes'][0]) {
                case 'missing-input-secret':
                    $msg = 'ReCaptcha secret key is missing. Please contact site admin!';
                    break;
                case 'invalid-input-secret':
                    $msg = "ReCaptcha secret key is invalid. Please contact site admin.";
                    break;
                case 'missing-input-response':
                    $msg = "toastr.warning('ReCaptcha validation failed')";
                    break;
                case 'invalid-input-response':
                    $msg = "ReCaptcha input validation is invalid or malformed";
                    break;
                case 'bad-request':
                    $msg = "ReCaptcha failed to process request at Google servers.";
                    break;
                case 'timeout-or-duplicate':
                    $msg = 'ReCaptcha token has expired or has been used already.';
                    break;
                default:
                    $msg = "Unknown error! Contact server admin";
                    break;
            }
            return [
                'status' => false,
                'msg' => $msg
            ];
        }
        if ($res['score'] < 0.5) {
            return [
                'status' => false,
                'msg' => '403 Forbidden'
            ];
        }
        return [
            'status' => true,
            'msg' => 'ok'
        ];
    }
}
