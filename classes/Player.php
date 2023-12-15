<?php

class Player {


    /**
     *
     * ADD PLAYER/USER TO DATABASE
     *
     * @param object $connection - database connection
     * @param string $user_name - user_name
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
    public static function createPlayerUser($connection, $user_name, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue, $player_type) {

        // temporary password for new player/user
        $temporary_password = password_hash("manilaSBIZ", PASSWORD_DEFAULT);
        // sql scheme
        $sql = "INSERT INTO player_user (user_name, password, first_name, second_name, country, player_club, player_Image, player_cue, player_break_cue, player_jump_cue, player_type)
        VALUES (:user_name, :password, :first_name, :second_name, :country, :player_club, :player_Image, :player_cue, :player_break_cue, :player_jump_cue, :player_type)";

        // prepare data to send to Database
        $stmt = $connection->prepare($sql);

        // filling and bind values will be execute to Database
        $stmt->bindValue(":user_name", $user_name, PDO::PARAM_STR);
        $stmt->bindValue(":password", $temporary_password, PDO::PARAM_STR);
        $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindValue(":country", $country, PDO::PARAM_STR);
        $stmt->bindValue(":player_club", $player_club, PDO::PARAM_STR);
        $stmt->bindValue(":player_Image", $player_Image, PDO::PARAM_STR);
        $stmt->bindValue(":player_cue", $player_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_break_cue", $player_break_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_jump_cue", $player_jump_cue, PDO::PARAM_STR);
        $stmt->bindValue(":player_type", $player_type, PDO::PARAM_STR);

        
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
                FROM player_user";

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
     * @param integer $player_Id - id for one user
     * @return array asoc array with one user
     */
    public static function getUser($connection, $player_Id){
        $sql = "SELECT * 
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

}