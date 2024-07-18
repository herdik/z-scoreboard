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

}