<?php
namespace API\Validation;

use API\Core\Utils\Validation;

class ValidEMail extends Validation
{
    public function __invoke($email): ?string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->lang->translate('EMAIL_BAD_STRUCTURE');
        }
        return null;
    }
}
