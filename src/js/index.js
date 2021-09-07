import "../../css/chessboard-0.3.0.css";
import "../../css/font-awesome.min.css";
import "../../css/autocomplete.min.css";
import "../../css/style.min.css";
import "../../css/loading.css";
import "../../css/sweetalert2.min.css";
import "../../css/own.min.css";
import 'bootstrap/dist/css/bootstrap.min.css';

import upload from "./uploadgames";
import 'bootstrap/dist/js/bootstrap';
import "./lib/jquery.autocomplete";
import 'datatables.net-bs';
import "./lib/chessboardjs/js/chessboard-0.3.0";


document.addEventListener("DOMContentLoaded", () => {
  const uploadButton = document.querySelector("#upload");
  uploadButton.addEventListener("click", (e) => {
    e.preventDefault();
    upload();
  });
});

$(document).ready(function () {
  var pgnData = [];

  $("#player_name").autocomplete({
    serviceUrl: "src/ajaxrequest/get_name.php",
    type: "POST",
  });

  //Write the game to the DOM
  function writeGameText(g) {
    //remove the header to get the moves
    var h = g.header();

    if (h.WhiteElo == undefined) {
      h.WhiteElo = "";
    } else {
      h.WhiteElo = " (" + h.WhiteElo + ")";
    }

    if (h.BlackElo == undefined) {
      h.BlackElo = "";
    } else {
      h.BlackElo = " (" + h.BlackElo + ")";
    }

    var gameHeaderText =
      "<h4>" + h.White + h.WhiteElo + " - " + h.Black + h.BlackElo + "</h4>";
    gameHeaderText +=
      "<h5>" + h.Event + ", " + h.Site + " " + h.EventDate + "</h5>";
    var pgn = g.pgn();

    var gameMoves = pgn
      .replace(/\[(.*?)\]/gm, "")
      .replace(h.Result, "")
      .trim();
    //format the moves so each one is individually identified, so it can be highlighted
    moveArray = gameMoves.split(/([0-9]+\.\s)/).filter(function (n) {
      return n;
    });
    for (var i = 0, l = moveArray.length; i < l; ++i) {
      var s = $.trim(moveArray[i]);
      if (!/^[0-9]+\.$/.test(s)) {
        //move numbers
        m = s.split(/\s+/);
        for (var j = 0, ll = m.length; j < ll; ++j) {
          m[j] =
            '<span class="gameMove' +
            (i + j - 1) +
            ' move" data-value="' +
            (i + j - 1) +
            '"><a id="myLink" href="#" value="' +
            (i + j - 1) +
            '">' +
            m[j] +
            "</a></span>";
        }
        s = m.join(" ");
      }
      moveArray[i] = s;
    }

    var gameData =
      gameHeaderText + '<div class="gameMoves">' + moveArray.join(" ");
    if (h.Result)
      gameData += ' <span class="gameResult">' + h.Result + "</span></div>";
    $("#game-data").html(gameData);
  }

  //buttons
  $("#btnStart").on("click", function () {
    game.reset();
    currentPly = -1;
    board.position(game.fen());
    $("#currentFen").val(game.fen());
  });

  $("#btnPrevious").on("click", function () {
    if (currentPly >= 0) {
      game.undo();
      currentPly--;
      board.position(game.fen());
      $("#currentFen").val(game.fen());
    }
  });

  $("#btnNext").on("click", function () {
    if (currentPly < gameHistory.length - 1) {
      currentPly++;
      game.move(gameHistory[currentPly].san);
      board.position(game.fen());
      $("#currentFen").val(game.fen());
    }
  });

  $("#btnEnd").on("click", function () {
    while (currentPly < gameHistory.length - 1) {
      currentPly++;
      game.move(gameHistory[currentPly].san);
    }
    board.position(game.fen());
    $("#currentFen").val(game.fen());
  });

  //used for clickable moves in gametext
  //not used for buttons for efficiency
  function goToMove(ply) {
    if (ply > gameHistory.length - 1) ply = gameHistory.length - 1;
    game.reset();
    for (var i = 0; i <= ply; i++) {
      game.move(gameHistory[i].san);
    }
    currentPly = i - 1;
    board.position(game.fen());
  }

  var onChange = function onChange() {
    //fires when the board position changes
    //highlight the current move
    $("[class^='gameMove']").removeClass("highlight");
    $(".gameMove" + currentPly).addClass("highlight");
  };

  function getMovesAsFENs(chessObj) {
    return chessObj.history().map(function (move) {
      chessObj.move(move);
      return chessObj.fen();
    });
  }

  function loadGame(i) {
    game = new Chess();

    game.load_pgn(pgnData[i].join("\n"), {
      newline_char: "\n",
    });

    writeGameText(game);
    gameHistory = game.history({
      verbose: true,
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
    if (e.keyCode == 39) {
      //right arrow
      if (e.ctrlKey) {
        $("#btnEnd").click();
      } else {
        $("#btnNext").click();
      }
      return false;
    } else if (e.keyCode == 37) {
      //left arrow
      if (e.ctrlKey) {
        $("#btnStart").click();
      } else {
        $("#btnPrevious").click();
      }
      return false;
    } else if (e.keyCode == 38) {
      //up arrow
      if (currentGame > 0) {
        if (e.ctrlKey) {
          loadGame(0);
        } else {
          loadGame(currentGame - 1);
        }
      }
      $("#gameSelect").val(currentGame);
      return false;
    } else if (e.keyCode == 40) {
      //down arrow
      if (currentGame < pgnData.length - 1) {
        if (e.ctrlKey) {
          loadGame(pgnData.length - 1);
        } else {
          loadGame(currentGame + 1);
        }
      }
      $("#gameSelect").val(currentGame);
      return false;
    }
  });

  function enableButton() {
    $("#find_player").prop("disabled", false);
  }

  // find players into models
  $(document).delegate("#find_player", "click", function (e) {
    e.preventDefault();
    $("body, input").addClass("wait");
    $("#find_player").prop("disabled", true);
    board = new ChessBoard("board", cfg);
    $(window).resize(board.resize);
    $("#table").hide();
    $("#game-data").hide();
    var dataTable = [];
    pgnData.length = 0; //clear the array for the next search

    var player_name = $("#player_name").val().toString().replace(",", "");

    if (player_name == "") {
      alert("Has d'omplir algun nom");
      enableButton();
      $("body, input").removeClass("wait");
      return false;
    }

    if (player_name.length < 2) {
      alert("Has de posar un nom amb més d'una lletra");
      enableButton();
      $("body, input").removeClass("wait");
      return false;
    }

    var parametros = {
      player_name: player_name,
    };

    $.ajax({
      data: parametros,
      dataType: "json",
      url: "src/ajaxrequest/get_games_player.php",
      type: "POST",
      error: function (jqXHR, textStatus, errorThrown) {
        enableButton();
        console.log(
          "Error: " +
            JSON.parse(errorThrown) +
            " " +
            JSON.parse(textStatus) +
            " " +
            JSON.parse(jqXHR)
        );
      },
      success: function (p) {
        $("body, input").removeClass("wait");
        if (p.toString() != "not_found") {
          for (var i = 0; i < p.length; i++) {
            pgnData.push(p[i]);
            var g = new Chess();
            var year;
            var date;
            g.load_pgn(p[i].join("\n"), {
              newline_char: "\n",
            });

            var h = g.header();

            if (typeof h.Date === "undefined") {
              h.Date = "-";
            }

            if (typeof h.ECO === "undefined") {
              h.ECO = "-";
            }

            if (typeof h.Event === "undefined") {
              h.Event = "-";
            }

            if (typeof h.Result === "undefined") {
              h.Result = "-";
            }

            if (
              typeof h.White !== "undefined" &&
              typeof h.Black !== "undefined"
            ) {
              dataTable.push({
                tournament: h.Event,
                year: h.Date,
                white: h.White,
                black: h.Black,
                result: h.Result,
                eco: h.ECO,
                btn:
                  '<button class="edit btn btn-success show-pgn" value="' +
                  i +
                  '" type="button" title="Veure partida"><i class="fa fa-eye fa-lg"></i></button>',
              });
            }
          }
          enableButton();
          $("#myTable").DataTable({
            data: dataTable,
            pageLength: 15,
            destroy: true,
            order: [[0, "desc"]],
            columns: [
              { data: "tournament" },
              { data: "year" },
              { data: "white" },
              { data: "black" },
              { data: "result" },
              { data: "eco" },
              { data: "btn" },
            ],
            language: {
              processing: "Processant...",
              search: "Cerca:",
              lengthMenu: "Mostrar _MENU_ registres",
              info: "Mostrant _START_ de _END_ d&apos;un total de _TOTAL_ registre(s)",
              infoEmpty: "Mostrant 0 de 0 d&apos;un total de 0 registres",
              infoFiltered: "(filtrat de _MAX_ registre(s) en total)",
              infoPostFix: "",
              loadingRecords: "S&apos;est&agrave; carregant...",
              zeroRecords: "No hi ha elements per mostrar",
              emptyTable: "No hi ha dades disponibles a la taula",
              paginate: {
                first: "Primer",
                previous: "Anterior",
                next: "Següent",
                last: "&Uacute;ltim",
              },
              aria: {
                sortAscending:
                  ": activeu per ordenar la columna en ordre ascendent",
                sortDescending:
                  ": activeu per ordenar la columna en ordre descendent",
              },
            },
          });
          $("#table").show();
        } else {
          alert("No s'ha trobat cap partida amb aquest criteri de búsqueda ");
          enableButton();
        }
      },
    });
  });

  $(document).delegate(".show-pgn", "click", function () {
    var val = $(this).val();
    loadGame(val);
    $("#game-data").show();
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });

  $(document).delegate("#currentFen", "click", function (e) {
    //https://lichess.org/analysis/standard/rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR
    var url = "https://lichess.org/analysis/standard/" + $(this).val();
    window.open(url, "_blank");
  });

  $(document).delegate(".move", "click", function () {
    var val = $(this).attr("data-value");
    goToMove(val);
  });

  //set up the board
  var cfg = {
    pieceTheme: "chessboardjs/img/chesspieces/wikipedia/{piece}.png",
    position: "start",
    showNotation: true,
    onChange: onChange,
  };
  board = new ChessBoard("board", cfg);
  $(window).resize(board.resize);
});