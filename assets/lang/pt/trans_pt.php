<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 21/03/2018
 * Time: 13:45
 */

$appText = [



    'MICRO1' => "Home",
    'MICRO2' => "About",
    'MICRO3' => "Demos",

    'DEMO1' => "Controllers",
    'DEMO2' => "Models",
    'DEMO3' => "Views",
    'DEMO4' => "Routing",
    'DEMO5' => "Helpers",

    'MICRO_PHP' => 'MicroPHP',
    'DROPDOWN' => 'Documents',

    'MICRO_PHP_1' => 'Bootstrap',
    'MICRO_PHP_2' => 'DataBase Settings',
    'MICRO_PHP_3' => 'Navigation',
    'MICRO_PHP_4' => 'Webpack Integration',
    'MICRO_PHP_5' => 'Response Redirect',
    'MICRO_PHP_6' => 'Validations',
    'MICRO_PHP_7' => 'Twig Extensions',


    'ABOUT_P1' => 'Espero que você goste de usar este framework tanto quanto eu gostei de construí-lo. Todas as classes, com exceção da classe render, que estende o Twig, e os conceitos gerais são de CoolDeavil. Tudo foi feito com muito ❤️ e disponível em um repositório público do GitHub',
    'ABOUT_P2' => "Simplicidade e facilidade de uso são as principais ideias sublinhadas neste framework. Começando com uma dependência mínima de pacotes e a implementação de padrões PHP-FIG.</p><p> A solicitação é correspondida pelo roteador e processada no pipeline e depois retornada como um objeto de resposta. Essas meia dúzia de linhas que compõem o index.php mostram essa ideia.",
    'ABOUT_P3' => "O código é baseado no padrão MVC do Model View Controller e na arquitetura antiga, mas eficaz. Todos os controladores implementam os métodos Psr\Http\Message\MessageInterface. Permitindo o uso no pipeline de qualquer outro middleware que implemente este padrão.",

    'PRESENTATION' => 'Simplicidade e facilidade de uso são as principais ideias sublinhadas neste framework. Começando com uma dependência mínima de apenas pacotes de árvore, guzzlehttp/psr7 psr/http-message http-interop/response-sender
O código é baseado no padrão MVC do Model View Controller e na arquitetura antiga, mas eficaz. Todos os controladores implementam os métodos Psr\Http\Message\MessageInterface. Permitindo o uso no pipeline de qualquer outro middleware que implemente este padrão
Todas as classes principais são baseadas em Interfaces, como RenderInterface ou RouterInterface, tornando muito fácil mudar a lógica por trás, como por exemplo, substituir a classe de roteador personalizada que acompanha o framework por outra como, por exemplo, coffeecode/router ou a injeção de dependência por usando um Container (*) mais avançado como PHP-DI 6 ou até mesmo um componente Symphony como o DependencyInjection. Para o sistema de renderização, altere-o, caso encontre um melhor que o Twig, o renderizador que escolhi para ser usado pelo microUI, e agora o modelo oficial de renderização do Symphony.
A simplicidade do arquivo index.php reflete toda a ideia por trás do framework.
(*) A classe DIContainer é na verdade um array de key value simples com alguns métodos para obter e definir os valores, chamei de box, microUI está em uma caixa! O conceito principal do framework é a injeção de dependência através do container de onde o despachante irá requisitar todos os controllers combinados pelo roteador.',





    'LOG_IN' => "LogIn",
    'REGISTER' => "Registo",
    'DASHBOARD' => "Perfil",
    'LOG_OUT' => "Sair",


    'TEST' => 'Experimental',

    ## Dates
    'YEARS' => 'Anos',
    'MONTHS'=> 'Meses',
    'DAYS'=> 'Dias',
    'HOURS'=> 'Horas',
    'MINUTES'=> 'Minutos',
    'SECONDS'=> 'Segundos',

    'YEAR' => 'Ano',
    'MONTH'=> 'Mês',
    'DAY'=> 'Dia',
    'HOUR'=> 'Hora',
    'MINUTE'=> 'Minuto',
    'SECOND'=> 'Segundo',

    'BEFORE'=> 'atras',
    'STARTED'=> 'Iniciou',
    'ENDED'=> 'Terminou',



    /* ERRORS */
    'CANT_BE_EMPTY'=>'min de 6 caracteres são necessários!',
    'MIN_LENGTH'=>'minimo de 6 caracteres são necessários!',
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
