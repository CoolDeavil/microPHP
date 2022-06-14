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
$nav->link('MICRO2', 'Micro.allGadgets', 'fa-cogs');

$nav->drop('DROPDOWN')
    ->entry('DEMO1', 'Micro.showRoutes','fa-star')
    ->entry('DEMO1', 'Micro.autocomplete','fa-cogs')
    ->entry('DEMO2', 'Micro.convertor','fa-star')
    ->entry('DEMO3', 'Micro.countries','fa-car');

$nav->admin()
    ->entry('REGISTER', 'authUserService.create', 'fa-user-plus', [],'GUEST')
    ->entry('LOG_IN', 'authUserService.index', 'fa-sign-in-alt', [],"GUEST")
    ->avatar($user_avatar, 'authUserService.show', ['id'=>$userID],"USER")
    ->entry('LOG_OUT', 'authUserService.clearSession', 'fa-sign-out-alt', [],"USER");
