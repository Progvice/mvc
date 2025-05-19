<?php

class Permissions {
    public function __construct() {
        Plugin::load('activerecord');
    }
    public function checkPermission() {
        if (isset($_SESSION['id'])) {
            $user = user::find_by_pk($_SESSION['id']);
            return $user->perm;
        }
        return false;
    }
    
    public function setPermission($uid, $perm) {
        if ($this->checkPermission() === 99) {
            $user = user::find_by_pk($uid);
            $user->perm = $perm;
            $user->save();
        }
        else {
            header('Location: /404');
        }
    }
}

?>