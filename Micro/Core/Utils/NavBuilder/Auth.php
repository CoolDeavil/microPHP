<?php

namespace API\Core\Utils\NavBuilder;

class Auth {

//    public string $label ;
//    public string $icon;
//    public $translate;
//    public $params = [];
    public array $links = [];


    public function __construct( )
    {
    }
    public function entry($label,$url,$icon, $params = null, $usage= null) : Auth
    {
        $link = new Link($label, $url, $icon,  $params, $usage);
        if(isset($params)){
            foreach ($params as $k=>$v ){
                $link->param($k,$v);
            }
        }
        $this->links[] = $link;
        return $this;
    }

    public function avatar( $avatar, $url, $params = null, $usage= null) : Auth
    {
        $link = new ALink($avatar,$url,$params,$usage);
        if(isset($params)){
            foreach ($params as $k=>$v ){
                $link->param($k,$v);
            }
        }

        $this->links[] = $link;
        return $this;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

}
