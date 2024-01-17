<?php

class LeaguePlayer {


    /**
     *
     * RETURN BOOLEAN if PLAYER ARE REGISTERED IN LEAGUE - DATABASE
     *
     * @param integer $league_id - id for one league
     * @param integer $player_Id - id for one player to reg in league
     * 
     * @return boolean if update is successful
     */
    public static function createLeaguePlayer($connection, $league_id, $player_Id) {

        // sql scheme
        $sql = "INSERT INTO list_of_players_league (league_id, player_Id)
        VALUES (:league_id, :player_Id)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Registrovanie nového hráča do ligy sa nepodarilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createLeaguePlayer\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * RETURN ALL REGISTERED PLAYERS IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeaguePlayers($connection, $league_id, $columns = "*"){
        $sql = "SELECT list_of_players_league.$columns, player_user.first_name, player_user.second_name, player_user.player_Image, player_user.country
                FROM list_of_players_league
                INNER JOIN player_user ON list_of_players_league.player_Id = player_user.player_Id
                WHERE league_id = :league_id";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o hráčoch z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeaguePlayers, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



}