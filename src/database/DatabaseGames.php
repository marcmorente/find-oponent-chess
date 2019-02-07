<?php

/**
 * Description of Users
 *
 * @author mmorente
 */
class DatabaseGames extends MysqlDatabaseConnection
{

    var $games;
    var $name_player;
    var $surname_player;
    var $surname2_player;
    var $full_name;

    public function setPgnToDatabase($games)
    {
        $this->games = $games;
        
        for ($index = 0; $index < count($this->games); $index++) {
            $query = "INSERT INTO games(pgn) VALUES (?)";
            $stmt = $this->database_handle->prepare("INSERT INTO games(pgn) VALUES (?)");
            //$str = str_replace(array("\r\n", "\n", "\r"), '', $this->games[$index]);
            $stmt->bindParam(1, $this->games[$index]);
            if (!$stmt->execute()) {
                exit("Error insert");
            }
        }
        echo 'Imoprt Successful ';
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
        
        if (! empty($name_player) && ! empty($surname_player) ) {
            $query = "SELECT * FROM (SELECT * FROM `games` WHERE pgn LIKE ?) AS surname  WHERE surname.pgn LIKE ?";
            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->name_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else if (! empty($name_player) && ! empty($surname_player) && ! empty($surname2_player) ) {
            $query = "SELECT * FROM (SELECT * FROM (SELECT * FROM `games` WHERE pgn LIKE ?) AS surname  WHERE surname.pgn LIKE ?) AS surname2 WHERE surname2.pgn LIKE ?";
            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->surname2_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else if (! empty($surname_player) && ! empty($surname2_player) ) {
            $query = "SELECT * FROM (SELECT * FROM `games` WHERE pgn LIKE ?) AS surname  WHERE surname.pgn LIKE ?";
            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->surname2_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else {
            $query = "SELECT * FROM `games` WHERE pgn LIKE ? OR pgn LIKE ? OR pgn LIKE ?";
            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->surname2_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        }
        
        return $row;
        
    }

}
