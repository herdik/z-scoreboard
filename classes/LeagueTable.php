<?php

class LeagueTable {


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
    public static function createLeagueTable($connection, $league_id, $league_group, $player_id) {

        // sql scheme
        $sql = "INSERT INTO league_table (league_id, league_group, player_id, played_matches, winnings_matches, lost_matches, score_game_win, score_game_loss, difference, mutual_match_points, points)
        VALUES (:league_id, :league_group, :player_id, :played_matches, :winnings_matches, :lost_matches, :score_game_win, :score_game_loss, :difference, :mutual_match_points, :points)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
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
            error_log("Chyba pri funkcii createLeagueTable\n", 3, "../errors/error.log");
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
    public static function getAllFromLeagueTable($connection, $league_id, $league_group, $playing_format){

        if ($playing_format === "single"){
            $sql_columns = "league_table.*, t1.first_name AS player_firstname, t1.second_name AS player_second_name, t1.country AS player_country, t1.player_club AS player_club, t1.player_Image as player_image
            FROM league_table
            INNER JOIN player_user AS t1
                ON t1.player_Id = league_table.player_id";

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
                ORDER BY points DESC, mutual_match_points DESC, difference DESC, score_game_win DESC, player_second_name ASC, player_firstname ASC";

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
            error_log("Chyba pri funckii getAllFromLeagueTable, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

}