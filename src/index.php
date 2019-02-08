<?php
ini_set('memory_limit', '1G'); // or you could use 1G
require_once(__DIR__ . './autoload.php');

$dir = '../pgn';
$files = scandir($dir);

foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $parser = new PgnParser("$dir/$file");
        $pgn = $parser->getUnparsedGames();
        $DatabaseGames = new DatabaseGames();
        if ($DatabaseGames->setPgnToDatabase($pgn)) {
            echo 'Import Successful<br>';
            rename("$dir/$file", "../data/$file");
        }
    }
}