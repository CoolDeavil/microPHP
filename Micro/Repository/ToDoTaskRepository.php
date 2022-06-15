<?php


namespace API\Repository;

use API\Core\Database\Database;
use API\Core\Database\Model;
use API\Interfaces\RepositoryInterface;
use API\Models\ToDoTask;
use PDO;

class ToDoTaskRepository extends Model implements RepositoryInterface
{
    /**
     * @var PDO
     */
    private $conn;

    public function __construct(Database $db)
    {
        parent::__construct($db);
        $this->conn = $db->getConnection();
        ToDoTaskRepository::BuildTable();

    }
    public function registerNew(ToDoTask $data): int
    {
        $query = "insert into ToDoTasks(
                            description,
                            completed, 
                            created, 
                            edited,
                            comments
                            )
                            values(
                          :description_,
                          :completed_,
                          :created_,
                          :edited_,
                          :comments_
                          )";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
          'description_' => $data->getDescription(),
          'completed_' => $data->getCompleted(),
          'created_' => $data->getCreated(),
          'edited_' => time(),
          'comments_' => $data->getComments(),
        ]);
        return (int)$this->conn->lastInsertId();
    }
    public function updateTask(ToDoTask $task)
    {
        $query = "update ToDoTasks set 
                    description = :description_, 
                    comments= :comments_,
                    completed= :completed_,
                    dateCompleted= :dateCompleted_,
                    edited = :edited_
                    where id = :id_";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'id_' => $task->getId(),
            'description_' => $task->getDescription(),
            'comments_' => $task->getComments(),
            'completed_' => $task->getCompleted(),
            'dateCompleted_' => $task->getDateCompleted(),
            'edited_' => time(),
        ]);
        

    }
    public function getAll(): array
    {
        $query="select * from ToDoTasks";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([]);
//        return $stmt->fetchAll(\PDO::FETCH_CLASS,"\\Micro\\Models\\ToDoTask");
        return $stmt->fetchAll(PDO::FETCH_CLASS,ToDoTask::class);

    }
    public function getByID($id)
    {
        $query="select * from ToDoTasks where id = :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetchobject(ToDoTask::class);
    }
    public function deleteByID($id): bool
    {
        $query="delete from ToDoTasks where id = :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'id' => $id
        ]);
        return true;
    }
    public function buildTable()
    {
        $sqlData = match (DB_TYPE) {
            'memory', 'sqlite' => file_get_contents(PATH_BUILD_TABLES . "toDoTasks_sqlite.sql"),
            'mysql' => file_get_contents(PATH_BUILD_TABLES . "toDoTasks_mysql.sql"),
            default => null,
        };
        $conn = $this->db->getConnection();
        $conn->exec($sqlData);
        return;
    }
    public function registerNewX($data)
    {
        // TODO: Implement registerNew() method.
    }
    public function update($data)
    {
        // TODO: Implement update() method.
    }

}