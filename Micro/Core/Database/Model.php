<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 17/02/2019
 * Time: 12:08
 */

namespace API\Core\Database;

use API\Interfaces\ContainerInterface;
use API\Core\Database\Database;
use ReflectionClass;

class Model
{
    protected $db;
    /**
     * @var ContainerInterface
     */
    private $dic;
    /**
     * Model constructor.
     * @param Database $db
//     * @param ContainerInterface $dic
     */
//    public function __construct(Database $db, ContainerInterface $dic)
    public function __construct(Database $db)
    {
        $this->db = $db;
//        $this->dic = $dic;
    }
    /**
     * @param $class
     * @param $data
     * @return array
     * @throws \ReflectionException
     */
    public function toObject($class, $data): array
    {
        $reflection_class = new ReflectionClass("\\Micro\\Models\\$class");
        $constructor = $reflection_class->getConstructor();
        $parameters = $constructor->getParameters();
        $objCollection = [];
        foreach ($data as $item) {
            $values = [];
            foreach ($parameters as $key => $value) {
                array_push($values, $item[$value->name]);
            }
            $new = $reflection_class->newInstanceArgs($values);
            array_push($objCollection, $new);
        }
        return $objCollection;
    }
}
