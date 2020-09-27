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
        $this->db    = $db;
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
        $split_name = new SplitName($name);
        $split      = $split_name->buildSplit();

        $autocomplete['suggestions'] = [];

        $query = 'SELECT white_player, black_player FROM games WHERE '
            . $split['query'] . ' GROUP BY white_player, black_player LIMIT 20';

        $row = $this->db->select($query, $split['binds']);

        foreach ($row as $res) {
            $white_player = trim($res['white_player']);
            $black_player = trim($res['black_player']);

            if (
                preg_match('/' . $split["regex"] . '/i', $white_player) &&
                !in_array($white_player, $autocomplete['suggestions'])
            ) {
                $autocomplete['suggestions'][] = $white_player;
            }

            if (
                preg_match('/' . $split["regex"] . '/i', $black_player) &&
                !in_array($black_player, $autocomplete['suggestions'])
            ) {
                $autocomplete['suggestions'][] = $black_player;
            }
        }

        return $autocomplete;
    }

    public function getGamesByName($name)
    {
        $split_name = new SplitName($name);
        $split = $split_name->buildSplit();

        $query = 'SELECT pgn FROM games WHERE '
            . $split['query'] . ' LIMIT ' . $this->limit;

        return $this->db->select($query, $split['binds']);
    }

    public function print_query($query, $binds = null, $print = true)
    {
        $find = $repl = array();
        if (!empty($binds)) {
            foreach ($binds as $k => $v) {
                if (substr($k, 0, 1) == ':')
                    $k = substr($k, 1);

                array_push($find, ':' . $k);
                array_push($repl, "'$v'");
            }
        }

        $query_out = str_replace($find, $repl, $query);

        if ($print) {
            echo $query_out;
        } else {
            return $query_out;
        }
    }
}
