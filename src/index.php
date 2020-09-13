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
$count = 0;

if (count($files) > 2) {
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && $count === 2) {
            $parser = new PgnParser("$dir/$file");
            $pgn = $parser->getUnparsedGames();
            $persistGames = new PersistGames($pgn);
            if ($persistGames->setPgn()) {
                echo "Partides de l'arxiu <strong>$file</strong>, s'han afegit a la base de dades correctament.<br>";
                //rename("$dir/$file", "../data/$file");
            }
        }
        $count++;
    }
} else {
    echo "<strong>No hi ha cap arxiu per afegir a la base de dades.</strong>";
}
