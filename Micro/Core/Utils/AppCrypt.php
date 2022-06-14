<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 4/25/2017
 * Time: 7:28 PM
 */

namespace API\Core\Utils;

class AppCrypt
{
    private static $instance = null;
    public static $params;

    public function __construct()
    {
        self::$params = (object)[
            'encrypt_method' => "AES-256-CBC",
            'secret_iv' => APP_KEY,
            'secret_key' => 'Some-Secret-String'
        ];
    }

    public static function getInstance(): AppCrypt|null
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    /**
     * @param $string
     * @param null $params
     * @return string
     */
    public static function crypt($string, $params = null): string
    {

        if ($params == null) {
            $params =self::$params ;
        }
        $encrypt_method = $params->encrypt_method;
        $secret_iv = $params->secret_iv;
        $secret_key = $params->secret_key;
        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    /**
     * @param $string
     * @param null $params
     * @return string
     */
    public static function decrypt($string, $params = null): string
    {
        if ($params == null) {
            $params = self::$params;
        }

        $encrypt_method = $params->encrypt_method;
        $secret_iv = $params->secret_iv;
        $secret_key = $params->secret_key;

        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
    /**
     * @param integer $chars
     * @return string
     */
    public static function randomString(int $chars): string
    {
        $letters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $len = strlen($letters);
        $random = '';
        for ($i = 0; $i< $chars; $i++) {
            $letter = $letters[rand(0, $len-1)];
            $random.=$letter;
        }
        return $random;
    }
    public static function generateIdentifier($userName)
    {
        $alg = APP_KEY_ALGORITHM;
        $identifier = $alg(APP_SECRET_KEY . $alg($userName . APP_SECRET_KEY));
        Logger::log('Identifier '. $userName .' -> '.$identifier);
        return $identifier;
    }
    public static function generateToken()
    {
        $alg = APP_KEY_ALGORITHM;
        $token = $alg(uniqid(rand(), true));
        Logger::log('Token '. $token);
        return $token;
    }
    public static function hashFactory($dataToCrypt)
    {
        $context = hash_init(APP_KEY_ALGORITHM, HASH_HMAC, APP_KEY);
        hash_update($context, $dataToCrypt);
        return  hash_final($context);
    }
}
