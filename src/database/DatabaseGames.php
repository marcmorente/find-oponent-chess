<?php

/**
 * @author mmorente
 */
class DatabaseGames extends MysqlDatabaseConnection
{

    var $games;
    var $name_player;
    var $surname_player;
    var $surname2_player;
    var $moves;
    var $full_name;

    public function setPgnToDatabase($games)
    {
        $this->games = $games;
        
        for ($index = 0; $index < count($this->games); $index++) {
            
            $game = $this->games[$index];
            
            $pgn_game = new PgnGame($game);
            $white = $pgn_game->getWhite();
            $black = $pgn_game->getBlack();
            $moves = $pgn_game->getMoves();
            //SELECT pgn, moves, COUNT(*) c FROM games GROUP BY moves HAVING c > 1 
            //58010
            if ($moves != '' && $this->checkIfGameExist($moves) == 0) {
                $query = "INSERT INTO games(pgn, white_player, black_player, moves) VALUES (?, ?, ?, ?)";

                $stmt = $this->database_handle->prepare($query);
                $stmt->bindParam(1, $game, PDO::PARAM_STR);
                $stmt->bindParam(2, $white, PDO::PARAM_STR);
                $stmt->bindParam(3, $black, PDO::PARAM_STR);
                $stmt->bindParam(4, $moves, PDO::PARAM_STR);

                if (!$stmt->execute()) {
                    return false;
                }
            }
        }
        
        return true;
    }

    private function checkIfGameExist($moves)
    {
        
        $query = "SELECT * FROM `games` WHERE moves = '$moves'";
        $stmt = $this->database_handle->prepare($query);
        $stmt->execute();
        $row = $stmt->rowCount();

        return $row;
    }

    public function getGamesToDatabase()
    {
        $query = "SELECT pgn FROM games";

        $stmt = $this->database_handle->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll();

        return $row;
    }

    public function getGamesToDatabaseByName($name_player, $surname_player, $surname2_player)
    {
        $this->name_player = "%$name_player%";
        $this->surname_player = "%$surname_player%";
        $this->surname2_player = "%$surname2_player%";

        if (!empty($name_player) && !empty($surname_player)) {
            $query = "SELECT pgn FROM `games` WHERE "
                    . "(white_player LIKE ? AND white_player LIKE ?) "
                    . "OR "
                    . "(black_player LIKE ? AND black_player LIKE ?)";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->surname_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else if (!empty($name_player) && !empty($surname_player) && !empty($surname2_player)) {
            $query = "SELECT pgn FROM `games` WHERE "
                    . "(white_player LIKE ? AND white_player LIKE ? AND white_player LIKE ?) "
                    . "OR "
                    . "(black_player LIKE ? AND black_player LIKE ? AND black_player LIKE ?)";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->surname2_player, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(5, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(6, $this->surname2_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else if (!empty($surname_player) && !empty($surname2_player)) {
            $query = "SELECT pgn FROM `games` WHERE "
                    . "(white_player LIKE ? AND white_player LIKE ?) "
                    . "OR "
                    . "(black_player LIKE ? AND black_player LIKE ?)";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->surname2_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->surname2_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else {
            $array = array(
                $name_player => $this->name_player,
                $surname_player => $this->surname_player,
                $surname2_player => $this->surname2_player
            );

            foreach ($array as $key => $value) {
                if (!empty($key)) {
                    $search = "%$surname_player $surname2_player%";
                    $query = "SELECT pgn FROM `games` WHERE white_player LIKE ? OR black_player LIKE ? ";

                    $stmt = $this->database_handle->prepare($query);
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
