<?php

/**@var $nav API\Core\Utils\NavBuilder\NavBuilder */


$user_avatar = '';
$userID  = 0;
//use API\Core\Session\Session;
//if( Session::loggedUserID()){
//    $userID  = (int)Session::loggedUserID();
//    $user_avatar = Session::loggedUserAvatar();
//}


$nav->link('MICRO1', 'Micro.index', 'fa-cannabis');

$nav->drop('MICRO_PHP')
    ->entry('MICRO_PHP_1', 'Micro.bootstrap','fa-cogs')
    ->entry('MICRO_PHP_2', '','fa-star')
    ->entry('MICRO_PHP_3', 'Micro.navigation','fa-cogs')
    ->entry('MICRO_PHP_4', '','fa-cogs')
    ->entry('MICRO_PHP_5', 'Check.rawValidation','fa-cogs')
    ->entry('MICRO_PHP_6', 'taskService.index','fa-cogs');

$nav->drop('DROPDOWN')
    ->entry('DEMO1', 'Micro.showWebPage','fa-cogs',['demo'=>'Controllers'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-database',['demo'=>'Models'])
    ->entry('DEMO3', 'Micro.showWebPage','fa-desktop',['demo'=>'Views'])
    ->entry('DEMO4', 'Micro.showWebPage','fa-hospital',['demo'=>'Helpers']);

$nav->admin()
    ->entry('REGISTER', 'Micro.showWebPage', 'fa-user-plus', [],'GUEST')
    ->entry('LOG_IN', 'Micro.showWebPage', 'fa-sign-in-alt', [],"GUEST")
    ->avatar($user_avatar, 'authUserService.show', ['id'=>$userID],"USER")
    ->entry('LOG_OUT', 'authUserService.clearSession', 'fa-sign-out-alt', [],"USER");


