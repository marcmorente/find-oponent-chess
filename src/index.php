<?php
ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '1G'); // or you could use 1G
set_time_limit(0);
require_once(__DIR__ . './autoload.php');

$dir = '../pgn';
$files = scandir($dir);
header('Content-Type: text/php');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $parser = new PgnParser("$dir/$file");
        $pgn = $parser->getUnparsedGames();
        $DatabaseGames = new DatabaseGames();
        if ($DatabaseGames->setPgnToDatabase($pgn)) {
            echo '\nImport Successful';
            rename("$dir/$file", "../data/$file");
        }
    }
}