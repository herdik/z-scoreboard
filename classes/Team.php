<?php

class Team {
    

    /**
     *
     * ADD PLAYER/USER TO DATABASE
     *
     * @param object $connection - database connection
     * @param string $team_name - team_name
     * @param string $team_image - team_image
     * @param string $team_country - team_country
     *
     * @return boolean true or false
     * 
     */
    public static function createTeam($connection, $team_name, $team_image, $team_country) {

        // sql scheme
        $sql = "INSERT IGNORE INTO team_user (team_name, team_image, team_country)
        VALUES (:team_name, :team_image, :team_country)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":team_name", $team_name, PDO::PARAM_STR);
        $stmt->bindValue(":team_image", $team_image, PDO::PARAM_STR);
        $stmt->bindValue(":team_country", $team_country, PDO::PARAM_STR);

        
        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                $team_id = $connection->lastInsertId();
                return $team_id;
            } else {
                throw new Exception ("Vytvorenie nového teamu sa neuskutočnilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createTeam\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ONE TEAM FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $team_id - id for one team
     * @return array asoc array with one team
     */
    public static function getTeam($connection, $team_id, $columns = "*"){
        $sql = "SELECT $columns
                FROM team_user
                WHERE team_id = :team_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":team_id", $team_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one player
                return $stmt->fetch();
            } else {
                throw Exception ("Príkaz pre získanie všetkých dát ohľadom teamu sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getTeam, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL REGISTERED TEAMS FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllTeams($connection, $columns = "*"){
        $sql = "SELECT $columns
                FROM team_user
                WHERE team_id != 0
                ORDER BY team_name";

        $stmt = $connection->prepare($sql);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o družstvách sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllTeams, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }
}