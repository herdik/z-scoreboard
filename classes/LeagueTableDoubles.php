<?php

class LeagueTableDoubles {


    /**
     *
     * RETURN BOOLEAN if PLAYER ARE REGISTERED IN LEAGUE - DATABASE
     *
     * @param integer $league_id - id for one league
     * @param integer $league_group - group for one league
     * @param integer $player_id - id for one player to reg in league
     * 
     * @return boolean if creating is successful
     */
    public static function createLeagueTableDoubles($connection, $league_id, $league_group, $player_id_doubles_1, $player_id_doubles_2) {

        // sql scheme
        $sql = "INSERT INTO league_table_doubles (league_id, league_group, player_id_doubles_1, player_id_doubles_2, played_matches, winnings_matches, lost_matches, score_game_win, score_game_loss, difference, mutual_match_points, points)
        VALUES (:league_id, :league_group, :player_id_doubles_1, :player_id_doubles_2, :played_matches, :winnings_matches, :lost_matches, :score_game_win, :score_game_loss, :difference, :mutual_match_points, :points)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":player_id_doubles_1", $player_id_doubles_1, PDO::PARAM_INT);
        $stmt->bindValue(":player_id_doubles_2", $player_id_doubles_2, PDO::PARAM_INT);
        $stmt->bindValue(":played_matches", 0, PDO::PARAM_INT);
        $stmt->bindValue(":winnings_matches", 0, PDO::PARAM_INT);
        $stmt->bindValue(":lost_matches", 0, PDO::PARAM_INT);
        $stmt->bindValue(":score_game_win", 0, PDO::PARAM_INT);
        $stmt->bindValue(":score_game_loss", 0, PDO::PARAM_INT);
        $stmt->bindValue(":difference", 0, PDO::PARAM_STR);
        $stmt->bindValue(":mutual_match_points", 0, PDO::PARAM_INT);
        $stmt->bindValue(":points", 0, PDO::PARAM_INT);

        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Vytvorenie nového hráča do tabuľky konkrétnej ligy podľa skupiny sa nepodarilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createLeagueTableDoubles\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL REGISTERED PLAYERS TO LEAGUE TABLE FROM LEAGUE BY SPECIPIC GROUP FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllFromLeagueTableDoubles($connection, $league_id, $league_group){

        $sql = "SELECT league_table_doubles.*, t1.first_name AS player1_firstname, t1.second_name AS player1_second_name, t1.country AS player1_country, t1.player_club AS player1_club, t1.player_Image as player1_image, t2.first_name AS player2_firstname, t2.second_name AS player2_second_name, t2.country AS player2_country, t2.player_club AS player2_club, t2.player_Image as player2_image 
        FROM league_table_doubles
        INNER JOIN player_user AS t1
            ON t1.player_Id = league_table_doubles.player_id_doubles_1
        INNER JOIN player_user AS t2
            ON t2.player_Id = league_table_doubles.player_id_doubles_2
        WHERE league_id = :league_id AND league_group = :league_group
        ORDER BY points DESC, mutual_match_points DESC, difference DESC, score_game_win DESC, player1_second_name ASC, player1_firstname ASC";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o hráčovi z tabuľky výsledkov z konkrétnej ligy príslušné k špecifickej skupine sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllFromLeagueTableDoubles, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

}