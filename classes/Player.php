<?php

class Player {


    /**
     *
     * Vráti všetkých registrovaných hráčov z databáze
     *
     * @param object $connection - pripojenie do databáze
     *
     * @return array pole objektov, kde každý objekt je jeden hráč  
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





}