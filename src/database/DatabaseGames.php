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
    var $array_moves_pgn = [];

    public function setPgnToDatabase($games)
    {
        $this->games = $games;
        $array_moves_database = $this->getMovesToDatabase();
        
        for ($index = 0; $index < count($this->games); $index++) {
            $game = $this->games[$index];
            
            $pgn_game = new PgnGame($game);
            $white = $pgn_game->getWhite();
            $black = $pgn_game->getBlack();
            
            //Add moves into array key for eliminate duplicate games
            $this->array_moves_pgn[$pgn_game->getMoves()] = true;
            
            //Get moves from array key
            $moves = key($this->array_moves_pgn);
            
            //SELECT pgn, moves, COUNT(*) c FROM games GROUP BY moves HAVING c > 1 
            //58010
            if ($moves != '' && !isset($array_moves_database[$moves])) {
                $query = 'INSERT INTO games(pgn, white_player, black_player, moves) VALUES (?, ?, ?, ?)';

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
    
    public function getMovesToDatabase()
    {
        $array_moves = [];
        $query = 'SELECT moves FROM games';
        
        $stmt = $this->database_handle->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll();

        foreach ($row as $value) {
            if (!empty($value['moves'])) {
                $array_moves[] = $value['moves'];
            }
        }
        
        $array_flip = array_flip($array_moves);

        return $array_flip;
    }

    public function getGamesToDatabase()
    {
        $query = 'SELECT pgn FROM games';

        $stmt = $this->database_handle->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll();

        return $row;
    }

    public function getGamesToDatabaseByName($name_player, $surname_player, $surname2_player)
    {
        $this->name_player = '%'.$name_player.'%';
        $this->surname_player = '%'.$surname_player.'%';
        $this->surname2_player = '%'.$surname2_player.'%';

        if (!empty($name_player) && !empty($surname_player)) {
            $query = 'SELECT pgn FROM `games` WHERE '
                    . '(white_player LIKE ? AND white_player LIKE ?) '
                    . 'OR '
                    . '(black_player LIKE ? AND black_player LIKE ?)';

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->surname_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else if (!empty($name_player) && !empty($surname_player) && !empty($surname2_player)) {
            $query = 'SELECT pgn FROM `games` WHERE '
                    . '(white_player LIKE ? AND white_player LIKE ? AND white_player LIKE ?) '
                    . 'OR '
                    . '(black_player LIKE ? AND black_player LIKE ? AND black_player LIKE ?)';

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
            $query = 'SELECT pgn FROM `games` WHERE '
                    . '(white_player LIKE ? AND white_player LIKE ?) '
                    . 'OR '
                    . '(black_player LIKE ? AND black_player LIKE ?)';

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
                    $query = 'SELECT pgn FROM `games` WHERE white_player LIKE ? OR black_player LIKE ? ';

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
