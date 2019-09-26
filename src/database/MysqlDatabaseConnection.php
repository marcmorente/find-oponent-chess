<?php

abstract class MysqlDatabaseConnection
{

    const USERNAME = 'root';
    const PASSWORD = '';
    const HOST = 'localhost';
    const DB_NAME = 'chess';
    
    protected $database_handle;

    public function __construct()
    {
        $host = self::HOST;
        $username = self::USERNAME;
        $password = self::PASSWORD;
        $db_name = self::DB_NAME;

        try {
            $data_source_name = "mysql:host={$host};dbname={$db_name};charset=utf8";
            $this->database_handle = new \PDO($data_source_name, $username, $password);
            /* Only debug mode
            $this->database_handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->database_handle->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);*/
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

}
