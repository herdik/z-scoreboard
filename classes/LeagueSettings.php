<?php

class LeagueSettings {


    /**
     *
     * ADD LEAGUE TO DATABASE
     *
     * @param object $connection - database connection
     * @param integer $league_id - league id
     * @param boolean $rematch - oneway match or rematch match
     * @param integer $race_to - playing to
     * @param integer $count_tables - how many tables in plaing area
     * @param integer $count_groups - how many groups will play all players
     * 
     * @return integer $league_id - id for league
     * 
     */
    public static function createLeagueSettings($connection, $league_id, $rematch, $race_to, $count_tables, $count_groups) {

        
        // sql scheme
        $sql = "INSERT INTO league_settings (league_id, rematch, race_to, count_tables, count_groups)
        VALUES (:league_id, :rematch, :race_to, :count_tables, :count_groups)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":rematch", $rematch, PDO::PARAM_BOOL);
        $stmt->bindValue(":race_to", $race_to, PDO::PARAM_INT);
        $stmt->bindValue(":count_tables", $count_tables, PDO::PARAM_INT);
        $stmt->bindValue(":count_groups", $count_groups, PDO::PARAM_INT);

        
        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Vytvorenie nových ligových nastavení sa neuskutočnilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createLeagueSettings\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ONE SELECTED REGISTERED LEAGUE SETTINGS FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param string $league_id - league_id
     *
     * @return array array of objects, one object mean one league
     */
    public static function getLeagueSettings($connection, $league_id, $columns = "*"){
        $sql = "SELECT $columns
                FROM league_settings
                WHERE league_id = :league_id";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one league
                return $stmt->fetch();
            } else {
                throw new Exception ("Príkaz pre získanie ligových nastavení sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getLeagueSettings, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * RETURN ONE LEAGUE SETTINGS FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - league_id
     * 
     * @return boolean if update is successful
     */
    public static function updateLeagueSettings($connection, $league_id, $rematch, $race_to, $count_tables, $count_groups){
        $sql = "UPDATE league_settings
                SET rematch = :rematch,
                    race_to = :race_to, 
                    count_tables = :count_tables, 
                    count_groups = :count_groups 
                WHERE league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":rematch", $rematch, PDO::PARAM_BOOL);
        $stmt->bindValue(":race_to", $race_to, PDO::PARAM_INT);
        $stmt->bindValue(":count_tables", $count_tables, PDO::PARAM_INT);
        $stmt->bindValue(":count_groups", $count_groups, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update všetkých ligových nastavení o lige sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateLeagueSettings, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

}