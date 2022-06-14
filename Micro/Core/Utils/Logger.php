<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 4/25/2017
 * Time: 3:11 PM
 */

namespace API\Core\Utils;

class Logger
{
    public static function log($toLog)
    {

        try {
            if (!file_exists(APP_ROOT."logs")) {
                mkdir(APP_ROOT."logs", 0777, true);
            }
            file_put_contents(APP_ROOT."logs/microLog.log", $toLog."\n", FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            echo "MicroLog encountered an error: " . $e;
            die("MicroLog encountered an error: " . $e);
        }
    }
}
