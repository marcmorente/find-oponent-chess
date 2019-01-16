<?php
ini_set('memory_limit', '1G'); // or you could use 1G
require_once(__DIR__ . "./autoload.php");

$parser = new PgnParser('../2017.pgn');
$pgn = $parser->getUnparsedGames();
$DatabaseGames = new DatabaseGames();
//$DatabaseGames->setPgnToDatabase($pgn);
var_dump($pgn);