<?php


class PersistGames extends MysqlDatabaseConnection
{
    private $games;
    private $db;

    public function __construct($games)
    {
        $this->games = $games;
        $this->db = new MysqlDatabaseConnection();
    }

    public function setPgn()
    {
        $queryGames = new QueryGames();

        $array_moves_database = $queryGames->getDatabaseMoves();

        for ($index = 0; $index < count($this->games); $index++) {
            $game = $this->games[$index];

            $pgn_game = new PgnGame($game);
            $white = $pgn_game->getWhite();
            $black = $pgn_game->getBlack();

            $moves = $pgn_game->getMoves();

            //SELECT pgn, moves, COUNT(*) c FROM games GROUP BY moves HAVING c > 1
            //58010
            if ($moves != '' && !in_array($moves, $array_moves_database)) {
                $query = 'INSERT INTO games(pgn, white_player, black_player, moves) VALUES (?, ?, ?, ?)';

                $stmt = $this->db->connect()->prepare($query);
                $stmt->bindParam(1, $game, PDO::PARAM_STR);
                $stmt->bindParam(2, $white, PDO::PARAM_STR);
                $stmt->bindParam(3, $black, PDO::PARAM_STR);
                $stmt->bindParam(4, $moves, PDO::PARAM_STR);

                if (!$stmt->execute()) {
                    return false;
                }
            }
        }

        $this->db->disconnect();

        return true;
    }


}