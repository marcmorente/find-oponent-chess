<?php 

class PersistNameSearched
{

    private $db;
    
    function __construct(DatabaseRepository $db)
    {
        $this->db = $db;
    }

    public function persistNameSearched($player_name)
    {
        $values = [
            'player_name' => $player_name
        ];

        return $this->db->insert('input_search', $values);
    }
}