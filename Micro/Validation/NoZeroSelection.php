<?php


namespace API\Validation;


use API\Core\Utils\Validation;

class NoZeroSelection extends Validation
{
    public function __invoke($value)
    {
        if((int)$value===0){
            return $this->lang->translate('NO_ZERO_SELECTION');
        }
        return null;
    }

}
