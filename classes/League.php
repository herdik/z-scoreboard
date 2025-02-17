<?php

class League {


    /**
     *
     * ADD LEAGUE TO DATABASE
     *
     * @param object $connection - database connection
     * @param string $league_name - leagueName
     * @param string $playing_format - single/doubles/teams
     * @param string $date_of_event - Date of Event
     * @param int    $season - season
     * @param string $discipline - 8, 9, 10, 14 - ball
     * @param string $venue - place of leaugue
     * @param string $manager - logged user
     * 
     * @return integer $league_id - id for league
     * 
     */
    public static function createLeague($connection, $league_name, $category, $playing_format, $date_of_event, $season, $discipline, $venue, $type, $manager, $manager_id) {

        
        // sql scheme
        $sql = "INSERT INTO league (league_name, category, playing_format, date_of_event, season, discipline, venue, type, manager, manager_id, active_league)
        VALUES (:league_name, :category, :playing_format, :date_of_event, :season, :discipline, :venue, :type, :manager, :manager_id, :active_league)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_name", $league_name, PDO::PARAM_STR);
        $stmt->bindValue(":category", $category, PDO::PARAM_STR);
        $stmt->bindValue(":playing_format", $playing_format, PDO::PARAM_STR);
        $stmt->bindValue(":date_of_event", $date_of_event, PDO::PARAM_STR);
        $stmt->bindValue(":season", $season, PDO::PARAM_STR);
        $stmt->bindValue(":discipline", $discipline, PDO::PARAM_INT);
        $stmt->bindValue(":venue", $venue, PDO::PARAM_STR);
        $stmt->bindValue(":type", $type, PDO::PARAM_STR);
        $stmt->bindValue(":manager", $manager, PDO::PARAM_STR);
        $stmt->bindValue(":manager_id", $manager_id, PDO::PARAM_INT);
        $stmt->bindValue(":active_league", false, PDO::PARAM_BOOL);

        
        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                $league_id = $connection->lastInsertId();
                return $league_id;
            } else {
                throw new Exception ("Vytvorenie novej ligy sa neuskutočnilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createLeague\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN BOOLEAN FROM DATABASE AFTER UPDATED LEAGUE - active league infos
     *
     * @param object $connection - database connection
     * @param boolean $active_league - league matches are created
     * @param int $league_id - id for league
     * 
     * @return boolean if update is successful
     */
    public static function updateLeague($connection, $league_id, $active_league){
        $sql = "UPDATE league
                SET active_league = :active_league
                WHERE league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":active_league", $active_league, PDO::PARAM_BOOL);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update aktívnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateLeague, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL REGISTERED LEAGUES FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one league
     */
    public static function getAllLeagues($connection, $columns = "*"){
        $sql = "SELECT $columns
                FROM league";

        $stmt = $connection->prepare($sql);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o ligách sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeagues, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    /**
     *
     * RETURN ONE SELECTED REGISTERED LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param string $league_id - league_id
     *
     * @return array array of objects, one object mean one league
     */
    public static function getLeague($connection, $league_id, $columns = "*"){
        $sql = "SELECT $columns
                FROM league
                WHERE league_id = :league_id";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one league
                return $stmt->fetch();
            } else {
                throw new Exception ("Príkaz pre získanie ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getLeague, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }
}