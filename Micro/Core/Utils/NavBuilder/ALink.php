<?php


namespace API\Core\Utils\NavBuilder;


class ALink
{
    public $avatar ;
    public $url;
    public $params = [];
    public $usage;

    public function __construct( $avatar, $url, $params = null,  $usage=null){
        $this->avatar = $avatar;
        $this->url = $url;
        if($params){
            $this->params = $params;
        }else {
            $this->params = [];
        }
        $this->usage = $usage;
    }
    public function getParams(): array
    {
        return $this->params;
    }
    public function param($name,$value)
    {
        $this->params[$name] = $value;
        return $this;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }
    public function getURL()
    {
        return $this->url;
    }
    public function getUsage()
    {
        return $this->usage;
    }


}