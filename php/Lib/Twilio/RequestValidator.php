<?php

class Services_Twilio_RequestValidator
{

    protected $AuthToken;

    function __construct($token)
    {
        $this->AuthToken = $token;
    }
    
    public function computeSignature($url, $data = array())
    {
        // sort the array by keys
        ksort($data);

        // append them to the data string in order
        // with no delimiters
        foreach($data as $key => $value)
            $url .= "$key$value";
            
        // This function calculates the HMAC hash of the data with the key
        // passed in
        // Note: hash_hmac requires PHP 5 >= 5.1.2 or PECL hash:1.1-1.5
        // Or http://pear.php.net/package/Crypt_HMAC/
        return base64_encode(hash_hmac("sha1", $url, $this->AuthToken, true));
    }

    public function validate($expectedSignature, $url, $data = array())
    {
        return self::compare(
            $this->computeSignature($url, $data),
            $expectedSignature
        );
    }

    /**
     * Time insensitive compare, function's runtime is governed by the length
     * of the first argument, not the difference between the arguments.
     * @param $a string First part of the comparison pair
     * @param $b string Second part of the comparison pair
     * @return bool True if $a == $b, false otherwise.
     */
    public static function compare($a, $b) {
        $result = true;

        if (strlen($a) != strlen($b)) {
            return false;
        }

        if (!$a && !$b) {
            return true;
        }

        $limit = strlen($a);

        for ($i = 0; $i < $limit; ++$i) {
            if ($a[$i] != $b[$i]) {
                $result = false;
            }
        }

        return $result;
    }

}
