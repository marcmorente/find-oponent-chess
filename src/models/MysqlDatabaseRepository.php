<?php

class MysqlDatabaseRepository implements DatabaseRepository
{

    private $username = 'root';
    private $password = '';
    private $host = 'localhost';
    private $db_name = 'chess';

    private $db;
    private $last_error;

    private function connect()
    {
        try {
            $data_source_name = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8";
            $this->db         = new PDO($data_source_name, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $this->db;
    }

    private function disconnect()
    {
        try {
            $this->db = null;
        } catch (PDOException $e) {
            $this->last_error = 'Close connection failed: ' . $e->getMessage();
        }
    }

    /**
     * Returns a plain array on single selection
     * Returns an arrayed list of row on multiple selections.
     *
     * @param $query string query
     * @param $binds array of :bind => values
     *
     * @param null $multiple_selection
     * @return false|mixed|null
     * @example
     * $query = "SELECT * FROM table WHERE fieldname = :fieldbind";
     * $binds = array(':fieldbind'=>value);
     * $result = $db->Select($query, $binds)
     */
    public function select($query, $binds = null, $multiple_selection = null)
    {
        $this->connect();
        $stmt = $this->db->prepare($query);

        try {
            $stmt->execute($binds);
            $row_count = $stmt->rowCount();
            $results   = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->last_error = 'Execution failed: ' . $e->getMessage();
        }

        // Multiple Selection when requested
        if ($multiple_selection === true) {
            $this->disconnect();
            return $results;
        }

        if ($row_count == 1 && strpos($query, 'LIMIT 1') !== false) { //Single selection
            $this->disconnect();
            return $results[0];
        }

        if ($row_count > 0) {
            $this->disconnect();
            return $results;
        }

        // Empty selection
        $this->disconnect();
        return null;
    }

    /**
     * Inserts on $table a list of $items
     * Returns the last ID on single insert or the number of affected rows on multiple inserts
     *
     * @param $table    table name
     * @param $items    array of field=>value or arrayed list of items
     *
     * @example
     *
     * Single insert:
     * $db->Insert('tablename', array('field1'=>valueA, 'field2'=>valueB));
     */
    public function insert($table, $items)
    {
        $this->connect();
        $values = '';
        $binds  = [];

        // Single insert
        if (!is_array(current($items))) {
            $arr_fields = array_keys($items);

            foreach ($items as $field => $value) {
                $binds[':' . $field] = $value;
                $values .= ':' . $field . ', ';
            }

            $values = substr($values, 0, -2);
            $values = '(' . $values . ')';
        }

        $fields = implode(', ', $arr_fields);
        $query  = "INSERT INTO $table ($fields) VALUES $values";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($binds);

            $return = $stmt->rowCount() == 1 ? $this->db->lastInsertId() : $stmt->rowCount();
            $this->disconnect();

            return $return;
        } catch (PDOException $e) {
            $this->last_error = 'Execution failed: ' . $e->getMessage();
        }
    }
}
