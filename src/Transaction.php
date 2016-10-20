<?php

namespace Glow\Kopokopo;

class Transaction{
    /**
     * Transaction constructor.
     *
     * @param string $secret The kopokopo api key
     */
    function __construct($secret){
        $this->secret = $secret;
    }

    /**
     * Verify that the transaction coming in from kopokopo is valid
     * 
     * @param array $payload An array formed out of the kopokopo payload.
     *
     * @return boolean
     */
    public function verify(array $payload){
        $signature = $payload['signature'];

        unset($payload['signature']);

        ksort($payload);

        $msg = implode("&", $payload);

        $tx_array = [];

        foreach($payload as $key=>$value){
            array_push($tx_array,$key.'='.$value);
        }

        $encodable_str = implode('&',$tx_array);


        $hash = hash_hmac('sha1', $encodable_str, $this->secret, true);

        $hash_64 = base64_encode($hash);
        
        if($signature == $hash_64){
            return true;
        }

        return false;
    }
}