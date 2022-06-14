<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 18/02/2019
 * Time: 20:58
 */

namespace API\Validation;

use API\Core\Utils\Validation;

class Checked extends Validation
{
    public function __invoke($value, $compare)
    {
        if ($value !== $compare) {
            return $this->lang->translate('NOT_CONFIRMED');
        }
        return null;
    }
}
