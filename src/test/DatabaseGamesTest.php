<?php
require_once(__DIR__ . "/../autoload.php");
use PHPUnit\Framework\TestCase;


class DatabaseGamesTest extends TestCase
{
    
    
    /**
     * @test
     */
    public function shouldGetDataFromDatabaseByName()
    {
        $name_player = "david";
        $surname_player = "garcia";
        $surname2_player = "";
        
        $database = new QueryGames();
        
        // given
        $result = $database->getGamesToDatabaseByName($name_player, $surname_player, $surname2_player);

        $this->assertGreaterThan(0, count($result));
    }

    

}
