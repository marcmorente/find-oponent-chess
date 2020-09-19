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
        $query = 'SELECT id FROM games WHERE moves LIKE :moves LIMIT 1';
        $row = $this->db->select($query, [':moves' => '%' . $moves . '%']);

        return $row['id'];
    }

    public function getGamesByName($name, $surname, $surname2)
    {
        if (!empty($name) && !empty($surname)) {

            $query = 'SELECT pgn FROM `games` WHERE '
                . '(white_player LIKE :wname AND white_player LIKE :wsurname) '
                . 'OR '
                . '(black_player LIKE :bname AND black_player LIKE :bsurname) LIMIT ' . $this->limit;

            $row = $this->db->select($query, [
                ':wname'    => '%' . $name . '%',
                ':wsurname' => '%' . $surname . '%',
                ':bname'    => '%' . $name . '%',
                ':bsurname' => '%' . $surname . '%'
            ]);
        } elseif (!empty($name) && !empty($surname) && !empty($surname2)) {

            $query = 'SELECT pgn FROM `games` WHERE '
                . '(white_player LIKE :name AND white_player LIKE :surname AND white_player LIKE :surname2) '
                . 'OR '
                . '(black_player LIKE :name AND black_player LIKE :surname AND black_player LIKE :surname2) LIMIT ' . $this->limit;

            $row = $this->db->select($query, [
                ':wname'     => '%' . $name . '%',
                ':wsurname'  => '%' . $surname . '%',
                ':wsurname2' => '%' . $surname2 . '%',
                ':bname'     => '%' . $name . '%',
                ':bsurname'  => '%' . $surname . '%',
                ':bsurname2' => '%' . $surname2 . '%'
            ]);
        } elseif (!empty($surname) && !empty($surname2)) {

            $query = 'SELECT pgn FROM `games` WHERE '
                . '(white_player LIKE :surname AND white_player LIKE :surname2) '
                . 'OR '
                . '(black_player LIKE :surname AND black_player LIKE :surname2) LIMIT ' . $this->limit;

            $row = $this->db->select($query, [
                ':wsurname'  => '%' . $surname . '%',
                ':wsurname2' => '%' . $surname2 . '%',
                ':bsurname'  => '%' . $surname . '%',
                ':bsurname2' => '%' . $surname2 . '%'
            ]);
        } else {

            $array = [
                $name,
                $surname,
                $surname2
            ];

            foreach ($array as $value) {
                if (!empty($value)) {
                    $query = 'SELECT pgn FROM games WHERE white_player LIKE :wp OR black_player LIKE :bp LIMIT ' . $this->limit;
                    $row = $this->db->select($query, [
                        ':wp' => '%' . $value . '%',
                        ':bp' => '%' . $value . '%'
                    ]);
                }
            }
        }
        return $row;
    }
}
