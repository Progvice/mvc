<?php

namespace Glafuski;

class Cmc {
    private $apikey;
    public function __construct() {
        $key = json_decode(file_get_contents(JSON_PATH . '/cmc.json'));
        $this->apikey = $key->apikey;
    }
/***** 
     *  @name Convert
     *  @desc  
     *      Convert currency to another currency.
     *          
     *  @param array 
     *  [   
     *      $from => 'symbol',
     *      $to => 'symbol',
     *      $amount => 'amount'
     *  ]
     *  
     *  @return string
******/
    public function Convert($array) {
        $url = 'https://pro-api.coinmarketcap.com/v1/tools/price-conversion';
        /*
         *
         *  If amount is not set it will be
         *  set to 1 by default
         *
         */
        if (empty($array['amount'])) {
            $array['amount'] = '1';
        }
        $parameters = [
            'symbol' => $array['from'],
            'amount' => $array['amount'],
            'convert' => $array['to']
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . $this->apikey
        ];
        $qs = http_build_query($parameters);
        $furl = $url . '?' . $qs;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $furl,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => 1
        ]);
        /*
            To is set to seperate variable
            because objects do not like when
            array key is used as object selector
        */
        $to = $array['to'];
        $response = curl_exec($curl);
        $test = json_decode($response);
        return $test->data->quote->$to->price;
    }
}

?>