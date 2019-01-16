<?php
require_once(__DIR__ . "/src/autoload.php");
$DatabaseGames = new DatabaseGames();
$games[] = [];
$i = 0;
$j = 0;
foreach ($DatabaseGames->getGamesToDatabase() as $value) {
    $value['pgn'] = str_replace("'", "\'", $value['pgn']);
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $value['pgn']) as $line){
        $games[$i][] = $line;
    }
    $i++;
}

/*foreach ($games as $value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    exit;
    
}*/

//echo '<pre>';
//var_dump($games);
//echo '</pre>';
//exit;

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
                        
                        <label for="gameSelect" class="col-xs-2 control-label">Buscar jugador:</label>
                        
                        <div class="col-xs-8">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Primer cognom, Segon cognom, Nom" id="name_player" value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" id="find_player">Buscar</button>
                                </span>
                            </div>
                            <div id="result-search"></div>
                            <div class="table-container"></div>
                            
<!--                            <select id="gameSelect" class="form-control input-sm" onchange="loadGame(this.value);return false;"></select>-->
                        </div>
                    </div>
                </form>
                <div id="game-data"></div>
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
        var pgn_database = [];
        <?php foreach ($games as $value) { ?>
            <?php foreach ($value as $val) { ?>
                pgn_database.push('<?= $val; ?>');
            <?php } ?> 
            pgnData.push(pgn_database);
        <?php } ?> 

        console.log(pgnData);
        
        $(document).ready(function () {
            var data = {
                k: ['Name', 'Occupation'],
                v: [['Chandler', 'IT Procurement Manager'],
                    ['Joey', 'Out-of-work Actor'],
                    ['Monica', 'Chef'],
                    ['Rachel', 'Assistant Buyer'],
                    ['Ross', 'Dinosaurs']]
            }
            //creates new table object
            var table = new Table();

            //sets table data and builds it
            table
                .setHeader(data.k)
                .setData(data.v)
                .setTableClass('table')
                .build()
            $(document).delegate('#find_player', 'click', function() {
                var name_player = $('#name_player').val();

                var parametros = {
                    "name_player" : name_player
                };
                $.ajax({
                    data: parametros,
                    url : "get_games_player.php",
                    type: "POST",
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error: ' + errorThrown + ' ' + textStatus + ' ' + jqXHR);
                    },
                    success: function(data) {
                        console.log(data);
                    }
                });

            });
            
        });

        
    </script>
    
    
<!--    <script src="data/games.js"></script>-->
    <script src="js/pgnviewer.js"></script>
    <script src="js/table.js " type="text/javascript"></script>
</body>

</html>