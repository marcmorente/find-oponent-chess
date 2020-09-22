<?php
require_once "../autoload.php";

$db            = new MysqlDatabaseRepository();
$query_games   = new QueryGames($db);

$name        = $_POST['query'];
$name_result = $query_games->getName($name);

if(!empty($name_result)) {
    echo json_encode($name_result);
} else {
    echo json_encode([
        'suggestions' => ['Sense resultats']
    ]);
}
