<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 05/02/2019
 * Time: 04:56
 */

namespace API\Interfaces;

interface RenderInterface
{
    public function addPath(string $path) : void;
    public function addGlobal(string $key, $value) : void;
    public function render(string $view, array $params = []);
    public function template($templateName, $params = []);
}
