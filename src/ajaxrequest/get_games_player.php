<?php
//Only for local
//ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '1G'); // or you could use 1G
//Only for local
//set_time_limit(0);
require_once "../autoload.php";

$db            = new MysqlDatabaseRepository();
$query_games   = new QueryGames($db);

$player_name     = $_POST['player_name'];

if (!empty($player_name) && strlen($player_name) < 255) {
    $insert_search = new PersistNameSearched($db);
    $insert_search->persistNameSearched($player_name);
}

$player_list = $query_games->getGamesByName($player_name);
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
