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
    var $full_name;
    var $player = [];

    public function setPgnToDatabase($games)
    {
        $this->games = $games;

        for ($index = 0; $index < count($this->games); $index++) {

            foreach (preg_split("/((\r?\n)|(\r\n?))/", $this->games[$index]) as $line) {
                // do stuff with $line

                if (strpos($line, 'White ') !== false) {

                    $white = str_replace("White ", "", preg_replace('/[^A-Za-z0-9\-\ ]/', '', $line));
                    $this->player["white"] = $white;
                }

                if (strpos($line, 'Black ') !== false) {
                    $black = str_replace("Black ", "", preg_replace('/[^A-Za-z0-9\-\ ]/', '', $line));
                    $this->player["black"] = $black;
                }
            }

            $query = "INSERT INTO games(pgn, white_player, black_player) VALUES (?, ?, ?)";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->games[$index], PDO::PARAM_STR);
            $stmt->bindParam(2, $this->player["white"], PDO::PARAM_STR);
            $stmt->bindParam(3, $this->player["black"], PDO::PARAM_STR);


            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
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

            $query = "SELECT * FROM (SELECT * FROM `games` WHERE white_player LIKE ? OR black_player LIKE ?) AS surname  WHERE surname.white_player LIKE ? OR surname.black_player LIKE ?";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->name_player, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->surname_player, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->surname_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();


            /* $query = "SELECT * FROM (SELECT * FROM `games` WHERE pgn LIKE ?) AS surname  WHERE surname.pgn LIKE ?";
              $stmt = $this->database_handle->prepare($query);
              $stmt->bindParam(1, $this->name_player, PDO::PARAM_STR);
              $stmt->bindParam(2, $this->surname_player, PDO::PARAM_STR);
              $stmt->execute();
              $row = $stmt->fetchAll(); */
        } else if (!empty($name_player) && !empty($surname_player) && !empty($surname2_player)) {
            //$query = "SELECT * FROM (SELECT * FROM (SELECT * FROM `games` WHERE pgn LIKE ?) AS surname  WHERE surname.pgn LIKE ?) AS surname2 WHERE surname2.pgn LIKE ?";

            $search = "%$surname_player $surname2_player $name_player%";
            $query = "SELECT * FROM `games` WHERE white_player LIKE ? OR black_player LIKE ? ";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $search, PDO::PARAM_STR);
            $stmt->bindParam(2, $search, PDO::PARAM_STR);
            //$stmt->bindParam(3, $this->surname2_player, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetchAll();
        } else if (!empty($surname_player) && !empty($surname2_player)) {
            //$query = "SELECT * FROM (SELECT * FROM `games` WHERE pgn LIKE ?) AS surname  WHERE surname.pgn LIKE ?";

            $search = "%$surname_player $surname2_player%";
            $query = "SELECT * FROM `games` WHERE white_player LIKE ? OR black_player LIKE ? ";

            $stmt = $this->database_handle->prepare($query);
            $stmt->bindParam(1, $search, PDO::PARAM_STR);
            $stmt->bindParam(2, $search, PDO::PARAM_STR);
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
                    $query = "SELECT * FROM `games` WHERE white_player LIKE ? OR black_player LIKE ? ";

                    $stmt = $this->database_handle->prepare($query);
                    $stmt->bindParam(1, $value, PDO::PARAM_STR);
                    $stmt->bindParam(2, $value, PDO::PARAM_STR);




                    /*
                    $query = "SELECT * FROM `games` WHERE pgn LIKE ?";
                    $stmt = $this->database_handle->prepare($query);
                    $stmt->bindParam(1, $value, PDO::PARAM_STR);*/
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                }
            }
        }
        return $row;
    }

}
