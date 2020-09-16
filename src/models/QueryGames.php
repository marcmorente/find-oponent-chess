<?php

/**
 * @author mmorente
 */
class QueryGames
{

    private $db;
    private $games;
    private $player_name;
    private $player_surname;
    private $player_surname2;
    private $moves;
    private $full_name;
    private $array_moves_pgn = [];

    public function __construct(DatabaseRepository $db)
    {
        $this->db = $db;
    }

    public function getDatabaseMoves($moves)
    {
        $query = 'SELECT moves FROM games WHERE moves = :moves LIMIT 1';
        $row = $this->db->select($query, [':moves' => $moves]);

        return $row['moves'];
    }

    public function getGamesByName($player_name, $player_surname, $player_surname2)
    {
        $this->player_name = '%' . $player_name . '%';
        $this->player_surname = '%' . $player_surname . '%';
        $this->player_surname2 = '%' . $player_surname2 . '%';

        if (!empty($player_name) && !empty($player_surname)) {

            $query = 'SELECT pgn FROM `games` WHERE '
                . '(white_player LIKE ? AND white_player LIKE ?) '
                . 'OR '
                . '(black_player LIKE ? AND black_player LIKE ?)';

            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(1, $this->player_name, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->player_surname, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->player_name, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->player_surname, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } elseif (!empty($player_name) && !empty($player_surname) && !empty($player_surname2)) {

            $query = 'SELECT pgn FROM `games` WHERE '
                . '(white_player LIKE ? AND white_player LIKE ? AND white_player LIKE ?) '
                . 'OR '
                . '(black_player LIKE ? AND black_player LIKE ? AND black_player LIKE ?)';

            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(1, $this->player_name, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->player_surname, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->player_surname2, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->player_name, PDO::PARAM_STR);
            $stmt->bindParam(5, $this->player_surname, PDO::PARAM_STR);
            $stmt->bindParam(6, $this->player_surname2, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } elseif (!empty($player_surname) && !empty($player_surname2)) {

            $query = 'SELECT pgn FROM `games` WHERE '
                . '(white_player LIKE ? AND white_player LIKE ?) '
                . 'OR '
                . '(black_player LIKE ? AND black_player LIKE ?)';

            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(1, $this->player_surname, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->player_surname2, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->player_surname, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->player_surname2, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else {

            $array = array(
                $player_name => $this->player_name,
                $player_surname => $this->player_surname,
                $player_surname2 => $this->player_surname2,
            );

            foreach ($array as $key => $value) {
                if (!empty($key)) {
                    $query = 'SELECT pgn FROM `games` WHERE white_player LIKE ? OR black_player LIKE ? ';

                    $stmt = $this->db->connect()->prepare($query);
                    $stmt->bindParam(1, $value, PDO::PARAM_STR);
                    $stmt->bindParam(2, $value, PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                }
            }
        }
        return $row;
    }

}
