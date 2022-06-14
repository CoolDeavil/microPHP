<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 17/02/2019
 * Time: 12:13
 */

namespace API\Interfaces;

use API\Models\ToDoTask;

interface RepositoryInterface
{
    public function getAll();
    public function getByID($id);
    public function deleteByID($id);
    public function registerNew(ToDoTask $data);
    public function update($data);

}
