<?php

class Player {

    /**
     *
     * RETURN ALL REGISTERED PLAYERS FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllPlayers($connection, $columns = "*"){
        $sql = "SELECT $columns
                FROM player_user";

        $stmt = $connection->prepare($sql);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o hráčoch sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllPlayers, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ONE PLAYER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $player_Id - id for one player
     * @return array asoc array with one player
     */
    public static function getPlayer($connection, $player_Id){
        $sql = "SELECT  first_name, 
                        second_name, 
                        country, 
                        player_club, 
                        player_cue, 
                        player_break_cue, 
                        player_jump_cue
                FROM player_user
                WHERE player_Id = :player_Id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one player
                return $stmt->fetch();
            } else {
                throw Exception ("Príkaz pre získanie všetkých dát o hráčoch sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getPlayer, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }
}