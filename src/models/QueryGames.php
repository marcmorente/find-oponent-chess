<?php

/**
 * @author Marc Morente
 */
class QueryGames
{

    private $db;
    private $limit;

    public function __construct(DatabaseRepository $db)
    {
        $this->db = $db;
        $this->limit = 3000;
    }

    public function checkIfGameExists($moves)
    {
        $query = 'SELECT id FROM games WHERE moves = :moves LIMIT 1';
        $row = $this->db->select($query, [':moves' => $moves]);

        return $row['id'];
    }

    public function getName($name)
    {
        $autocomplete = [];
        $query = 'SELECT white_player FROM `games` WHERE '
            . 'white_player LIKE :wname GROUP BY white_player LIMIT 20';

        $row = $this->db->select($query, [
            ':wname'    => '%' . $name . '%'
        ]);

        foreach ($row as $res) {
            $autocomplete['suggestions'][]['value'] = $res['white_player'];
        }

        return $autocomplete;
    }

    public function getGamesByName($name)
    {
        $query = 'SELECT pgn FROM games WHERE white_player LIKE :wp OR black_player LIKE :bp LIMIT ' . $this->limit;

        return $this->db->select($query, [
            ':wp' => '%' . $name . '%',
            ':bp' => '%' . $name . '%'
        ]);
    }
}
