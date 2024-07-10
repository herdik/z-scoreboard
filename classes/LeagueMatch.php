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
    public static function createLeagueMatch($connection, $rematch, $number_of_rounds_in_group, $matches_in_rounds_by_group, $player_in_group, $league_id, $choosed_game, $league_group, $playing_format) {

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
                    if ($playing_format === "single") {
                        $player_id_1 = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id"];
                        $player_id_2 = $player_in_group[$y]["player_Id"];
                    } elseif ($playing_format === "doubles") {
                        $player_id_1A = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id_doubles_1"];
                        $player_id_1B = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id_doubles_2"];
                        $player_id_2A = $player_in_group[$y]["player_Id_doubles_1"];
                        $player_id_2B = $player_in_group[$y]["player_Id_doubles_2"];
                    } elseif ($playing_format === "teams") {
                        $team_id_1 = $player_in_group[(count($player_in_group) - 1) - $y]["team_id"];
                        $team_id_2 = $player_in_group[$y]["team_id"];
                    }
                } else {
                    if ($playing_format === "single") {
                        $player_id_1 = $player_in_group[$y]["player_Id"];
                        $player_id_2 = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id"];
                    } elseif ($playing_format === "doubles") {
                        $player_id_1A = $player_in_group[$y]["player_Id_doubles_1"];
                        $player_id_1B = $player_in_group[$y]["player_Id_doubles_2"];
                        $player_id_2A = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id_doubles_1"];
                        $player_id_2B = $player_in_group[(count($player_in_group) - 1) - $y]["player_Id_doubles_2"];
                    } elseif ($playing_format === "teams") {
                        $team_id_1 = $player_in_group[$y]["team_id"];
                        $team_id_2 = $player_in_group[(count($player_in_group) - 1) - $y]["team_id"];
                    }
                }

                // all players in current match are inavtive, that mean player "0"
                $correct_match = false;

                // setting for league match, means league match is not finish, league match is prepare to be active match, if is true that means match contain player_Id = "0"
                $match_waiting = $match_started = $match_finished = false;

                if ($playing_format === "single"){
                    // inside sql scheme single
                    $sql_players = "player_id_1, score_1, player_id_2, score_2";
                    $sql_players_values = ":player_id_1, :score_1, :player_id_2, :score_2";
                    if ($player_id_1 || $player_id_2){
                        // at least one player is active
                        $correct_match = true;
                        if ((!$player_id_1) || (!$player_id_2)){
                            $match_waiting = $match_started = $match_finished = true;
                        }
                    }
                } elseif ($playing_format === "doubles"){
                    // inside sql scheme doubles
                    $sql_players = "player_id_1A, player_id_1B, score_1, player_id_2A, player_id_2B, score_2";
                    $sql_players_values = ":player_id_1A, :player_id_1B, :score_1, :player_id_2A, :player_id_2B, :score_2";
                    if ($player_id_1A || $player_id_1B || $player_id_2A || $player_id_2B){
                        // at least one player is active
                        $correct_match = true;
                        if (!$player_id_1A || !$player_id_1B || !$player_id_2A || !$player_id_2B){
                            $match_waiting = $match_started = $match_finished = true;
                        }
                    }
                } elseif ($playing_format === "teams"){
                    // inside sql scheme teams
                    $sql_players = "team_id_1, score_1, team_id_2, score_2";
                    $sql_players_values = ":team_id_1, :score_1, :team_id_2, :score_2";
                    if ($team_id_1 || $team_id_2){
                        // at least one player is active
                        $correct_match = true;
                        if ((!$team_id_1) || (!$team_id_2)){
                            $match_waiting = $match_started = $match_finished = true;
                        }
                    }
                }
                
                // if match is active, make sql statement
                if ($correct_match){
                    
                    // sql scheme
                    $sql = "INSERT INTO league_match_$playing_format (league_id, league_group, $sql_players, round_number, choosed_game, table_number, match_waiting, match_started, match_finished)
                    VALUES (:league_id, :league_group, $sql_players_values, :round_number, :choosed_game, :table_number, :match_waiting, :match_started, :match_finished)";

                    // prepare data to send to Database
                    $stmt = $connection->prepare($sql);

                    // filling and bind values will be execute to Database
                    $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
                    $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
                    if ($playing_format === "single"){
                        $stmt->bindValue(":player_id_1", $player_id_1, PDO::PARAM_INT);
                        $stmt->bindValue(":score_1", 0, PDO::PARAM_INT);
                        $stmt->bindValue(":player_id_2", $player_id_2, PDO::PARAM_INT);
                        $stmt->bindValue(":score_2", 0, PDO::PARAM_INT);
                    } elseif ($playing_format === "doubles"){
                        $stmt->bindValue(":player_id_1A", $player_id_1A, PDO::PARAM_INT);
                        $stmt->bindValue(":player_id_1B", $player_id_1B, PDO::PARAM_INT);
                        $stmt->bindValue(":score_1", 0, PDO::PARAM_INT);
                        $stmt->bindValue(":player_id_2A", $player_id_2A, PDO::PARAM_INT);
                        $stmt->bindValue(":player_id_2B", $player_id_2B, PDO::PARAM_INT);
                        $stmt->bindValue(":score_2", 0, PDO::PARAM_INT);
                    } elseif ($playing_format === "teams"){
                        $stmt->bindValue(":team_id_1", $team_id_1, PDO::PARAM_INT);
                        $stmt->bindValue(":score_1", 0, PDO::PARAM_INT);
                        $stmt->bindValue(":team_id_2", $team_id_2, PDO::PARAM_INT);
                        $stmt->bindValue(":score_2", 0, PDO::PARAM_INT);
                    }
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
    public static function getAllLeagueMatches($connection, $league_id, $league_group, $playing_format){

        if ($playing_format === "single"){
            $sql_columns = "league_match_single.*, t1.first_name AS player1_firstname, t1.second_name AS player1_second_name, t1.country AS player1_country, t1.player_club AS player1_club, t1.player_Image as player1_image, t2.first_name AS player2_firstname, t2.second_name AS player2_second_name, t2.country AS player2_country, t2.player_club AS player2_club, t2.player_Image as player2_image
            FROM league_match_single
            INNER JOIN player_user AS t1
                ON t1.player_Id = league_match_single.player_id_1
            INNER JOIN player_user AS t2
                ON t2.player_Id = league_match_single.player_id_2";

        } elseif ($playing_format === "doubles"){
            $sql_columns = "league_match_doubles.*, t1A.first_name AS player1A_firstname, t1A.second_name AS player1A_second_name, t1A.country AS player1A_country, t1A.player_club AS player1A_club, t1A.player_Image as player1A_image, t1B.first_name AS player1B_firstname, t1B.second_name AS player1B_second_name, t1B.country AS player1B_country, t1B.player_club AS player1B_club, t1B.player_Image as player1B_image, t2A.first_name AS player2A_firstname, t2A.second_name AS player2A_second_name, t2A.country AS player2A_country, t2A.player_club AS player2A_club, t2A.player_Image as player2A_image, t2B.first_name AS player2B_firstname, t2B.second_name AS player2B_second_name, t2B.country AS player2B_country, t2B.player_club AS player2B_club, t2B.player_Image as player2B_image
            FROM league_match_doubles
            INNER JOIN player_user AS t1A
                ON t1A.player_Id = league_match_doubles.player_id_1A
            INNER JOIN player_user AS t1B
                ON t1B.player_Id = league_match_doubles.player_id_1B
            INNER JOIN player_user AS t2A
                ON t2A.player_Id = league_match_doubles.player_id_2A
            INNER JOIN player_user AS t2B
                ON t2B.player_Id = league_match_doubles.player_id_2B";
        } elseif ($playing_format === "teams"){
            $sql_columns = "league_match_teams.*, t1.team_name AS team1_name, t1.team_country AS team1_country, t1.team_image as team1_image, t2.team_name AS team2_name, t2.team_country AS team2_country, t2.team_image as team2_image
            FROM league_match_teams
            INNER JOIN team_user AS t1
                ON t1.team_id = league_match_teams.team_id_1
            INNER JOIN team_user AS t2
                ON t2.team_id = league_match_teams.team_id_2";
        }

        $sql = "SELECT $sql_columns
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



    /**
     *
     * RETURN BOOLEAN IF MATCH GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     
     * @param integer $match_id - match_id for one match
     * @param integer $match_status - match_started/match_waiting/match_finished
     * @param boolean $match_status_value - true or false
     * 
     * @return boolean if update is successful
     */
    public static function updateLeagueMatch($connection, $match_id, $btn_value){

        if ($btn_value === "Zapnúť"){
            $match_status = "match_waiting";
            $match_status_value = true;
        }

        $sql = "UPDATE league_match_single
                SET $match_status = $match_status_value
                WHERE match_id = :match_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);
        // $stmt->bindValue(":match_status", $match_status, PDO::PARAM_STR);
        // $stmt->bindValue(":match_status_value", $match_status_value, PDO::PARAM_BOOL);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update league_group v konkrétnej lige sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateLeagueMatch, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
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
     * @return array array of match
     */
    public static function getLeagueMatch($connection, $match_id, $playing_format){

        if ($playing_format === "single"){
            $sql_columns = "league_match_single.*, t1.first_name AS player1_firstname, t1.second_name AS player1_second_name, t1.country AS player1_country, t1.player_club AS player1_club, t1.player_Image as player1_image, t2.first_name AS player2_firstname, t2.second_name AS player2_second_name, t2.country AS player2_country, t2.player_club AS player2_club, t2.player_Image as player2_image
            FROM league_match_single
            INNER JOIN player_user AS t1
                ON t1.player_Id = league_match_single.player_id_1
            INNER JOIN player_user AS t2
                ON t2.player_Id = league_match_single.player_id_2";

        } elseif ($playing_format === "doubles"){
            $sql_columns = "league_match_doubles.*, t1A.first_name AS player1A_firstname, t1A.second_name AS player1A_second_name, t1A.country AS player1A_country, t1A.player_club AS player1A_club, t1A.player_Image as player1A_image, t1B.first_name AS player1B_firstname, t1B.second_name AS player1B_second_name, t1B.country AS player1B_country, t1B.player_club AS player1B_club, t1B.player_Image as player1B_image, t2A.first_name AS player2A_firstname, t2A.second_name AS player2A_second_name, t2A.country AS player2A_country, t2A.player_club AS player2A_club, t2A.player_Image as player2A_image, t2B.first_name AS player2B_firstname, t2B.second_name AS player2B_second_name, t2B.country AS player2B_country, t2B.player_club AS player2B_club, t2B.player_Image as player2B_image
            FROM league_match_doubles
            INNER JOIN player_user AS t1A
                ON t1A.player_Id = league_match_doubles.player_id_1A
            INNER JOIN player_user AS t1B
                ON t1B.player_Id = league_match_doubles.player_id_1B
            INNER JOIN player_user AS t2A
                ON t2A.player_Id = league_match_doubles.player_id_2A
            INNER JOIN player_user AS t2B
                ON t2B.player_Id = league_match_doubles.player_id_2B";
        } elseif ($playing_format === "teams"){
            $sql_columns = "league_match_teams.*, t1.team_name AS team1_name, t1.team_country AS team1_country, t1.team_image as team1_image, t2.team_name AS team2_name, t2.team_country AS team2_country, t2.team_image as team2_image
            FROM league_match_teams
            INNER JOIN team_user AS t1
                ON t1.team_id = league_match_teams.team_id_1
            INNER JOIN team_user AS t2
                ON t2.team_id = league_match_teams.team_id_2";
        }

        $sql = "SELECT $sql_columns
                WHERE match_id = :match_id";

        $stmt = $connection->prepare($sql);

        $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one league
                return $stmt->fetch();
            } else {
                throw new Exception ("Príkaz pre získanie ligového zápasu sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getLeagueMatch, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

}