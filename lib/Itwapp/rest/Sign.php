<?php

abstract class Sign {

    /**
     * @param $string String, the string to encode
     * @param $key String your private key
     * @return string the md5 of the signature
     */
    public static function encode($string, $key)  {
        $hmac = base64_encode(hash_hmac('sha256', $string, $key, true));
        return md5($hmac);
    }
} 