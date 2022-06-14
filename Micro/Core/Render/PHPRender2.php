<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 05/02/2019
 * Time: 04:57
 */

namespace Micro\Core\Render;



use API\Interfaces\RenderInterface;

class PHPRender2 implements RenderInterface
{
    private $globals = [];
    private $path;
    public $styleSheets = [];
    public $scripts = [];


    public function addStyle(string $style)
    {
        $this->styleSheets[] = $style;
        return $this;
    }
    public function addSScript(string $script)
    {
        $this->scripts[] = $script;
        return $this;
    }
    public function addPath(string $path) : void
    {
        $this->path = $path;
    }

    /**
     * @param string $view
     * @param array $params
     * @return false|string
     */
    public function render(string $view, array $params = [])
    {
        $ext = ".php";
        ob_start();
        $renderer = $this;
        extract($params);
        extract($this->globals);
        require(APP_VIEWS.$view.$ext);
        return ob_get_clean();
    }
    /**
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value) : void
    {
        $this->globals[$key] = $value;
    }

    public function template($templateName, $params = [])
    {
        // TODO: Implement template() method.
    }
}
