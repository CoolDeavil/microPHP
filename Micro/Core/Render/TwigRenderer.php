<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 05/02/2019
 * Time: 14:14
 */

namespace API\Core\Render;

use API\Core\Utils\NavBuilder\NavBuilder;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Core\Session\Session;
use IntlDateFormatter;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RenderInterface
{

    private string $path = '';
    private FilesystemLoader $loader;
    private Environment $twig;
    private ContainerInterface $ioc;

    public function __construct(FilesystemLoader $loader, Environment $twig, ContainerInterface $ioc)
    {
        $this->path = APP_VIEWS;
        $this->loader = $loader;
        $this->twig = $twig;
        $this->setGlobals();
        $this->ioc = $ioc;
    }
    public function addGlobal( $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
    private function setGlobals(){
        $this->addGlobal('NAVBAR_PARTIALS', 'partials'.DIRECTORY_SEPARATOR.BASE_CSS_TEMPLATES);
        $this->addGlobal('cur_page',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
    public function addPath($path): void
    {
    }
    public function render($view, array $params = []): string
    {
        setlocale(LC_ALL, Session::get('LOCALE'));
        $params['app_tittle']= APP_TITTLE;
        $params['now']= utf8_encode('Working Locally');
//        $params['now']= utf8_encode($this->footDate(Session::get('ACTIVE_LANG')));
        $params['APP_MODE']=BUILD_RELEASE?'production':'development';
        $template=null;

        if(RENDER_NAV){
            /**@var NavBuilder $nav */
            $nav = $this->ioc->get(NavBuilder::class);
            $params['APP_NAV']= $nav->render(0);
            if(RENDER_SIDE_NAV){
                $params['APP_SIDE_NAV']= $nav->render(1);
            }
        } else {
            $params['APP_NAV'] = "<div class='appNavigation'></div>";
        }
        try {
            $template = $this->twig->load((string)$view . '.twig');
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
        }
        return $template->render($params);
    }
    public function template($templateName, $params = []): string
    {
        $template=null;
        try {
            $template = $this->twig->load('partials/' . $templateName . '.twig');
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
        }
        return $template->render($params);
    }
    public function renderField($templateName, array $params = []): string
    {
        $template=null;
        try {
            $template = $this->twig->load((string) 'Auth/fields/'.$templateName . '.twig');
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
        }
        return $template->render($params);

    }
    function footDate(string $locale): string
    {
        return datefmt_create(
            $locale,
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN
        )->format(time());
    }

}
