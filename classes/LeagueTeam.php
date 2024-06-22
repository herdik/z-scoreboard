<?php

class LeagueTeam {


    /**
     *
     * RETURN BOOLEAN if PLAYER ARE REGISTERED IN LEAGUE - DATABASE
     *
     * @param integer $league_id - id for one league
     * @param integer $team_id - id for one team to reg in league
     * 
     * @return boolean if creating is successful
     */
    public static function createLeagueTeam($connection, $league_id, $team_id, $league_group = NULL) {

        // sql scheme
        $sql = "INSERT INTO list_of_players_league_team (league_id, team_id, league_group)
        VALUES (:league_id, :team_id, :league_group)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":team_id", $team_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);

        try {
            // execute all data to SQL Database to table list_of_players_league_team
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Registrovanie nového družstva do ligy sa nepodarilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createLeagueTeam\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL TEAMS TO REGISTER FROM DATABASE WHO ARE NOT IN SPECIFIC LEAGUE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one team
     */
    public static function getAllLeagueTeamsNotRegistered($connection, $league_id, $columns = "team_id, team_name"){
        $sql = "SELECT $columns
                FROM team_user
                WHERE team_id != 0 AND team_id NOT IN
                    (SELECT team_user.team_id
                FROM team_user
                INNER JOIN list_of_players_league_team ON list_of_players_league_team.team_id = team_user.team_id
                WHERE list_of_players_league_team.league_id = :league_id)
                ORDER BY team_name";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých teamov z konkrétnej ligy, ktorí tam nie sú registrovaní sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeagueTeamsNotRegistered, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL REGISTERED TEAMS IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one TEAM
     */
    public static function getAllLeagueTeams($connection, $league_id, $team_zero, $columns = "list_of_players_league_team.*, team_user.team_name, team_user.team_image, team_user.team_country"){
        if ($team_zero === TRUE){
            $add_text = " AND team_user.team_id != 0";
        } else {
            $add_text = NULL;
        }
    
        $sql = "SELECT $columns
                FROM list_of_players_league_team
                INNER JOIN team_user ON list_of_players_league_team.team_id =team_user.team_id
                WHERE league_id = :league_id $add_text
                ORDER BY league_group";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o teamoch z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeagueTeams, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * DELETE ONE TEAM IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $team_id - id for registered team in League
     * 
     * @return boolean if delete is successful
     */
    public static function deleteLeagueTeam($connection, $league_id, $team_id){
        $sql = "DELETE 
                FROM list_of_players_league_team
                WHERE team_id = :team_id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":team_id", $team_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o družstva z konkretnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deleteLeagueTeam, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ALL GROUPS FOR EACH TEAM REGISTERED PLAYERS/TEAMS IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @return array array of objects, one object mean one team
     */
    public static function getTeamGroupInLeague($connection, $league_id){
        $sql = "SELECT COUNT(*)
                FROM list_of_players_league_team
                WHERE league_id = :league_id AND league_group IS NULL";

        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchColumn();
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát v skupinách pre družstvá z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getTeamGroupInLeague, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN BOOLEAN IF TEAM´S GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     
     * @param integer $league_group - league_group for one user
     * @param integer $team_id - id for one team
     * 
     * @return boolean if update is successful
     */
    public static function updateLeagueTeam($connection, $league_group, $team_id, $league_id){
        $sql = "UPDATE list_of_players_league_team
                SET league_group = :league_group
                WHERE team_id = :team_id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":team_id", $team_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update league_group v v konkrétnej lige sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updateLeagueTeam, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    /**
     *
     * RETURN BOOLEAN IF PLAYER´S GROUP IS UPDATED IN LEAGUE - DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_group - league_group for one team
     * @param integer $team_in_league_id - id for one team in league
     * 
     * @return boolean if update is successful
     */
    public static function updateSpecificLeagueGroup($connection, $league_group, $team_in_league_id, $league_id){
        $sql = "UPDATE list_of_players_league_team
                SET league_group = :league_group
                WHERE team_in_league_id = :team_in_league_id AND league_id = :league_id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_group", $league_group, PDO::PARAM_INT);
        $stmt->bindValue(":team_in_league_id", $team_in_league_id, PDO::PARAM_INT);
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update league_group v konkrétnej lige sa nepodaril");
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
                FROM list_of_players_league_team
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
     * RETURN ALL LEAGUETEAMS FROM DATABASE WITH 0 LEAGUE GROUP - INACTIVE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $league_group - id for league group
     * @return integer player ID for one player in league
     */
    public static function getInactiveLeagueTeams($connection, $league_id, $league_group){
        $sql = "SELECT team_in_league_id
                FROM list_of_players_league_team
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
                throw Exception ("Príkaz pre získanie všetkých dát o teame v lige a skupine sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getInactiveLeagueTeams, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * DELETE ONE TEAM IN LEAGUE FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $league_id - id for league
     * @param integer $team_in_league_id - id for registered team in League
     * 
     * @return boolean if delete is successful
     */
    public static function deleteSpecLeagueTeam($connection, $league_id, $team_in_league_id){
        $sql = "DELETE 
        FROM list_of_players_league_team
        WHERE team_in_league_id = :team_in_league_id AND league_id = :league_id";


        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":league_id", $league_id, PDO::PARAM_INT);
        $stmt->bindValue(":team_in_league_id", $team_in_league_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o teame z konkretnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deleteSpecLeagueTeam, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
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
     * @return integer count active teams in groups
     */
    public static function countActiveLeagueTeamsInGroup($connection, $league_id, $league_group){
        $sql = "SELECT COUNT(*)
                FROM list_of_players_league_team
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
            error_log("Chyba pri funckii countActiveLeagueTeamsInGroup, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }

    }


    /**
     *
     * RETURN ALL REGISTERED TEAMS IN LEAGUE FROM DATABASE ACCORDING LEAGUE GROUP
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllLeagueTeamsByGroup($connection, $league_id, $league_group){
    
        $sql = "SELECT *
                FROM list_of_players_league_team
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
                throw new Exception ("Príkaz pre získanie všetkých dát o teame z konkrétnej ligy sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllLeagueTeamsByGroup, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }
}