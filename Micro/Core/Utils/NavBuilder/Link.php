<?php

namespace API\Core\Utils\NavBuilder;

class Link {

    public string $label ;
    public string $icon;
    public string $url;
    public string|null $usage;
    public array $params = [];
    public function __construct( $label, $url, $icon = '', $params = null,  $usage=null)
    {
        $this->label = $label;
        $this->icon = $icon;
        $this->url = $url;
        $this->usage = $usage;
        $this->params = [];
        if($params){
            $this->params = $params;
        }
    }
    public function getParams(): array
    {
        return $this->params;
    }
    public function param($name,$value) : Link
    {
        $this->params[$name] = $value;
        return $this;
    }
    public function getLabel() : string
    {
        return $this->label;
    }
    public function getIcon() : string
    {
        return $this->icon;
    }
    public function getURL() : string
    {
        return $this->url;
    }
    public function getUsage() : string|null
    {
        return $this->usage;
    }

}
