<?php 

interface DatabaseRepository
{
    /**
     * @param string $query
     * @param null $binds
     * @param null $multiple_selection
     * @return mixed
     */
    public function select($query, $binds=null, $multiple_selection=null);

    /**
     * @param string $table
     * @param array $items
     * @return mixed
     */
    public function insert($table, $items);
}