<?php
ini_set('memory_limit', '1G'); // or you could use 1G
require_once(__DIR__ . "./autoload.php");

$parser = new PgnParser('../pgn/fins2010.pgn');
$pgn = $parser->getUnparsedGames();
//$pgn = $parser->getGames();

$DatabaseGames = new DatabaseGames();
$DatabaseGames->setPgnToDatabase($pgn);