<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/Image.php";
require "../classes/Url.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

// $database = new Database();
// $connection = $database->connectionDB();
// $player_Id = $_POST["player_Id"];
// $number_of_images = count(Image::getAllImages($connection, $player_Id));
// echo $number_of_images;
// if ($number_of_images <= 6) {
//     echo "pusti ma vykonať zmeny";
// } else {
//     echo "limit prekročený";
// }

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    // if redirect is active/true do not save data to database and not to use onother redirrect
    $redirect_status = false;

    $player_Id = $_POST["player_Id"];
    $user_email = $_POST["user_email"];
    $first_name = $_POST["first_name"];
    $second_name = $_POST["second_name"];
    $country = $_POST["country"];
    $player_club = $_POST["player_club"];
    $player_Image = $_FILES["player_Image"];
    $player_cue = $_POST["player_cue"];
    $player_break_cue = $_POST["player_break_cue"];
    $player_jump_cue = $_POST["player_jump_cue"];


    // isset is not null
    if(isset($_POST["submit"]) && isset($player_Image)){
        $image_name = $player_Image["name"];
        $image_size = $player_Image["size"];
        // temporary saved file/image
        $image_tmp_name = $player_Image["tmp_name"];
        $error = $player_Image["error"];

        // how many errors is
        if ($error === 0){
            $number_of_images = count(Image::getAllImages($connection, $player_Id));
            if ($number_of_images < 6) {
                echo "pusti ma vykonať zmeny";
                // 10000000 is 10MB
                if ($image_size > 10000000){
                    // redirect to error site
                    $too_big = "súbor je príliš veľký";
                    Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$too_big");
                    $redirect_status = true;
                } else {
                    // use pathinfo to get filename extension
                    $image_extension = pathinfo($image_name, PATHINFO_EXTENSION); 
                    // to lowercase image extension    
                    $image_extension_lower_case = strtolower($image_extension);

                    // allowed extensions 
                    $allowed_extensions = ["jpg", "jpeg", "png"];
                    
                    // in_array — Checks if a value exists in an array
                    if(in_array($image_extension_lower_case, $allowed_extensions)){
                        // uniq name for image
                        $new_image_name = uniqid("IMG-", true) . "." . $image_extension;

                        if(!file_exists("../uploads/" . $player_Id)){
                            // 0777 authorizations
                            mkdir("../uploads/" . $player_Id, 0777, true);
                        }

                        // create path where will save image
                        $image_upload_path = "../uploads/" . $player_Id . "/" . $new_image_name;

                        // upload image - change temporary image path for path to current registered player
                        move_uploaded_file($image_tmp_name, $image_upload_path);

                        // save image for registered player in Database for current registered Player
                        $image_id = Image::insertPlayerImage($connection, $player_Id, $new_image_name);
                    } else {
                        // redirect to error site
                        $not_allowed_extension = "nedovolená koncovka";
                        Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_allowed_extension");
                        $redirect_status = true;
                    }
                }
            } else {
                // redirect to error site if images in image gallery are more than allowed limit
                $not_allowed_image = "Počet obrázkov je väčší ako dovolený limit. Vymažte nejaký obrázok z galérie!!!";
                Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_allowed_image");
                $redirect_status = true; 
            }
            
        } else {
            // error 0 = something went wrong 
            // error 4 = is UPLOAD_ERR_NO_FILE
            if ($error == 4 || ($image_size == 0 && $error == 0)){
                $new_image_name = Player::getPlayer($connection, $player_Id, "player_Image")["player_Image"];
            }
        }
    }

    if (!$redirect_status){
         // player_Image is current choose image by user or automatically choose current image from database for current Player'
        $player_Image = $new_image_name;

        // update Player information in Database for current Player
        if (Player::updatePlayer($connection, $user_email, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue, $player_Id)){
            Url::redirectUrl("/z-scoreboard/admin/player-profil.php?player_Id=$player_Id");
        } else {
            $not_added_player = "Hráča sa nepodarilo upraviť";
            Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_added_player");
        }
    }
} else {
    echo "Nepovolený prístup";
}
?>

