<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 17/02/2019
 * Time: 11:53
 */

namespace API\Core\Database;

class Database
{
    // Hold the class instance.
    private static ?Database $instance = null;
    private \PDO $conn;
    // The db connection is established in the private constructor.
    // Cant be instantiated from the outside
    private function __construct()
    {
        if (DB_TYPE == "mysql") {
            try {
                $this->conn = new \PDO(
                    DB_TYPE.":host=".DB_HOST,
                    DB_USER,
                    DB_PASS,
                    [
                        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES   => false,
                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                    ]
                );
                $dbname = "`".str_replace("`","``",DB_NAME)."`";
                $this->conn->exec("CREATE DATABASE IF NOT EXISTS $dbname COLLATE utf8_general_ci;");
                $this->conn->exec("use $dbname");
            } catch (\PDOException $e) {
                die("MySQL Error " . $e->getMessage());
            }
        } elseif (DB_TYPE == "sqlite") {
            try {
                $this->conn = new \PDO("sqlite:" . PATH_TO_SQLITE_FILE);
                // Set error mode to exceptions
                $this->conn->setAttribute(
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                );
            } catch (\PDOException $e) {
                die("Sqlite Error " . $e->getMessage());
            }
        } elseif (DB_TYPE == "memory") {
            try {
                $this->conn = new \PDO(
                    'sqlite::memory:',
                    null,
                    null,
                    [\PDO::ATTR_PERSISTENT => true]
                );
                // Set error mode to exceptions
                $this->conn->setAttribute(
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                );
            } catch (\PDOException $e) {
                die("Sqlite Memory Error " . $e->getMessage());
            }
        }
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    public function getConnection()
    {
        return $this->conn;
    }
}
