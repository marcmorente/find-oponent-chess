<?php
//Only for local
ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '1G'); // or you could use 1G
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Only for local
set_time_limit(0);
require_once $_SERVER['DOCUMENT_ROOT'].'/src/autoload.php';

$dir = '../pgn';
$files = scandir($dir);

header('Content-Type: text/php');

if (count($files) > 2) {
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $parser = new PgnParser("$dir/$file");
            $pgn = $parser->getUnparsedGames();

            $db = new MysqlDatabaseRepository();
            $persistGames = new PersistGames($pgn, $db);

            $persistGames->setPgn();
            echo "Partides de l'arxiu $file, s'han afegit a la base de dades correctament.\n";
        }
    }
} else {
    echo "<strong>No hi ha cap arxiu per afegir a la base de dades.</strong>";
}
