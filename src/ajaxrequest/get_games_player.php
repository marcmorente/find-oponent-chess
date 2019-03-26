<?php
ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '1G'); // or you could use 1G
set_time_limit(0);
require_once("../autoload.php");

$DatabaseGames = new DatabaseGames();
$name_player = $_POST['name_player'];
$surname_player = $_POST['surname_player'];
$surname2_player = $_POST['surname2_player'];
$player_list = $DatabaseGames->getGamesToDatabaseByName($name_player, $surname_player, $surname2_player);
$games[] = [];
$i = 0;

if (count($player_list) > 0) {
    foreach ($player_list as $value) {
        $value['pgn'] = str_replace("'", "\'", $value['pgn']);
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $value['pgn']) as $line) {
            $games[$i][] = utf8_encode($line);
        }
        $i++;
    }
    echo json_encode($games);
} else {
    echo json_encode("not_found");
}




