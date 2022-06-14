<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 05/02/2019
 * Time: 14:46
 */

namespace API\Core\Render;

use API\Core\Utils\ScriptLoader;
use GuzzleHttp\Psr7\Response;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Core\Router\MRouter;
use API\Core\Session\Session;
use API\Core\Utils\Translate;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigRendererExtensions extends AbstractExtension
{

    private $ioc;

    public function __construct(ContainerInterface $ioc)
    {
        $this->ioc = $ioc;
    }

    public function getFunctions(): array
    {
        return [
            new  TwigFunction('url', [$this, 'url']),
            new  TwigFunction('asset', [$this, 'asset']),
            new  TwigFunction('link', [$this, 'link']),
            new  TwigFunction('cached', [$this, 'cached']),
            new  TwigFunction('thumbnail', [$this, 'thumbnail']),
            new  TwigFunction('decode', [$this, 'decode']),
            new  TwigFunction('trans', [$this, 'translate']),
            new  TwigFunction('download', [$this, 'download']),
            new  TwigFunction('field', [$this, 'field']),
            new  TwigFunction('decode', [$this, 'decode']),
            new  TwigFunction('script', [$this, 'script']),
            new  TwigFunction('style', [$this, 'style']),
            new  TwigFunction('prodScript', [$this, 'prodScript']),
        ];
    }
    public function script($scriptName): string
    {
        /**@var $loader ScriptLoader */
        $srcLoader = ScriptLoader::getInstance();
        return $srcLoader::loadScriptTag(SCRIPT,$scriptName);

    }
    public function style($styleName): string
    {
        /**@var $loader ScriptLoader */
        $stLoader = ScriptLoader::getInstance();
        return $stLoader::loadScriptTag(STYLE,$styleName);
    }
    public function prodScript(int $type): string
    {
        /**@var $loader ScriptLoader */
        $srcLoader = ScriptLoader::getInstance();
        return $srcLoader::loadProductionScripts($type);

    }


    public function url(string $name, array $params = []): string
    {
        /**@var $router MRouter */
        $router = $this->ioc->get(RouterInterface::class);
        return $router->generateURI($name, $params);
    }
    public function asset($asset = null, $target = null): string
    {
        if ($asset) {
            $asset = trim($asset, '/');
        }
        if ($target) {
            $asset = $asset . "/" . trim($target, '/');
        }

        if (BUILD_RELEASE) {
            return APP_ASSET_BASE . $asset;
        } else {
            return DEV_SERVER_URL . $asset;
        }
    }
    public function link($asset = null, $target = null): string
    {
        if ($asset) {
            $asset = trim($asset, '/');
        }
        if ($target) {
            $asset = $asset . "/" . trim($target, '/');
        }
        return APP_ASSET_BASE . $asset;
    }
    public function download($image): string
    {
        /**@var $router MRouter */
        $router = $this->ioc->get(RouterInterface::class);
        $url = $router->generateURI('uploader.download', []);
        return APP_ASSET_BASE . $url . '?file=' . $image;
    }
    public function thumbnail($image): string
    {
        return APP_ASSET_BASE . 'galleryAsset?file=' . $image;
    }
    public function translate($key): ?string
    {
        if ($key === null || $key === '') {
            return null;
        }
        /**@var $tr Translate */
        $tr = $this->ioc->get(Translate::class);
        return $tr->translate($key);
    }


    public function field($field)
    {
        /**@var $render TwigRenderer */
        $render = $this->ioc->get(RenderInterface::class);

        $template='';
        switch ($field['type']) {
            case "text":
            case "email":
            case "password":
                $template = $render->renderField('input',
                    [
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'class' => $field['class'],
                        'name' => $field['name'],
                        'placeholder' => $field['placeholder'],
                    ]);
                break;
            case "separator":
                $template="<hr>";
                break;
            case "checkbox":
                $template = $render->renderField('checkbox',
                    [
                        'labelClass' => $field['labelClass'],
                        'fieldType' => $field['type'],
                        'fieldClass' => $field['class'],
                        'fieldName' => $field['name'],
                        'fieldLabel' => $field['label'],
                        'fieldAction' => $field['onClick'],
                    ]);
                break;
            case "captcha":
                $template = $render->renderField('captcha',
                    [
                        'captcha' => $field['image'],
                        'placeholder' => $field['placeholder'],
                        'onClick' => $field['onClick'],
                    ]
                );
                break;
            case "select":
                $template = $render->renderField('select',
                    [
                        'fieldLabel' => $field['label'],
                        'selectItems' => $field['selectItems'],
                    ]
                );
                break;
            case "warning":

                $template = $render->renderField('warning');
                break;
        }
        return ($template);
    }
    public function decode($json)
    {
        if (gettype($json) === 'string') {
            $foo = json_decode($json);
            if($foo){
                return json_decode($json);
            }else{
                return $json;
            }
        }
        return '';
    }
    public function b64($file_): string
    {
        $base = '/var/www/html/application/storage/cashed_files/';
        $base = $base . $file_;
        $mime_type = mime_content_type($base);

        $source_image = imagecreatefromjpeg($base);
        $source_imageX = imagesx($source_image);
        $source_imageY = imagesy($source_image);

        $resize = $this->imageResize($source_imageX, $source_imageY, 150);

        $dest_imageX = $resize['x'];
        $dest_imageY = $resize['y'];
        $dest_image = imagecreatetruecolor($dest_imageX, $dest_imageY);
        imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $dest_imageX, $dest_imageY, $source_imageX, $source_imageY);

        ob_start();
        imagepng($dest_image);
        $bin = ob_get_clean();

        return 'data:image/' . $mime_type . ';base64,' . base64_encode($bin);
    }
    public function imageResize($width, $height, $target)
    {
        $percentage = null;
        if ($width > $height) {
            $percentage = ($target / $width);
        } else {
            $percentage = ($target / $height);
        }
        $width = round(($width) * $percentage);
        $height = round(($height) * $percentage);
        return [
            'x' => $width,
            'y' => $height,
        ];
    }

}
