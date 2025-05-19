<?php
/*
 *  @credits    Thank you Scott Arciszewski for providing
 *              secure way to create random strings.
 *
 *              https://stackoverflow.com/a/31107425/4913562
 *
 *  @class      Csrf
 *
 *  @example
 *      Core\App\Csrf::Generate();
 *
 *
 */
namespace Core\App;
class Csrf {
    public function Generate() {
        if (isset($_COOKIE['csrf-token'])) {
            return;
        }
        $length = 32;
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        setcookie('csrf-token', implode('', $pieces), time()+3600);
    }
    public function GenerateInput() {
        if (isset($_COOKIE['csrf-token'])) {
            return '<input type="hidden" name="csrf-token" value="' . $_COOKIE['csrf-token'] . '"/>';
        }
    }
    public function Verify() {
        if (isset($_POST['csrf-token'])) {
            if ($_COOKIE['csrf-token'] !== $_POST['csrf-token']) {
                echo 'Invalid CSRF token. Data was not sent.';
                exit;
            }
        }
    }
}
?>