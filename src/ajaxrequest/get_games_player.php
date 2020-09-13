<?php
//Only for local
//ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '1G'); // or you could use 1G
//Only for local
//set_time_limit(0);
require_once "../autoload.php";

$queryGames      = new QueryGames();
$player_name     = $_POST['player_name'];
$player_surname  = $_POST['player_surname'];
$player_surname2 = $_POST['player_surname2'];

if (
    (!empty($player_name) && strlen($player_name) < 255) ||
    (!empty($player_surname) && strlen($player_surname) < 255) ||
    (!empty($player_surname2) && strlen($player_surname2) < 255)
) {
    $queryGames->setInputSearchToDatabase($player_name, $player_surname, $player_surname2);
}

$player_list = $queryGames->getGamesByName($player_name, $player_surname, $player_surname2);
$games[]     = [];
$i           = 0;

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
    echo json_encode("not_found");
}
