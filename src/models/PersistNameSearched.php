<?php 

class PersistNameSearched
{

    private $db;
    
    function __construct(DatabaseRepository $db)
    {
        $this->db = $db;
    }

    public function persistNameSearched($player_name, $player_surname, $player_surname2)
    {
        $values = [
            'player_name' => $player_name,
            'player_surname' => $player_surname,
            'player_surname2' => $player_surname2
        ];

        return $this->db->insert('input_search', $values);
    }
}