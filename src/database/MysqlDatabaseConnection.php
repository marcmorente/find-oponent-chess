<?php

abstract class MysqlDatabaseConnection
{

    /*const USERNAME = 'root';
    const PASSWORD = '';
    const HOST     = 'localhost';
    const DB_NAME  = 'chess';*/

    const USERNAME = 'id9217686_marc';
    const PASSWORD = 'Qwerty1234';
    const HOST     = 'localhost';
    const DB_NAME  = 'id9217686_chess';

    protected $database_handle;

    public function __construct()
    {
        $host     = self::HOST;
        $username = self::USERNAME;
        $password = self::PASSWORD;
        $db_name  = self::DB_NAME;

        try {
            $data_source_name      = "mysql:host={$host};dbname={$db_name}";
            $this->database_handle = new \PDO($data_source_name, $username, $password);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

}
