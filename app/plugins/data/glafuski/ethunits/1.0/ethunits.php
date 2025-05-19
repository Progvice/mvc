<?php
/****
    *
    *   Class: WeiTo
    *       Function: Eth
    *           @param $number string | int | float - Input Wei and convert it to Ether
    *           
    *           @desc   This function is meant to change Wei to Ether. Why this was implemented is
    *                   because Web3JS for PHP is not able to convert hexadecimals correctly to     *                   float value
    *                   if Wei amount length is less than 19. At the same time this function does
    *                   make it possible to transfer bigger amounts of Wei to Ether aswell because
    *                   we don't want to rely on 3rd party library for small action like this.
    *                   
    *                   1000000000000000000 Wei = 1 Ether
    *
    *           @example
    *               $ethunits = new Glafuski\WeiTo;
    *               $ethunits->Ether('100000000000000000000');
    *               
    *               OR
    *   
    *               $decimal_balance = Glafuski\WeiTo::Ether('100000000000000000000');
    *
*****/
namespace Glafuski;
class WeiTo {
    public function Ether($number) {
        $calc = 19 - strlen($number);
        if ($calc <= 0) {
            /*
             *
             *  This code sets decimal point to right spot. Code returns at this point
             *  because further actions are not required.
             *
             */
            $decimalpos = strlen($number) - strlen('1000000000000000000') + 1;
            $intnumber = substr($number, 0, $decimalpos);
            $decimalnumber = substr($number, $decimalpos);
            $final_number = $intnumber . '.' . $decimalnumber;
            return $final_number;
        }
        else if ($calc === 1) {
            $zeroes = '0.';
        }
        else if ($calc > 1) {
            $zeroes = '';
            $runonce = false;
            for($counter = 0; $counter < $calc; $counter++) {
                /*
                 *
                 *  This code needs to run only once
                 *
                 */
                if ($runonce === false) {
                    $zeroes .= '0.';
                    $runonce = true;
                }
                else {
                    $zeroes .= '0';   
                }
            }
        }
        $final_number = $zeroes . $number;
        return $final_number;
    }
}
?>