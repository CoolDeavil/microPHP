<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 21/03/2018
 * Time: 13:45
 */
$appText = [



    'MICRO1' => "Domicile",
    'MICRO2' => "Sur",
    'MICRO3' => "Demos",

    'DEMO1' => "Demo One",
    'DEMO2' => "Demo Two",
    'DEMO3' => "Demo Tree",
    'DROPDOWN' => 'Demos',

    'TEST' => 'Trial',



    'BANNER_TITTLE' => 'Un titre aléatoire',
    'PRESENTATION_TITTLE' => 'Les concepts derrière...',
    'PRESENTATION_SUBTITLE' => 'Quelques idées de base sur le Framework',

    'PRESENTATION' => "La simplicité et la facilité d'utilisation sont les principales idées soulignées sur ce framework. En commençant par une dépendance minimale des packages arborescents, guzzlehttp/psr7 psr/http-message http-interop/response-sender
Le code est basé sur le modèle MVC de Model View Controller et sur une architecture ancienne mais efficace. Tous les contrôleurs implémentent les méthodes Psr\Http\Message\MessageInterface. Autoriser l'utilisation dans le pipeline de tout autre middleware implémentant cette norme
Toutes les classes principales sont basées sur des interfaces, comme RenderInterface ou RouterInterface, ce qui permet de changer très facilement la logique derrière, comme par exemple remplacer la classe de routeur personnalisée qui est livrée avec le framework par une autre comme par exemple coffeecode/router ou l'injection de dépendance par en utilisant un Container (*) plus avancé comme PHP-DI 6 ou même un composant Symphony comme DependencyInjection. Pour le système de rendu, changez-le, si vous pouvez en trouver un meilleur que Twig, le moteur de rendu que j'ai choisi pour être utilisé par microUI, et maintenant le modèle de rendu officiel pour Symphony.
La simplicité du fichier index.php reflète toute l'idée derrière le framework.
(*) La classe DIContainer est en fait un simple tableau de valeurs de clés avec quelques méthodes pour obtenir et définir les valeurs, j'ai appelé une boîte, microUI est sur une boîte ! Le concept principal du framework est l'injection de dépendances à travers le conteneur à partir duquel le répartiteur demandera tous les contrôleurs correspondants au routeur.",

    'LOG_IN' => "LogIn",
    'REGISTER' => "Register",
    'DASHBOARD' => "Profile",
    'LOG_OUT' => "LogOut",

    ## Dates
    'YEAR' => 'Année',
    'MONTH'=> 'Mois',
    'DAY'=> 'journée',
    'HOUR'=> 'Heure',
    'MINUTE'=> 'Minute',
    'SECOND'=> 'Seconde',


    ## Dates
    'YEARS' => 'Années',
    'MONTHS'=> 'Mois',
    'DAYS'=> 'Journées',
    'HOURS'=> 'Heures',
    'MINUTES'=> 'Minutes',
    'SECONDS'=> 'Secondes',


    'BEFORE'=> 'Before',
    'STARTED'=> 'Started',
    'ENDED'=> 'Ended',


    /* ERRORS */
    'CANT_BE_EMPTY'=>'min of 6 chars are required!',
    'MIN_LENGTH'=>'min of 6 chars are required!',
    'NAME_REQUIRED'=>'min of 6 chars are required!',
    'NAME_MIN_LENGTH'=>'Default errorTranslation [update keys]',
    'NAME_VALIDATED'=>'Default errorTranslation [update keys]',

    'EMAIL_REQUIRED'=>'The email field is required',
    'EMAIL_EXISTS'=>'These email is already registered. forgot your password?',
    'EMAIL_NOT_EXISTS'=>"These email is not recorded on the database, have you registered?",
    'EMAIL_BAD_STRUCTURE'=>'Email malformed',

    'NO_ZERO_SELECTION'=>'Must Select one Skill',

    'PASSWORD_REQUIRED'=>'Default errorTranslation [update keys]',
    'PASSWORD_SECURE'=>'Default errorTranslation [update keys]',
    'PASSWORD_MIN_LENGTH'=>'Default errorTranslation [update keys]',

    'PASSWORD_CONFIRM_REQUIRED'=>'Default errorTranslation [update keys]',
    'PASSWORD_CONFIRM_SECURE'=>'Default errorTranslation [update keys]',
    'PASSWORD_CONFIRM_MIN_LENGTH'=>'Default errorTranslation [update keys]',

    'CAPTCHA_REQUIRED'=>'Security check field is required',
    'CAPTCHA_EXCEPTION'=>'Security check failed, phrase  did not match',
    'CAPTCHA_MIN_LENGTH'=>'Default errorTranslation [update keys]',

    'ABOUT_REQUIRED'=>'You sentence must have min of 10char ',
    'ABOUT_EXCEPTION'=>'Security check failed, phrase  did not match',
    'ABOUT_MIN_LENGTH'=>'Default errorTranslation [update keys]',

    "INSECURE_PASS" => "Min 8 char, min 1 lower case, min 1 upper case and a digit or special char",
    "NOT_CONFIRMED" => "Must agree with the terms to proceed",

];
