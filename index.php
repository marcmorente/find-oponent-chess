<?php
require_once(__DIR__ . "/chessParser/autoload.php");
$DatabaseGames = new DatabaseGames();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SwiftStack Chess Game Status Board</title>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,400italic,700italic|Roboto+Slab:400,700' rel='stylesheet'
        type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="chessboardjs/css/chessboard-0.3.0.min.css" />
    <link rel="stylesheet" href="stylesheets/style.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>SwiftStack Chess Game Status Board</h1>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <div id="board" style="width: 100%;"></div>
                <div id="board-buttons">
                    <button type="button" class="btn btn-default" id="btnStart"><i class="fa fa-fast-backward fa-lg"></i></button>
                    <button type="button" class="btn btn-default" id="btnPrevious"><i class="fa fa-step-backward fa-lg"></i></button>
                    <button type="button" class="btn btn-default" id="btnNext"><i class="fa fa-step-forward fa-lg"></i></button>
                    <button type="button" class="btn btn-default" id="btnEnd"><i class="fa fa-fast-forward fa-lg"></i></button>
                </div>
            </div>
            <div class="col-xs-8">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="gameSelect" class="col-xs-4 control-label">Select game:</label>
                        <div class="col-xs-8">
                            <select id="gameSelect" class="form-control input-sm" onchange="loadGame(this.value);return false;"></select>
                        </div>
                    </div>
                </form>
                <div id="game-data">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h4>Keyboard Shortcuts</h4>
                <dl class="dl-horizontal">
                    <dt>Next move:</dt>
                    <dd>Right arrow key</dd>
                    <dt>Previous move:</dt>
                    <dd>Left arrow key</dd>
                    <dt>End of game:</dt>
                    <dd>Ctrl + right arrow key</dd>
                    <dt>Start of game:</dt>
                    <dd>Ctrl + left arrow key</dd>
                    <dt>Next game:</dt>
                    <dd>Up arrow key</dd>
                    <dt>Previous game:</dt>
                    <dd>Down arrow key</dd>
                    <dt>First game:</dt>
                    <dd>Ctrl + up arrow key</dd>
                    <dt>Last game:</dt>
                    <dd>Ctrl + down arrow key</dd>
                </dl>
            </div>
        </div>
    </div>
    <script src="chessboardjs/js/chessboard-0.3.0.min.js"></script>
    <script src="chessjs/chess.js"></script>
    <script>

        var pgnData = [];
        <?php foreach ($DatabaseGames->getGamesToDatabase() as $value) {?>
            pgnData.push( ['<?= str_replace("'", "\'", $value["pgn"]); ?>']);
        <?php } ?> 

        //console.log(pgnData);

        
    </script>
<!--    <script src="data/games.js"></script>-->
    <script src="js/pgnviewer.js"></script>
</body>

</html>