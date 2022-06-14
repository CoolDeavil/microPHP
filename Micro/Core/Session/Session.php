<?php
namespace API\Core\Session;

use API\Interfaces\SessionInterface;
use API\Core\Utils\Logger;
use API\Models\AppUser;

class Session implements SessionInterface
{
    private static Session $instance;
    private function __construct()
    {
        session_cache_expire(1800);
        @session_start();
        self::ensureStarted();
    }
    public static function getInstance() : Session
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    private static function ensureStarted()
    {
        $acceptedLanguages = [
            'pt',
            'en',
            'fr',
        ];
        if (!self::get('SID')) {
            self::set('SID', session_id());
            self::set('STARTED', session_id());
            $active_browser_locale  = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?  strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',') : '';
            $active_browser_language = substr($active_browser_locale, 0, 2);
            if (!in_array($active_browser_language, $acceptedLanguages)) {
                $active_browser_language = APP_LANG;
            };
            self::set('ACTIVE_LANG', $active_browser_language);
            self::set('LOCALE', $active_browser_locale);

            self::set('ANONYMOUS', true );



            Logger::log(sprintf('[@] SESSION STARTED: Lang [ %s ] at [ %s ]', self::get('ACTIVE_LANG'), time()));
        }
//        Logger::log('SESSION RUNNING..... ' );
    }







    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false;
    }
    public static function unsetKey($key): bool
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        return false;
    }
    public static function destroy()
    {
        Logger::log('End of Session');
        $_SESSION = [];
        session_destroy();
        unset($_SESSION);
    }


    // Author Related
    public static function updateLogged(AppUser $user)
    {
        self::set('loggedUser', serialize($user));
    }

    public static function loggedUser(): bool|AppUser
    {
        if (self::get('loggedIn')) {
            /**@var $user AppUser */
            $user = unserialize((string) self::get('loggedUser'));
            return ($user);
        }
        return false;
    }

    public static function loggedUserID(): int
    {
        if (self::get('loggedIn')) {
            /**@var $user AppUser */
            $user = unserialize(self::get('loggedUser'));
            return $user->getId();
        }
        return false;

    }
    public static function loggedUserAvatar(): bool|string
    {
        if (self::get('loggedIn')) {
            /**@var $user AppUser */
            $user = unserialize(self::get('loggedUser'));
            return $user->getAvatar();
        }
        return false;
    }

    public static function authorize(AppUser $user)
    {
        session_regenerate_id();
        self::set('SID', session_id());
        self::set('loggedIn', true);
        self::set('loggedUser', serialize($user));
        if (Session::get('ACTIVE_LANG')!=$user->getLanguageActive()) {
            Session::set('ACTIVE_LANG', $user->getLanguageActive());
        }
    }
}
