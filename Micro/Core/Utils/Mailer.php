<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 5/3/2017
 * Time: 3:48 PM
 */

namespace API\Core\Utils;

class Mailer
{
    public static function sendMultipartMail($mailData)
    {
        $textMessage = <<<EOT
Hello ,$mailData->userName.

Looks like you lost your Website password. 
No worry, it very simple to reset it.
Just copy the code and follow the link below. 

Code: : $mailData->userSecret
$mailData->urlToken
EOT;
        $src = APP_ASSET_BASE.'images/microPHPLogo.png';

        $htmlMessage = <<<EOT
<div>
<img Micro="$src" alt="MicroPHP" style="height: 50px;width: auto">
</div>    
<h4>Hello ,$mailData->userName.</h4>
<p>Looks like you lost your Website password.
<br/>No worry, it very simple to reset it.
<br/>Just copy the code and follow the link below.</p> 
<span>Code:</span> : <strong>$mailData->userSecret</strong><br>
<a href="$mailData->urlToken">Reset your Password</a>
EOT;

        $boundary = sha1(uniqid('np'));

        $headers = "MIME-Version: 1.0".PHP_EOL;
        $headers .= "From: MicroPHP <webmaster@microUI.com>".PHP_EOL;
        $headers .= "Subject: ".$mailData->subject.PHP_EOL;
        $headers .= "Content-Type: multipart/alternative;boundary=".$boundary.PHP_EOL;

        $message = PHP_EOL.PHP_EOL."--" . $boundary . PHP_EOL;
        $message .= "Content-type: text/plain;charset=utf-8".PHP_EOL.PHP_EOL;
        $message .= $textMessage;

        $message .= PHP_EOL.PHP_EOL."--" . $boundary . PHP_EOL;
        $message .= "Content-type: text/html;charset=utf-8".PHP_EOL.PHP_EOL;
        $message .= $htmlMessage;

        $message .= PHP_EOL.PHP_EOL."--" . $boundary . "--";

        mail($mailData->to, 'Multipart Mail', $message, $headers);
    }
}
