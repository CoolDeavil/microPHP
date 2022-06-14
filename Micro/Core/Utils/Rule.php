<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 18/02/2019
 * Time: 05:30
 */

namespace API\Core\Utils;

class Rule
{
    public string $validator = '';
    public mixed $values = null;

    public function rule(string $validator): Rule
    {
        $this->validator  = $validator;
        return $this;
    }
    public function val($values = null): Rule
    {
        $this->values= empty($values)?null:$values;
        return $this;
    }
    public function validator(): string
    {
        return $this->validator;
    }
    public function values()
    {
        return $this->values;
    }
}
