<?php

namespace Core\App;

class LoginSession {
    public function CheckSession() {
        if (isset($_SESSION['logses'])) {
            return true;
        }
        return false;
    }
}

?>