<?php
namespace API\Validation;

use API\Core\Utils\Validation;

class NotEmpty extends Validation
{
    public function __invoke(string $val): ?string
    {
        if ($val == '' || $val =  null) {
            return $this->lang->translate("CANT_BE_EMPTY");
        }
        return null;
    }
}
