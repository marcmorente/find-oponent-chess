<?php

abstract class MysqlDatabaseConnection
{

    private $username = 'root';
    private $password = '';
    private $host = 'localhost';
    private $db_name = 'chess';
    private $data_source_name;
    
    protected $database_handle;

    public function __construct()
    {
        try {
            $this->data_source_name = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8";
            $this->database_handle = new \PDO($this->data_source_name, $this->username, $this->password);
            /*
            $this->database_handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->database_handle->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);*/
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

}
