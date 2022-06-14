<?php
namespace API\Validation;

use API\Core\Utils\Validation;

class MinLength extends Validation
{
    public function __invoke($value, $limit): ?string
    {
        if ($value ===null || strlen($value)<$limit) {
            return  str_replace('#', $limit,$this->lang->translate('MIN_LENGTH'));
        }
        return null;
    }
}
