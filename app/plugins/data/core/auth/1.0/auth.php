<?php 
namespace Core\App;

class Auth {
    public static function CheckPerm($name) {
        if(isset($_SESSION['login']['permgroup'][$name])) {
            if(!$_SESSION['login']['permgroup'][$name]) {
                return false;
            }
            return true;
        }
        return false;
    }
    public static function CheckLogin() {
        if(!isset($_SESSION['login'])) {
            return false;
        }
        return true;
    }
    public static function GetPerm($name) {
        if(isset($_SESSION['login']['permgroup'][$name])) {
            return $_SESSION['login']['permgroup'][$name];
        }
    }
    public static function Redirect() {

    }
    public static function RefreshPerms() {
        if(isset($_SESSION['login']['permgroup'])) {
            \plugin::load('models');
            $models = new Models\MainModel();
            $models->CallModel('users');
            $userPerm = $models->Select([
                'columns' => 'permgroup',
                'values' => [
                    'normal' => [
                        'uuid' => $_SESSION['login']['uuid']
                    ]
                ]
            ]);
            if(count($userPerm) < 1) {
                return false;
            }
            $permGroup = $userPerm[0]['permgroup'] === $_SESSION['login']['permgroup']['perm_name'] ? $_SESSION['login']['permgroup']['perm_name'] : $userPerm[0]['permgroup'];
            $models->CallModel('perms');
            $perm = $models->Select([
                'values' => [
                    'normal' => [
                        'perm_name' => $permGroup
                    ]
                ]
            ]);
            $_SESSION['login']['permgroup'] = $perm[0];
            return true;
        }
    }
}

?>