

$(document).ready(function () {
    var pgnData = [];
    var pgn_database = [];



    //Write the game to the DOM
    function writeGameText(g) {

        //remove the header to get the moves
        var h = g.header();
        var gameHeaderText = '<h4>' + h.White + ' (' + h.WhiteElo + ') - ' + h.Black + ' (' + h.BlackElo + ')</h4>';
        gameHeaderText += '<h5>' + h.Event + ', ' + h.Site + ' ' + h.EventDate + '</h5>';
        var pgn = g.pgn();


        var gameMoves = pgn.replace(/\[(.*?)\]/gm, '').replace(h.Result, '').trim();
        //console.log('gameMoves --> ' + gameMoves);
        //format the moves so each one is individually identified, so it can be highlighted
        moveArray = gameMoves.split(/([0-9]+\.\s)/).filter(function (n) {
            return n;
        });
        for (var i = 0, l = moveArray.length; i < l; ++i) {
            var s = $.trim(moveArray[i]);
            if (!/^[0-9]+\.$/.test(s)) { //move numbers
                m = s.split(/\s+/);
                for (var j = 0, ll = m.length; j < ll; ++j) {
                    m[j] = '<span class="gameMove' + (i + j - 1) + ' move"><a id="myLink" href="#" value="' + (i + j - 1) + '">' + m[j] + '</a></span>';
                }
                s = m.join(' ');
            }
            moveArray[i] = s;
        }

        var gameData = gameHeaderText + '<div class="gameMoves">' + moveArray.join(' ');
        if (h.Result)
            gameData += ' <span class="gameResult">' + h.Result + '</span></div>';
        $("#game-data").html(gameData);

    }

//buttons
    $('#btnStart').on('click', function () {
        game.reset();
        currentPly = -1;
        board.position(game.fen());
    });
    $('#btnPrevious').on('click', function () {
        if (currentPly >= 0) {
            game.undo();
            currentPly--;
            board.position(game.fen());
        }
    });
    $('#btnNext').on('click', function () {
        if (currentPly < gameHistory.length - 1) {
            currentPly++;
            game.move(gameHistory[currentPly].san);
            board.position(game.fen());
        }
    });
    $('#btnEnd').on('click', function () {
        while (currentPly < gameHistory.length - 1) {
            currentPly++;
            game.move(gameHistory[currentPly].san);
        }
        board.position(game.fen());
    });

//used for clickable moves in gametext
//not used for buttons for efficiency
    function goToMove(ply) {
        if (ply > gameHistory.length - 1)
            ply = gameHistory.length - 1;
        game.reset();
        for (var i = 0; i <= ply; i++) {
            game.move(gameHistory[i].san);
        }
        currentPly = i - 1;
        board.position(game.fen());
    }

    var onChange = function onChange() { //fires when the board position changes
        //highlight the current move
        $("[class^='gameMove']").removeClass('highlight');
        $('.gameMove' + currentPly).addClass('highlight');
    };

    function loadGame(i) {
        game = new Chess();
        
        //console.log(pgnData[i]);
        game.load_pgn(pgnData[i].join('\n'), {
            newline_char: '\n'
        });
        //console.log(pgnData[i].join('\n'));
        /*game.load_pgn(pgnData[i], {
         newline_char: '\n'
         });*/
        writeGameText(game);
        gameHistory = game.history({
            verbose: true
        });
        goToMove(-1);
        currentGame = i;
    }

    var board, //the chessboard
            game, //the current  game
            games, //array of all loaded games
            gameHistory,
            currentPly,
            currentGame;
    //key bindings
    $(document).keydown(function (e) {
        if (e.keyCode == 39) { //right arrow
            if (e.ctrlKey) {
                $('#btnEnd').click();
            } else {
                $('#btnNext').click();
            }
            return false;
        } else if (e.keyCode == 37) { //left arrow
            if (e.ctrlKey) {
                $('#btnStart').click();
            } else {
                $('#btnPrevious').click();
            }
            return false;
        } else if (e.keyCode == 38) { //up arrow
            if (currentGame > 0) {
                if (e.ctrlKey) {
                    loadGame(0);
                } else {
                    loadGame(currentGame - 1);
                }
            }
            $('#gameSelect').val(currentGame);
            return false;
        } else if (e.keyCode == 40) { //down arrow
            if (currentGame < pgnData.length - 1) {
                if (e.ctrlKey) {
                    loadGame(pgnData.length - 1);
                } else {
                    loadGame(currentGame + 1);
                }
            }
            $('#gameSelect').val(currentGame);
            return false;
        }
    });
    //start doing stuff
    

    $(document).delegate('#find_player', 'click', function () {

        var dataTable = [];
        var name_player = $('#name_player').val().toString().replace(",", "");
        var parametros = {
            "name_player": name_player
        };
        $.ajax({
            data: parametros,
            dataType: 'json',
            url: "src/ajaxrequest/get_games_player.php",
            type: "POST",
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + errorThrown + ' ' + textStatus + ' ' + jqXHR);
            },
            beforeSend: function (xhr) {
                $("#find_player").text('Buscant partides...');
                $('#find_player').prop('disabled', true);
            },
            success: function (p) {
                
                if (p.toString() != "not_found") {
                    
                    for (var i = 0; i < p.length; i++) {
                        pgnData.push(p[i]);
                        //console.log('primer for ' + i);
                        var g = new Chess();
                        //console.log(p[i].join('\n'));
                        g.load_pgn(p[i].join('\n'), {
                            newline_char: '\n'
                        });
                        var h = g.header();

                        /*$('#gameSelect').append($('<option></option>')
                         .attr('value', i)
                         .text(h.White + ' - ' + h.Black + ', ' + h.Date));*/

                        dataTable.push({
                            tournament: h.Event,
                            player: h.White + ' - ' + h.Black + ', ' + h.Date,
                            btn: '<button class="edit btn btn-default show-pgn" value="'+i+'" type="button" title="Veure partida"><i class="fa fa-eye"></i></button>'
                        });

                    }
                    $("#find_player").text('Buscar jugador');
                    $('#find_player').prop('disabled', false);
                    if ($('#myTable').length > 0) {
                        if (!$.fn.dataTable.isDataTable('#myTable')) {
                            var table = $('#myTable').DataTable({
                                data: dataTable,
                                order: [[0, 'desc']],
                                "columns": [
                                    {"data": "tournament"},
                                    {"data": "player"},
                                    {"data": "btn"}
                                ],
                                pageLength: 10
                            });
                            $('#table').show();
                        }
                    }
                    
                    
                } else {
                    alert("No s'ha trobat cap partida amb el nom " + name_player);
                    $("#find_player").text('Buscar jugador');
                    $('#find_player').prop('disabled', false);
                }

            }
            
        });
        
        

    });
    
    $(document).delegate('.show-pgn', 'click', function () {
        loadGame($(this).val());
    });
    
    $(document).delegate('.move', 'click', function () {
        goToMove($(this).val());
    });
    //only need the headers here, issue raised on github
    //read all the games to populate the select
    //console.log('-------------' + pgnData);


    //set up the board
    var cfg = {
        pieceTheme: 'chessboardjs/img/chesspieces/wikipedia/{piece}.png',
        position: 'start',
        showNotation: true,
        onChange: onChange
    };
    board = new ChessBoard('board', cfg);
    $(window).resize(board.resize);

    //load the first game
    /*if (pgnData.length > 0) {
     loadGame(0);
     goToMove(gameHistory.length - 1);
     }*/

});