<?php


namespace API\Core\Container;
use API\Interfaces\ContainerInterface;

class MicroDI implements ContainerInterface
{
    private static MicroDI $instance;
    private array $register;
    private array $resolvedServices = [];
    private function __construct(array $definitions)
    {
        $this->register = array_merge(
            $definitions,
            [ContainerInterface::class => $this]
        );
    }
    public static function getInstance(array $bootstrap): MicroDI
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($bootstrap);
        }
        return self::$instance;
    }
    public function get(string $id, $params = null)
    {
        if (!$this->has($id)) {
            die("[MicroDI] No entry or class found for '$id'");
        }
//        if (array_key_exists($id, $this->resolvedServices)) {
//            return $this->resolvedServices[$id];
//        }
        $callBackFunc = $this->register[$id];
        if ($callBackFunc instanceof \Closure) {
            if ($params) {
                $callBackFunc = $callBackFunc($params, $this );
            }else {
                $callBackFunc = $callBackFunc($this);
            }
        }
        $this->resolvedServices[$id] = $callBackFunc;
        return $callBackFunc;
    }
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->register) || array_key_exists($id, $this->resolvedServices);
    }

}

