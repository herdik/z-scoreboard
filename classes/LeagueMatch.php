<?php
class LeagueMatch {


    /**
    *
    * RETURN BOOLEAN if PLAYER ARE REGISTERED IN LEAGUE - DATABASE
    *
    * @param integer $league_id - id for one league
    * @param integer $player_Id - id for one player to reg in league
    * 
    * @return boolean if creating is successful
    */
    public static function createLeagueMatch($connection, $rematch, $number_of_rounds_in_group, $matches_in_rounds_by_group, $player_in_group, $league_id, $choosed_game, $league_group) {
        
        $league_pairs = array();

        if ($rematch){
            $number_of_rounds_in_group = $number_of_rounds_in_group * 2;
        }
        for ($round = 1; $round <= $number_of_rounds_in_group; $round++){
            // echo "<br>";
            // echo $round;
            // echo " .kolo";
            
            for ($y = 0; $y < $matches_in_rounds_by_group; $y++){
                if ($rematch && $round > $number_of_rounds_in_group / 2 ){
                    $player_id_1 = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id"];
                    $player_id_2 = $player_in_group[$y]["player_Id"];
                } else {
                    $player_id_1 = $player_in_group[$y]["player_Id"];
                    $player_id_2 = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id"];
                }
                
                if (($player_id_1) || ($player_id_2)){
                    if ((!$player_id_1) || (!$player_id_2)){
                        $match_waiting = $match_started = $match_finished = true;
                    } else {
                        $match_waiting = $match_started = $match_finished = false;
                    }
                    // echo "<br>";
                    // echo "Hráč č.1";
                    // echo "<br>";
                    // echo $player_id_1;
                    // echo "<br>";
                    // echo "Hráč č.2";
                    // echo "<br>";
                    // echo $player_id_2;
                    // echo "<br>";


                    // sql scheme
                    $sql = "INSERT INTO league_match_single (league_id, league_group, player_id_1, score_1, player_id_2, score_2, round_number, choosed_game, table_number, match_waiting, match_started, match_finished)
                    VALUES (:league_id, :league_group, :player_id_1, :score_1, :player_id_2, :score_2, :round_number, :choosed_game, :table_number, :match_waiting, :match_started, :match_finished)";

                    // prepare data to send to Database
                    $stmt = $connection->prepare($sql);

                    // filling and bind values will be execute to Database
                    $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
                    $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
                    $stmt->bindValue(":player_id_1", $player_id_1, PDO::PARAM_INT);
                    $stmt->bindValue(":score_1", 0, PDO::PARAM_INT);
                    $stmt->bindValue(":player_id_2", $player_id_2, PDO::PARAM_INT);
                    $stmt->bindValue(":score_2", 0, PDO::PARAM_INT);
                    $stmt->bindValue(":round_number", $round, PDO::PARAM_INT);
                    $stmt->bindValue(":choosed_game", $choosed_game, PDO::PARAM_INT);
                    $stmt->bindValue(":table_number", false, PDO::PARAM_BOOL);
                    $stmt->bindValue(":match_waiting", $match_waiting, PDO::PARAM_BOOL);
                    $stmt->bindValue(":match_started", $match_started, PDO::PARAM_BOOL);
                    $stmt->bindValue(":match_finished", $match_finished, PDO::PARAM_BOOL);

                    try {
                        // execute all data to SQL Database to table player_user
                        if($stmt->execute()){
                            $league_pairs[] = true;
                        } else {
                            throw new Exception ("Registrovanie nového zápasu do ligy sa nepodarilo");
                        }
                    } catch (Exception $e) {
                        error_log("Chyba pri funkcii createLeagueMatch\n", 3, "../errors/error.log");
                        echo "Výsledná chyba je: " . $e->getMessage();
                    }

                }
            
            }
            // $first_player = array_shift($player_in_group);
            // $last_player = array_pop($player_in_group); 
            array_unshift($player_in_group, array_shift($player_in_group), array_pop($player_in_group));
        }
        if(count(array_unique($league_pairs)) === 1){
            return current($league_pairs);
        } else {
            return false;
        }
            
    }

    /**
     *
     * RETURN ALL REGISTERED MATCHES IN LEAGUE BY SPECIPIC GROUP FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one match
     */
    public static function getAllLeagueMatches($connection, $league_id, $league_group, $columns="league_match_single.*, t1.first_name AS player1_firstname, t1.second_name AS player1_second_name, t1.country AS player1_country, t1.player_club AS player1_club, t1.player_Image as player1_image, t2.first_name AS player2_firstname, t2.second_name AS player2_second_name, t2.country AS player2_country, t2.player_club AS player2_club, t2.player_Image as player2_image"){
        
        $sql = "SELECT $columns
                FROM league_match_single
                INNER JOIN player_user AS t1
	                ON t1.player_Id = league_match_single.player_id_1
                INNER JOIN player_user AS t2
                    ON t2.player_Id = league_match_single.player_id_2
                WHERE league_id = :league_id AND league_group = :league_group
                ORDER BY round_number";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o zápasoch z konkrétnej ligy príslušné k špecifickej skupine sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeagueMatches, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

}