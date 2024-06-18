<?php

class LeaguePlayerDoubles {


    /**
     *
     * RETURN BOOLEAN if DOUBLES ARE REGISTERED IN LEAGUE - DATABASE
     *
     * @param integer $league_id - id for one league
     * @param integer $player_Id_doubles_1 - id for first player to reg in league
     * @param integer $player_Id_doubles_2 - id for second player to reg in league
     * 
     * @return boolean if creating is successful
     */
    public static function createLeagueDoubles($connection, $league_id, $player_Id_doubles_1, $player_Id_doubles_2, $league_group = NULL) {

        // sql scheme
        $sql = "INSERT INTO list_of_players_league_doubles (league_id, player_Id_doubles_1, player_Id_doubles_2, league_group)
        VALUES (:league_id, :player_Id_doubles_1, :player_Id_doubles_2, :league_group)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles_1", $player_Id_doubles_1, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles_2", $player_Id_doubles_2, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Registrovanie nového páru hráčov - doubles do ligy sa nepodarilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii Doubles\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    /**
     *
     * RETURN ALL PLAYERS in DOUBLES TO REGISTER FROM DATABASE WHO ARE NOT IN CURRENT LEAGUE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * 
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeaguePlayersNotRegistered($connection, $league_id, $columns = "player_Id, second_name, first_name"){
        $sql = "SELECT $columns
                FROM player_user
                WHERE player_Id != 0 AND player_Id NOT IN
                    (SELECT player_user.player_Id
                    FROM player_user
                    INNER JOIN list_of_players_league_doubles ON list_of_players_league_doubles.player_Id_doubles_1 = player_user.player_Id OR list_of_players_league_doubles.player_Id_doubles_2 = player_user.player_Id
                    WHERE list_of_players_league_doubles.league_id = :league_id)
                ORDER BY second_name";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých hráčov o hráčoch v dvojiciach z konkrétnej ligy, ktorí tam nie sú registrovaní - sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeaguePlayersNotRegistered, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL REGISTERED DOUBLES IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param boolean $player_zero - true or false
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeagueDoubles($connection, $league_id, $player_zero, $columns = "list_of_players_league_doubles.*, t1.first_name AS player1_first_name, t1.second_name AS player1_second_name, t1.player_Image AS player1_image, t1.country AS player1_country, t1.player_club AS player1_club, t2.first_name AS player2_first_name, t2.second_name AS player2_second_name, t2.player_Image AS player2_image, t2.country AS player2_country, t2.player_club AS player2_club"){

        if ($player_zero === TRUE){
            $add_text = " AND (t1.player_Id != 0 OR t2.player_Id != 0)";
        } else {
            $add_text = NULL;
        }
    
        $sql = "SELECT $columns
                FROM list_of_players_league_doubles
                INNER JOIN player_user AS t1 
                    ON list_of_players_league_doubles.player_Id_doubles_1 = t1.player_Id
                INNER JOIN player_user AS t2
                    ON list_of_players_league_doubles.player_Id_doubles_2 = t2.player_Id
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
     * RETURN ALL GROUPS FOR EACH PLAYER REGISTERED PLAYERS IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $player_Id_doubles - representing one player form doubles in current league
     * @return array array of objects, one object mean one doubles in league
     */
    public static function getDoubles($connection, $league_id, $player_Id_doubles){
        $sql = "SELECT *
                FROM list_of_players_league_doubles
                WHERE league_id = :league_id AND (player_Id_doubles_1 = :player_Id_doubles OR player_Id_doubles_2 = :player_Id_doubles);";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles", $player_Id_doubles, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetch();
            } else {
                throw new Exception ("Príkaz pre získanie všetkých informácii o dvojici v konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getDoubles, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * DELETE ONE PLAYER IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $player_Id_doubles_1 - id for registered player in League
     * @param integer $player_Id_doubles_2 - id for registered player in League
     * 
     * @return boolean if delete is successful
     */
    public static function deleteLeagueDobles($connection, $league_id, $player_Id_doubles_1, $player_Id_doubles_2){
        $sql = "DELETE 
                FROM list_of_players_league_doubles
                WHERE player_Id_doubles_1 = :player_Id_doubles_1 AND player_Id_doubles_2 = :player_Id_doubles_2 AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles_1", $player_Id_doubles_1, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles_2", $player_Id_doubles_2, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o dvojici z konkretnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deleteLeagueDobles, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * DELETE ONE DOUBLES IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $doubles_in_league_id - id for registered player in League
     * 
     * @return boolean if delete is successful
     */
    public static function deleteSpecLeagueDoubles($connection, $league_id, $doubles_in_league_id){
        $sql = "DELETE 
        FROM list_of_players_league_doubles
        WHERE doubles_in_league_id = :doubles_in_league_id AND league_id = :league_id";


        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":doubles_in_league_id", $doubles_in_league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o hráčovi z konkretnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deleteSpecLeagueDoubles, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL GROUPS FOR EACH DOUBLES REGISTERED PLAYERS IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @return array array of objects, one object mean one player
     */
    public static function getDoublesGroupInLeague($connection, $league_id){
        $sql = "SELECT COUNT(*)
                FROM list_of_players_league_doubles
                WHERE league_id = :league_id AND league_group IS NULL";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchColumn();
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát skupinách pre dvojice z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getDoublesGroupInLeague, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN BOOLEAN IF DOUBLES IN GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     
     * @param integer $league_group - league_group for one user
     * @param integer $player_Id - id for one user
     * 
     * @return boolean if update is successful
     */
    public static function updateLeagueDoubles($connection, $league_group, $player_Id_doubles_1, $player_Id_doubles_2, $league_id){
        $sql = "UPDATE list_of_players_league_doubles
                SET league_group = :league_group
                WHERE player_Id_doubles_1 = :player_Id_doubles_1 AND player_Id_doubles_2 = :player_Id_doubles_2 AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles_1", $player_Id_doubles_1, PDO::PARAM_INT);
        $stmt->bindValue(":player_Id_doubles_2", $player_Id_doubles_2, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update one doubles in league_group v v konkrétnej lige sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateLeagueDoubles, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN BOOLEAN IF DOUBLES IN GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_group - league_group for one user
     * @param integer $doubles_in_league_id - id for one doubles in league
     * 
     * @return boolean if update is successful
     */
    public static function updateSpecificLeagueGroup($connection, $league_group, $doubles_in_league_id, $league_id){
        $sql = "UPDATE list_of_players_league_doubles
                SET league_group = :league_group
                WHERE doubles_in_league_id = :doubles_in_league_id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":doubles_in_league_id", $doubles_in_league_id, PDO::PARAM_INT);
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
     * RETURN INTEGER - HOW MANY GROUPS HAVE ON ACTIVE LEAGUE
     *
     * @param object $connection - connection to database
     * @param integer $league_group - league_group for one user
     * 
     * @return integer $league_group - highest number of groups
     */
    public static function getNumberOfGroups($connection, $league_id){
        $sql = "SELECT league_group
                FROM list_of_players_league_doubles
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
     * RETURN ALL LEAGUE DOUBLES FROM DATABASE WITH 0 LEAGUE GROUP - INACTIVE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $league_group - id for league group
     * @return integer player ID for one player in league
     */
    public static function getInactiveLeagueDoubles($connection, $league_id, $league_group){
        $sql = "SELECT doubles_in_league_id
                FROM list_of_players_league_doubles
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
    public static function countActiveLeagueDoublesInGroup($connection, $league_id, $league_group){
        $sql = "SELECT COUNT(*)
                FROM list_of_players_league_doubles
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
                throw new Exception ("Príkaz pre získanie počtu dvojíc skupine z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii countActiveLeagueDoublesInGroup, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }

    }


    /**
     *
     * RETURN ALL REGISTERED DOUBLES IN LEAGUE FROM DATABASE ACCORDING LEAGUE GROUP
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeagueDoublesByGroup($connection, $league_id, $league_group){
    
        $sql = "SELECT *
                FROM list_of_players_league_doubles
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

}