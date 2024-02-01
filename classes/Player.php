<?php

class Player {


    /**
     *
     * ADD PLAYER/USER TO DATABASE
     *
     * @param object $connection - database connection
     * @param string $user_email - user_email
     * @param string $password - username password
     * @param string $first_name - first name
     * @param string $second_name - second name
     * @param string $country - country
     * @param string $player_club - player_club
     * @param string $player_Image - player_Image
     * @param string $player_cue - player_cue
     * @param string $player_break_cue - player_break_cue
     * @param string $player_jump_cue - player_jump_cue
     * @param string $player_type - manually input 'player'
     *
     * @return integer $player_id - id for player
     * 
     */
    public static function createPlayer($connection, $user_email, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue) {

        // temporary password for new player/user
        $temporary_password = password_hash("manilaSBIZ", PASSWORD_DEFAULT);
        // sql scheme
        $sql = "INSERT INTO player_user (user_email, password, first_name, second_name, country, player_club, player_Image, player_cue, player_break_cue, player_jump_cue, player_type)
        VALUES (:user_email, :password, :first_name, :second_name, :country, :player_club, :player_Image, :player_cue, :player_break_cue, :player_jump_cue, :player_type)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":user_email", $user_email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $temporary_password, PDO::PARAM_STR);
        $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindValue(":country", $country, PDO::PARAM_STR);
        $stmt->bindValue(":player_club", $player_club, PDO::PARAM_STR);
        $stmt->bindValue(":player_Image", $player_Image, PDO::PARAM_STR);
        $stmt->bindValue(":player_cue", $player_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_break_cue", $player_break_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_jump_cue", $player_jump_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_type", "player", PDO::PARAM_STR);

        
        try {
            // execute all data to SQL Database to table player_user
            if($stmt->execute()){
                $player_id = $connection->lastInsertId();
                return $player_id;
            } else {
                throw new Exception ("Vytvorenie nového hráča sa neuskutočnilo");
            }
        } catch (Exception $e) {
            error_log("Chyba pri funkcii createPlayerUser\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }






    /**
     *
     * RETURN ALL REGISTERED PLAYERS FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one player
     */
    public static function getAllPlayers($connection, $columns = "*"){
        $sql = "SELECT $columns
                FROM player_user
                WHERE player_Id != 0
                ORDER BY second_name";

        $stmt = $connection->prepare($sql);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o hráčoch sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllPlayers, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN ONE PLAYER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $player_Id - id for one player
     * @return array asoc array with one player
     */
    public static function getPlayer($connection, $player_Id){
        $sql = "SELECT  first_name, 
                        second_name, 
                        country, 
                        player_club,
                        player_Image, 
                        player_cue, 
                        player_break_cue, 
                        player_jump_cue
                FROM player_user
                WHERE player_Id = :player_Id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one player
                return $stmt->fetch();
            } else {
                throw Exception ("Príkaz pre získanie všetkých dát o hráčovi sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getPlayer, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    
    /**
     *
     * RETURN ONE USER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param string $first_name - player first name
     * @param string $second_name - player second name
     * @param string $country - player country
     * @param string $player_club - player club
     * @param string $player_Image - player Image
     * @param string $player_cue - player cue
     * @param string $player_break_cue - player break cue
     * @param string $player_jump_cue - player jump cue
     * @param integer $player_Id - id for one user
     * 
     * @return boolean if update is successful
     */
    public static function updatePlayer($connection, $user_email, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue, $player_Id){
        $sql = "UPDATE player_user
                SET user_email = :user_email,
                    first_name = :first_name, 
                    second_name = :second_name, 
                    country = :country, 
                    player_club = :player_club,
                    player_Image = :player_Image, 
                    player_cue = :player_cue, 
                    player_break_cue = :player_break_cue, 
                    player_jump_cue = :player_jump_cue
                WHERE player_Id = :player_Id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":user_email", $user_email, PDO::PARAM_STR);
        $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindValue(":country", $country, PDO::PARAM_STR);
        $stmt->bindValue(":player_club", $player_club, PDO::PARAM_STR);
        $stmt->bindValue(":player_Image", $player_Image, PDO::PARAM_STR);
        $stmt->bindValue(":player_cue", $player_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_break_cue", $player_break_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_jump_cue", $player_jump_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre update všetkých dát o užívateľovi sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii updatePlayer, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * RETURN ONE USER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $player_Id - id for one user
     * 
     * @return boolean if update is successful
     */
    public static function deletePlayer($connection, $player_Id){
        $sql = "DELETE 
                FROM player_user
                WHERE player_Id = :player_Id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        // filling and bind values will be execute to Database
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw Exception ("Príkaz pre vymazanie všetkých dát o užívateľovi sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii deletePlayer, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * RETURN ONE USER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $player_Id - id for one user
     * @return array asoc array with one user
     */
    public static function getUser($connection, $player_Id){
        $sql = "SELECT  player_Id,
                        user_email,
                        first_name, 
                        second_name, 
                        country, 
                        player_club,
                        player_Image, 
                        player_cue, 
                        player_break_cue, 
                        player_jump_cue
                FROM player_user
                WHERE player_Id = :player_Id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one player
                return $stmt->fetch();
            } else {
                throw Exception ("Príkaz pre získanie všetkých dát o užívateľovi sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getUser, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * RETURN ID USER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param string $user_email - $user_email from form for one user
     * @return int ID for one user
     */
    public static function getUserId($connection, $user_email){
        $sql = "SELECT  player_Id
                FROM player_user
                WHERE user_email = :user_email";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":user_email", $user_email, PDO::PARAM_STR);

        try {
            if($stmt->execute()){
                // asscoc array for one player and we want to get player_Id
                return $stmt->fetch();
            } else {
                throw Exception ("Príkaz pre získanie všetkých ID o užívateľovi sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getUserId, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }




    /**
     *
     * RETURN ONE USER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param integer $player_Id - $user_Id who is logged in
     * @return array asoc array with one user
     */
    public static function getUserRole($connection, $player_Id){
        $sql = "SELECT role
                FROM player_user
                WHERE player_Id = :player_Id";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":player_Id", $player_Id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                // asscoc array for one player
                return $stmt->fetch()["role"];
            } else {
                throw Exception ("Príkaz pre získanie role o užívateľovi sa nepodaril");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii getUserRole, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * Authentication for ONE USER FROM DATABASE
     *
     * @param object $connection - connection to database
     * @param string $user_email - $user_email from form for one user
     * @param string $password - $password from form to signIn
     * @return array asoc array with one user
     */
    public static function authentication($connection, $log_user_email, $log_password){
        $sql = "SELECT password
                FROM player_user
                WHERE user_email = :user_email";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":user_email", $log_user_email, PDO::PARAM_STR);
        

        try {
            if($stmt->execute()){
                if ($user = $stmt->fetch()){
                    return password_verify($log_password, $user["password"]);
                }
            } else {
                throw Exception ("Overenie hesla pre užívateľa sa nepodarilo");
            }
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii authentication, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

    /**
     *
     * GET NEXT player_Id / user_id FOR NEXT USER FROM DATABASE
     *
     * @return int ID for one user
     */
    public static function nextPLayerId($connection){
        $sql = "SELECT `AUTO_INCREMENT`
                FROM  INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = 'z_scoreboard'
                AND   TABLE_NAME   = 'player_user'";
        

        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        try {
            if($stmt->execute()) {
                return $stmt->fetch()["AUTO_INCREMENT"];
            }
            
        } catch (Exception $e){
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funkcii authentication, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }

}