<?php

require_once(__DIR__ . "/chessParser/autoload.php");

$DatabaseGames = new DatabaseGames();
$search_player = $_POST['name_player'];
$list = $DatabaseGames->getGamesToDatabaseByName($search_player);

$games[] = [];
$i = 0;
if (count($DatabaseGames->getGamesToDatabaseByName($search_player)) > 0) {
    foreach ($DatabaseGames->getGamesToDatabaseByName($search_player) as $value) {
        $value['pgn'] = str_replace("'", "\'", $value['pgn']);
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $value['pgn']) as $line) {
            $games[$i][] = $line;
        }
        $i++;
    }
    print_r($games);
} else {
    echo "No hi han resultats amb aquest nom";
}




