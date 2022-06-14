<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 6/7/2017
 * Time: 3:45 PM
 */

namespace API\Core\Utils;

use API\Core\Session\Session;

class Translate
{
    private static Translate $instance;
    private static string $activeLang;
    private static array $translateKeys;

    private function __construct()
    {
    }

    public static function getInstance() : self
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
            self::$activeLang = Session::get('ACTIVE_LANG')?Session::get('ACTIVE_LANG'):APP_LANG;
            self::setLang(self::$activeLang);
        }
        return self::$instance;
    }
    public static function setLang(string $lang)  : void
    {
        self::$activeLang = $lang;
        $languageKeys = APP_ASSET.'lang/'.self::$activeLang.'/trans_'.self::$activeLang.'.php';
        include $languageKeys;
        /** @var $appText array mix */
        self::$translateKeys = $appText;
    }
    public static function translate($key) : string
    {
        if(!self::$translateKeys[$key]){
            die('KEY NOT FOUND: -> '.$key);
        }
        return self::$translateKeys[$key];
    }

}
