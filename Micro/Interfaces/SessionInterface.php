<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 17/02/2019
 * Time: 18:20
 */

namespace API\Interfaces;

interface SessionInterface
{
//    public static function init();
    public static function set($key, $value);
    public static function get($key);
    public static function unsetKey($key);
    public static function destroy();
}
