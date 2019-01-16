<?php

require_once("../autoload.php");

$DatabaseGames = new DatabaseGames();
$search_player = $_POST['name_player'];
$player_list = $DatabaseGames->getGamesToDatabaseByName($search_player);

$games[] = [];
$i = 0;
if (count($player_list) > 0) {
    
    foreach ($player_list as $value) {
        $value['pgn'] = str_replace("'", "\'", $value['pgn']);
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $value['pgn']) as $line) {
            $games[$i][] = $line;
        }
        $i++;
    }
    echo json_encode($games);
    
} else {
    echo "No hi han resultats amb aquest nom";
}




