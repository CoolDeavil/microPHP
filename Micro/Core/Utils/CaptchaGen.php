<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 5/5/2017
 * Time: 3:09 PM
 */

namespace API\Core\Utils;

use API\Core\Utils\Logger;
use Imagick;
use ImagickDraw;
use ImagickException;
use ImagickPixel;
use API\Core\Session\Session;

class CaptchaGen
{
    /**
     */

    public static function generate(): object
    {
        if( class_exists("Imagick") )
        {
           return self::imagickCaptcha();
        } else {
            return self::gdCaptcha();
        }
    }

    public static function gdCaptcha(): object
    {
        $captcha_text = self::generateCaptchaText();

        $im = imagecreatetruecolor(400, 100);
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 399, 199, $white);

//        $line_color = imagecolorallocate($im, 230,108,13);


//        245,255,251
        $line_color = imagecolorallocate($im, 220,20,60);
        for($i=0;$i<10;$i++) {
            imageline($im,0,rand()%100,400,rand()%100,$line_color);
        }
        $font = CAPTCHA_FONT;
        imagettftext($im, 60, -3, 0, 80, $black, $font, $captcha_text);
        ob_start();
        imagepng($im);
        $buffer = ob_get_clean();
        ob_end_clean();
        $captcha = base64_encode($buffer);
        imagedestroy($im);
        $captcha = (object)[
            'image' => 'data:image/jpg;base64,' . $captcha,
            'text' => $captcha_text
        ];
        Session::set('captcha', serialize($captcha));
        Logger::log('Captcha:  ' . $captcha->text);
        return $captcha;


    }


    public static function imagickCaptcha(): object
    {
//        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
//        $captcha_text = substr(str_shuffle($letters), 0, CAPTCHA_LENGTH);
        $captcha_text = self::generateCaptchaText();

        $draw = new ImagickDraw();
        $draw->setFont(CAPTCHA_FONT);
        $draw->setFontSize(50);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFillColor(new ImagickPixel('#006d6d'));
        $draw->setFillColor(new ImagickPixel('#000000'));
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        $draw->annotation(150, 60, $captcha_text);

        $draw->setStrokeColor(new ImagickPixel("#ff1116;"));
        $draw->setStrokeWidth(2);
        $draw->line(40, 35, 250, 30);
//        $draw->line(40, 65, 250, 60);

        $imagick = new Imagick();
        $imagick->newImage(300, 150, "transparent");
        $imagick->setImageFormat("png");
        $imagick->drawImage($draw);
        $distort = [180];
        $imagick->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $imagick->setImageMatte(true);
        $imagick->distortImage(Imagick::DISTORTION_ARC, $distort, true);
//        $imagick->writeImage("microCaptcha.png");

        $captcha = (object)[
            'image' => 'data:image/jpg;base64,' . base64_encode($imagick->getImageBlob()),
            'text' => $captcha_text
        ];
        Session::set('captcha', serialize($captcha));
        Logger::log('Captcha:  ' . $captcha->text);
        return $captcha;
    }

    private static function generateCaptchaText(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($letters), 0, CAPTCHA_LENGTH);

    }
}
