<?php
class UndermatchSingle {


    /**
    *
    * RETURN BOOLEAN if UNDERMATCH ARE REGISTERED IN UNDERMATCH LEAGUE SINGLE - DATABASE
    * 
    * @param integer $undermatch_number - submatch order in current team match
    * @param integer $match_id - id for one match in league
    * @param integer $league_id - id for one league
    * @param integer $league_group - id for one league group
    * @param integer $player_id_1 - id for one player to reg in league
    * @param integer $score_1 - score for player1 in league
    * @param integer $player_id_2 - id for second player  in league
    * @param integer $score_2 - score for player2 in league
    * @param integer $choosed_game - choosed game in undermatch
    * @param integer $table_number - table_number in undermatch
    * @param integer $match_waiting - status match_waiting in undermatch
    * @param integer $match_started - status match_started in undermatch
    * @param integer $match_finished - status match_finished in undermatch
    * @param integer $match_visibility - undermatch will be visible or not
    * @param integer $undermatch_type - type single or doubles undermatch
    * 
    * @return boolean if creating is successful
    */
    public static function createLeagueUnderMatch($connection, $undermatch_number, $match_id, $league_id, $league_group, $player_id_1, $score_1, $player_id_2, $score_2, $choosed_game, $table_number, $match_waiting, $match_started, $match_finished, $match_visibility, $undermatch_type) {

                    
        // sql scheme
        $sql = "INSERT INTO under_match_teams_single (undermatch_number, match_id, league_id, league_group, player_id_1, score_1, player_id_2, score_2, choosed_game, table_number, match_waiting, match_started, match_finished, match_visibility, undermatch_type)
        VALUES (:undermatch_number, :match_id, :league_id, :league_group, :player_id_1, :score_1, :player_id_2, :score_2, :choosed_game, :table_number, :match_waiting, :match_started, :match_finished, :match_visibility, :undermatch_type)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":undermatch_number", $undermatch_number, PDO::PARAM_INT);
        $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        
        $stmt->bindValue(":player_id_1", $player_id_1, PDO::PARAM_INT);
        $stmt->bindValue(":score_1", 0, PDO::PARAM_INT);
        $stmt->bindValue(":player_id_2", $player_id_2, PDO::PARAM_INT);
        $stmt->bindValue(":score_2", 0, PDO::PARAM_INT);
    
        $stmt->bindValue(":choosed_game", $choosed_game, PDO::PARAM_INT);
        $stmt->bindValue(":table_number", false, PDO::PARAM_BOOL);
        $stmt->bindValue(":match_waiting", $match_waiting, PDO::PARAM_BOOL);
        $stmt->bindValue(":match_started", $match_started, PDO::PARAM_BOOL);
        $stmt->bindValue(":match_finished", $match_finished, PDO::PARAM_BOOL);
        $stmt->bindValue(":match_visibility", $match_visibility, PDO::PARAM_BOOL);
        $stmt->bindValue(":undermatch_type", $undermatch_type, PDO::PARAM_STR);

        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Registrovanie nového podzápasu do ligy sa nepodarilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createLeagueUnderMatch\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
            
    }

}