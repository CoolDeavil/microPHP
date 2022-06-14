<?php
namespace API\Core\Utils;

use GuzzleHttp\Psr7\Request;
use API\Interfaces\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;

class Validator
{
    private array $requestFields = [];
    private array $rules = [];
    private bool $isInitiated = false;
    private array $validationErrors = [];
    private ContainerInterface $dic;
    public bool $isValid = true;
    private Translate $translate;

    public function __construct(ContainerInterface $dic)
    {
        $this->isInitiated = false;
        $this->dic = $dic;
        $this->translate = $dic->get(Translate::class);
    }
    public function init(Request $request): Validator
    {
        if ($request->getMethod() == 'GET') {
            $this->requestFields = $request->getQueryParams();
        } else {
            $this->requestFields = $request->getParsedBody();
        }
        $this->isInitiated = true;
        return $this;
    }
    public function field(string $field): Rule
    {
        return $this->rules[][$field] = new Rule();
    }

    public function validate(): bool
    {
        if (!$this->isInitiated) {
            die("No Form Fields Set....");
        }
        if (!isset($this->rules[0])) {
            return true;
        }
        foreach ($this->rules as $validation) {
            $field = array_keys($validation)[0];
            $validator = $this->dic->get($validation[$field]->validator());
            $err = call_user_func_array(
                $validator,
                [$this->requestFields[$field],$validation[$field]->values()]
            );
            if ($err !== null) {
                if (!isset($this->validationErrors[$field])) {
                    $this->validationErrors[$field] = $err;
                    $this->isValid=false;
                }
            }
        }
        /**@var $rule Rule */
        return $this->isValid;
    }
    public function fetch($key = null)
    {
        if (isset($this->requestFields[$key])) {
            return $this->requestFields[$key];
        }
        return null;
    }
    public function set($field, $value)
    {
        $this->requestFields[$field] = $value;
    }
    public function fetchAll(): array
    {
        return $this->requestFields;
    }
    public function fetchErrors(): array
    {
        return $this->validationErrors;
    }
}
