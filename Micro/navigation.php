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
//$nav->link('MICRO2', 'checkItController.index', 'fa-cogs');

$nav->drop('DROPDOWN')
    ->entry('DEMO2', 'Micro.showWebPage','fa-cogs',['demo'=>'Controllers'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-star',['demo'=>'Models'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-cogs',['demo'=>'Views'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-cogs',['demo'=>'Routing'])
    ->entry('DEMO3', 'Micro.showWebPage','fa-car',['demo'=>'Helpers']);

$nav->drop('DROPDOWN')
    ->entry('DEMO2', 'Micro.showWebPage','fa-cogs',['demo'=>'Controllers'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-star',['demo'=>'Models'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-cogs',['demo'=>'Views'])
    ->entry('DEMO2', 'Micro.showWebPage','fa-cogs',['demo'=>'Routing'])
    ->entry('DEMO3', 'Micro.showWebPage','fa-car',['demo'=>'Helpers']);

$nav->admin()
    ->entry('REGISTER', 'authUserService.create', 'fa-user-plus', [],'GUEST')
    ->entry('LOG_IN', 'authUserService.index', 'fa-sign-in-alt', [],"GUEST")
    ->avatar($user_avatar, 'authUserService.show', ['id'=>$userID],"USER")
    ->entry('LOG_OUT', 'authUserService.clearSession', 'fa-sign-out-alt', [],"USER");
