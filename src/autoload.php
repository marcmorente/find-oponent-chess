<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'jsontopgnparser' => '/pgnparser/JsonToPgnParser.php',
                'pgngame' => '/pgnparser/PgnGame.php',
                'chess_json' => '/pgnparser/CHESS_JSON.php',
                'board0x88config' => '/pgnparser/Board0x88Config.php',
                'dgtgameparser' => '/pgnparser/DGTGameParser.php',
                'fenparser0x88' => '/pgnparser/FenParser0x88.php',
                'gameparser' => '/pgnparser/GameParser.php',
                'movebuilder' => '/pgnparser/MoveBuilder.php',
                'moveparser' => '/pgnparser/MoveParser.php',
                'parsertest' => '/test/ParserTest.php',
                'pgngameparser' => '/pgnparser/PgnGameParser.php',
                'mysqldatabaseconnection' => '/database/MysqlDatabaseConnection.php',
                'databasegames' => '/database/DatabaseGames.php',
                'forceutf8' => '/ForceUTF8/Encoding.php',
                'pgnparser' => '/pgnparser/PgnParser.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
// @codeCoverageIgnoreEnd