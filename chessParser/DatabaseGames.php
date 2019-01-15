<?php

/**
 * Description of Users
 *
 * @author mmorente
 */
class DatabaseGames extends MysqlDatabaseConnection
{

    var $games;

    public function setPgnToDatabase($games)
    {
        $this->games = $games;
        
        for ($index = 0; $index < count($this->games); $index++) {
            $query = "INSERT INTO games(pgn) VALUES (?)";
            $stmt = $this->database_handle->prepare("INSERT INTO games(pgn) VALUES (?)");
            $str = str_replace(array("\r\n", "\n", "\r"), '', $this->games[$index]);
            $stmt->bindParam(1, $str);
            if (!$stmt->execute()) {
                exit("Error insert");
            }
        }
    }
    
    public function getGamesToDatabase()
    {
        $query = "SELECT pgn FROM games";
        
        $stmt = $this->database_handle->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        return $row;
        
    }

}
