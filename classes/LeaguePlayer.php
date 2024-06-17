<?php

class LeaguePlayer {


    /**
     *
     * RETURN BOOLEAN if PLAYER ARE REGISTERED IN LEAGUE - DATABASE
     *
     * @param integer $league_id - id for one league
     * @param integer $player_Id - id for one player to reg in league
     * 
     * @return boolean if creating is successful
     */
    public static function createLeaguePlayer($connection, $league_id, $player_Id, $league_group = NULL) {

        // sql scheme
        $sql = "INSERT INTO list_of_players_league_single (league_id, player_Id, league_group)
        VALUES (:league_id, :player_Id, :league_group)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

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
    public static function getAllLeaguePlayers($connection, $league_id, $player_zero, $columns = "list_of_players_league_single.*, player_user.first_name, player_user.second_name, player_user.player_Image, player_user.country, player_user.player_club"){
        if ($player_zero === TRUE){
            $add_text = " AND player_user.player_Id != 0";
        } else {
            $add_text = NULL;
        }
    
        $sql = "SELECT $columns
                FROM list_of_players_league_single
                INNER JOIN player_user ON list_of_players_league_single.player_Id = player_user.player_Id
                WHERE league_id = :league_id $add_text
                ORDER BY league_group";

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



    /**
     *
     * RETURN ALL REGISTERED PLAYERS IN LEAGUE FROM DATABASE ACCORDING LEAGUE GROUP
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeaguePlayersByGroup($connection, $league_id, $league_group){
    
        $sql = "SELECT *
                FROM list_of_players_league_single
                WHERE league_id = :league_id AND league_group = :league_group
                ";
        // ORDER BY RAND ()
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT); 
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT); 

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

    /**
     *
     * DELETE ONE PLAYER IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $player_Id - id for registered player in League
     * 
     * @return boolean if delete is successful
     */
    public static function deleteLeaguePlayer($connection, $league_id, $player_Id){
        $sql = "DELETE 
                FROM list_of_players_league_single
                WHERE player_Id = :player_Id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o hráčovi z konkretnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deleteLeaguePlayer, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL PLAYERS TO REGISTER FROM DATABASE WHO ARE NOT IN SPECIFIC LEAGUE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeaguePlayersNotRegistered($connection, $league_id, $columns = "player_Id, second_name, first_name"){
        $sql = "SELECT $columns
                FROM player_user
                WHERE player_Id != 0 AND player_Id NOT IN
                    (SELECT player_user.player_Id
                FROM player_user
                INNER JOIN list_of_players_league_single ON list_of_players_league_single.player_Id = player_user.player_Id
                WHERE list_of_players_league_single.league_id = :league_id)
                ORDER BY second_name";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých hráčov o hráčoch z konkrétnej ligy, ktorí tam nie sú registrovaní sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeaguePlayersNotRegistered, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * RETURN BOOLEAN IF PLAYER´S GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     
     * @param integer $league_group - league_group for one user
     * @param integer $player_Id - id for one user
     * 
     * @return boolean if update is successful
     */
    public static function updateLeaguePlayer($connection, $league_group, $player_Id, $league_id){
        $sql = "UPDATE list_of_players_league_single
                SET league_group = :league_group
                WHERE player_Id = :player_Id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update league_group v v konkrétnej lige sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateLeaguePlayer, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL GROUPS FOR EACH PLAYER REGISTERED PLAYERS IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @return array array of objects, one object mean one player
     */
    public static function getPlayerGroupInLeague($connection, $league_id){
        $sql = "SELECT COUNT(*)
                FROM list_of_players_league_single
                WHERE league_id = :league_id AND league_group IS NULL";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchColumn();
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát skupinách pre hráčov z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getPlayerGroupInLeague, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN BOOLEAN IF PLAYER´S GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_group - league_group for one user
     * @param integer $player_in_league_id - id for one user in league
     * 
     * @return boolean if update is successful
     */
    public static function updateSpecificLeagueGroup($connection, $league_group, $player_in_league_id, $league_id){
        $sql = "UPDATE list_of_players_league_single
                SET league_group = :league_group
                WHERE player_in_league_id = :player_in_league_id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":player_in_league_id", $player_in_league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update league_group v v konkrétnej lige sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateSpecificLeagueGroup, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL LEAGUEPLAYER FROM DATABASE WITH 0 LEAGUE GROUP - INACTIVE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $league_group - id for league group
     * @return integer player ID for one player in league
     */
    public static function getInactiveLeaguePlayers($connection, $league_id, $league_group){
        $sql = "SELECT player_in_league_id
                FROM list_of_players_league_single
                WHERE league_group = :league_group AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw Exception ("Príkaz pre získanie všetkých dát o hráčovi v lige a skupine sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getInactiveLeaguePlayers, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    /**
     *
     * RETURN INTEGER FROM DATABASE HOW MANY ACTIVE PLAYERS ARE IN GROUP
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $league_group - id for league group
     * @return integer player ID for one player in league
     */
    public static function countActiveLeaguePlayersInGroup($connection, $league_id, $league_group){
        $sql = "SELECT COUNT(*)
                FROM list_of_players_league_single
                WHERE league_group = :league_group AND league_id = :league_id";
    

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchColumn();
            } else {
                throw new Exception ("Príkaz pre získanie počtu hráčov skupine z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii countActiveLeaguePlayersInGroup, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }

    }


    /**
     *
     * RETURN INTEGER - HOW MANY GROUPS HAVE ON ACTIVE LEAGUE
     *
     * @param object $connection - connection to database
     * @param integer $league_group - league_group for one user
     * 
     * @return integer $league_group - highest number of groups
     */
    public static function getNumberOfGroups($connection, $league_id){
        $sql = "SELECT league_group
                FROM list_of_players_league_single
                WHERE league_id = :league_id
                ORDER BY league_group DESC
                LIMIT 1";
    

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchColumn();
            } else {
                throw new Exception ("Príkaz pre získanie počtu skupín z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getNumberOfGroups, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    /**
     *
     * DELETE ONE PLAYER IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $player_in_league_id - id for registered player in League
     * 
     * @return boolean if delete is successful
     */
    public static function deleteSpecLeaguePlayer($connection, $league_id, $player_in_league_id){
        $sql = "DELETE 
        FROM list_of_players_league_single
        WHERE player_in_league_id = :player_in_league_id AND league_id = :league_id";


        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_in_league_id", $player_in_league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o hráčovi z konkretnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deleteSpecLeaguePlaye, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


}
