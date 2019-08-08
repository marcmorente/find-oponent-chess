<?php
//Only for local
//ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '1G'); // or you could use 1G
//Only for local
//set_time_limit(0);
require_once './autoload.php';

$dir = '../pgn';
$files = scandir($dir);

header('Content-Type: text/php');
if (count($files) > 2) {
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $parser = new PgnParser("$dir/$file");
            $pgn = $parser->getUnparsedGames();
            $DatabaseGames = new DatabaseGames();
            if ($DatabaseGames->setPgnToDatabase($pgn)) {
                echo "Partides de l'arxiu <strong>$file</strong>, s'han afegit a la base de dades correctament.<br>";
                //rename("$dir/$file", "../data/$file");
            }
        }
    }
} else {
    echo "<strong>No hi ha cap arxiu per afegir a la base de dades.</strong>";
}
