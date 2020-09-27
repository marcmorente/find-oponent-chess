<?php


class PersistGames
{
    private $games;
    private $db;

    public function __construct($games, DatabaseRepository $db)
    {
        $this->games = $games;
        $this->db    = $db;
    }

    public function setPgn($debug=false)
    {
        $query_games = new QueryGames($this->db);

        for ($index = 0; $index < count($this->games); $index++) {
            $game           = $this->games[$index];
            $pgn_game       = new PgnGame($game);
            $moves          = $pgn_game->getMoves();
            $database_moves = $query_games->checkIfGameExists($moves);
            
            if ($moves != '' && empty($database_moves)) {
                $white = $pgn_game->getWhite();
                $black = $pgn_game->getBlack();

                $values = [
                    'pgn' => $game,
                    'white_player' => $white,
                    'black_player' => $black,
                    'moves' => $moves
                ];

                $this->db->insert('games', $values);

                if (!empty($this->db->last_error)) {
                    throw new RuntimeException('Error important arxiu! '.$this->db->last_error);
                }
            }

            if (!empty($database_moves) && $debug) {
                echo "Aquesta partida --> $database_moves ja existeix. \n";
            }
        }

        return true;
    }


}