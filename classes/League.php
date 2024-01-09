<?php

class League {


    /**
     *
     * ADD PLAYER/USER TO DATABASE
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
    public static function createLeague($connection, $league_name, $playing_format, $date_of_event, $season, $discipline, $venue, $manager) {

        
        // sql scheme
        $sql = "INSERT INTO league (league_name, playing_format, date_of_event, season, discipline, venue, manager)
        VALUES (:league_name, :playing_format, :date_of_event, :season, :discipline, :venue, :manager)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_name", $league_name, PDO::PARAM_STR);
        $stmt->bindValue(":playing_format", $playing_format, PDO::PARAM_STR);
        $stmt->bindValue(":date_of_event", $date_of_event, PDO::PARAM_STR);
        $stmt->bindValue(":season", $season, PDO::PARAM_STR);
        $stmt->bindValue(":discipline", $discipline, PDO::PARAM_INT);
        $stmt->bindValue(":venue", $venue, PDO::PARAM_STR);
        $stmt->bindValue(":manager", $manager, PDO::PARAM_STR);

        
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
}