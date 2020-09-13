<?php

class MysqlDatabaseConnection
{

    private $username = 'root';
    private $password = 'mmp51159';
    private $host = 'localhost';
    private $db_name = 'chess';

    protected $database_handle;

    public function connect()
    {
        try {
            $data_source_name = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8";
            $this->database_handle = new \PDO($data_source_name, $this->username, $this->password);
            $this->database_handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->database_handle->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return $this->database_handle;
    }

    public function disconnect()
    {
        $this->database_handle = null;
    }

}
