<?php
namespace API\Core\Utils;

class Validation
{
    /**
     * @var $lang Translate
     */
    protected Translate $lang;
    public function __construct(Translate $translate)
    {
        $this->lang = $translate;
    }
}
