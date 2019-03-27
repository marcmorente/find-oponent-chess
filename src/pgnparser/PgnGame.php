<?php

class PgnGame
{

    private $event;
    private $date;
    private $white;
    private $black;
    private $result;
    private $eco;
    private $moves;
    private $pgn;

    function __construct($pgn)
    {
        $this->pgn = $pgn;

        $this->parseInfo();
        $this->parseMoves();
    }

    private function parseInfo()
    {
        //header('Content-Type: text/php');
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $this->pgn) as $line) {

            if (strpos($line, 'White "') !== false) {
                $white = str_replace('White ', '', preg_replace('/[^A-Za-z0-9\-\ ]/', '', $line));
                $this->setWhite($white);
            }

            if (strpos($line, 'Black "') !== false) {
                $black = str_replace('Black ', '', preg_replace('/[^A-Za-z0-9\-\ ]/', '', $line));
                $this->setBlack($black);
            }

            if (strpos($line, 'Event "') !== false) {
                $event = str_replace('Event ', '', preg_replace('/[^A-Za-z0-9\-\ ]/', '', $line));
                $this->setEvent($event);
            }

            if (strpos($line, 'Date "') !== false) {
                $date = $line;
                $this->setDate($date);
            }

            if (strpos($line, 'Result "') !== false) {
                $result = $line;
                $this->setResult($result);
            }

            if (strpos($line, 'ECO "') !== false) {
                $eco = $line;
                $this->setEco($eco);
            }
        }
    }

    private function parseMoves()
    {
        $moves = '';
        $parser = new PgnGameParser($this->pgn);
        $pgnParsedData = $parser->getParsedData();
        foreach ($pgnParsedData['moves'] as $value) {
            if (isset($value['m'])) {
                $moves .= $value['m'];
            }
        }

        $this->setMoves($moves);
    }

    function getEvent()
    {
        return $this->event;
    }

    function getDate()
    {
        return $this->date;
    }

    function getWhite()
    {
        return $this->white;
    }

    function getBlack()
    {
        return $this->black;
    }

    function getResult()
    {
        return $this->result;
    }

    function getEco()
    {
        return $this->eco;
    }

    function getPgn()
    {
        return $this->pgn;
    }

    function setEvent($event)
    {
        $this->event = $event;
    }

    function setDate($date)
    {
        $this->date = $date;
    }

    function setWhite($white)
    {
        $this->white = $white;
    }

    function setBlack($black)
    {
        $this->black = $black;
    }

    function setResult($result)
    {
        $this->result = $result;
    }

    function setEco($eco)
    {
        $this->eco = $eco;
    }

    function setPgn($pgn)
    {
        $this->pgn = $pgn;
    }

    function getMoves()
    {
        return $this->moves;
    }

    function setMoves($moves)
    {
        $this->moves = $moves;
    }

}
