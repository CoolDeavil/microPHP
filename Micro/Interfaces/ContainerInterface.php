<?php
namespace API\Interfaces;

//interface ContainerInterface extends \Psr\Container\ContainerInterface
interface ContainerInterface
{

    public function get(string $id, $params = null);
    public function has(string $id): bool;
    public static function getInstance(array $bootstrap) : ContainerInterface;
}
