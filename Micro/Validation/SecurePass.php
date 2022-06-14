<?php
namespace API\Validation;

use API\Core\Utils\Validation;

class SecurePass extends Validation
{
    public function __invoke($pass): ?string
    {
        if (is_null($pass) || $pass=='') {
            return $this->lang->translate('CANT_BE_EMPTY');
        }
        if (preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $pass)) {
            return null;
        } else {
            return  $this->lang->translate("INSECURE_PASS");
        }
    }
}
