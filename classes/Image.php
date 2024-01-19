<?php

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
class Image {

    // INSERT IMAGE
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
}