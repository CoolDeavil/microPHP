<?php
namespace API\Validation;

use API\Core\Utils\Translate;
use API\Core\Utils\Validation;

class Range extends Validation
{
    protected Translate $lang;

    public function __invoke(string $val, array $params = null): ?string
    {
        if ((strlen($val) < (int)$params['min']) || ( strlen($val) > (int)$params['max'])) {
            return $this->lang->translate("RANGE_REQUIRED") . " Min: $params[min] Max: $params[max]";
        }
        return null;
    }
}
