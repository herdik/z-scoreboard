<?php

class OptionDoubles {


    /**
     *
     * ADD PLAYER/USER TO DATABASE
     *
     * @param object $connection - database connection
     
     * @param int $match_id - match_id for current team match
     * @param int $league_id - league_id for league
     * @param int $league_group - league_group
     * @param int $player_id_1 - player_id one in doubles
     * @param int $player_id_2 - player_id two in doubles
     * @param int $current_team_id - team_id for player club
     * @param bool $option_status - option_status true for unused option and false for used option
     *
     * @return void $player_id - id for player
     * 
     */
    public static function createOptionDoubles($connection, $match_id, $league_id, $league_group, $player_id_1, $player_id_2, $current_team_id, $option_status) {

        // sql scheme
        $sql = "INSERT INTO undermatch_option_doubles (match_id, league_id, league_group, player_id_1, player_id_2, current_team_id, option_status)
        VALUES (:match_id, :league_id, :league_group, :player_id_1, :player_id_2, :current_team_id, :option_status)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":player_id_1", $player_id_1, PDO::PARAM_INT);
        $stmt->bindValue(":player_id_2", $player_id_2, PDO::PARAM_INT);
        $stmt->bindValue(":current_team_id", $current_team_id, PDO::PARAM_INT);
        $stmt->bindValue(":option_status", $option_status, PDO::PARAM_BOOL);
        

        
        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Vytvorenie novej doubles možnosti pre podzápas sa neuskutočnilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createOptionDoubles\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }
}