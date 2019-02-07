<?php
ini_set('memory_limit', '1G'); // or you could use 1G
require_once(__DIR__ . "./autoload.php");

$dir = '../pgn';
$files  = scandir($dir);

foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $parser = new PgnParser($dir.'/'.$file);
        $pgn = $parser->getUnparsedGames();
        //$pgn = $parser->getGames();
        $DatabaseGames = new DatabaseGames();
        $DatabaseGames->setPgnToDatabase($pgn);
    }
    
}