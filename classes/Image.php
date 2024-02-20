<?php

 
class Image {

    // INSERT IMAGE
    /**
     *
     * ADD IMAGE TO DATABASE
     *
     * @param object $connection - database connection
     * @param string $user_id - specifically id for specifically player
     * @param string $image_name - image_name for specifically player
     *
     * 
     * @return boolean true or false
     * 
     */
    public static function insertPlayerImage($connection, $user_id, $image_name){
        $sql = "INSERT INTO image (user_id, image_name)
                VALUES (:user_id, :image_name)";
       
        $stmt = $connection->prepare($sql);


        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":image_name", $image_name, PDO::PARAM_STR);


        try {
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Vloženie obrázku do databázi sa nepodarilo");
            }
        } catch (Exception $e) {
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii insertPlayerImage, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }

    }


    /**
     *
     * RETURN ALL IMAGES FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one image
     */

    public static function getAllImages($connection, $user_id, $colums = "*"){
        $sql = "SELECT $colums
                FROM image
                WHERE user_id = :user_id";

        
        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o obrázkoch sa nepodaril");
            }
        } catch (Exception $e) {
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getAllImages, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }


    /**
     *
     * RETURN IMAGE NAME FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return array array of objects, one object mean one image
     */

     public static function getImageName($connection, $image_id){
        $sql = "SELECT image_name
                FROM image
                WHERE image_id = :image_id";

        
        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":image_id", $image_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return $stmt->fetchColumn();
            } else {
                throw new Exception ("Príkaz pre získanie všetkých dát o obrázkoch sa nepodaril");
            }
        } catch (Exception $e) {
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii getImageName, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }



    /**
     *
     * DELETE IMAGE FROM DIRECTORY, RETURN BOOLEAN
     *
     * @param object $connection - connection to database
     *
     * @return boolean after delete image from directorty
     */
    public static function deleteImageFromDirectory($path){
        try {
            // checking if exists 
            if(!file_exists($path)){
                throw new Exception("Súbor neexistuje a preto nemôže byť zmazaný");
            }
    
    
            // Zmazanie súboru
            if(unlink($path)){
                return true;
            } else {
                throw new Exception("Pri vymazaní súboru došlo k chybe");
            }
        } catch (Exception $e) {
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii deleteImageFromDirectory, získanie informácií z databázy zlyhalo\n", 3, "../errors/error.log");
            echo "Výsledná chyba je: " . $e->getMesssage();
        }
    }




    /**
     *
     * RETURN BOOLEAN AFTER DELETE IMAGE FROM DATABASE
     *
     * @param object $connection - connection to database
     *
     * @return boolean after delete image from database
     */

     public static function deleteImageFromDatabase($connection, $image_id){
        $sql = "DELETE
                FROM image
                WHERE image_id = :image_id";

        
        // connect sql amend to database
        $stmt = $connection->prepare($sql);

        // all parameters to send to Database
        $stmt->bindValue(":image_id", $image_id, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                return true;
            } else {
                throw new Exception ("Príkaz pre vymazanie obrázku sa nepodaril");
            }
        } catch (Exception $e) {
            // 3 je že vyberiem vlastnú cestu k súboru
            error_log("Chyba pri funckii deleteImageFromDatabase, príkaz pre získanie informácií z databázy zlyhal\n", 3, "./errors/error.log");
            echo "Výsledná chyba je: " . $e->getMessage();
        }
    }
}