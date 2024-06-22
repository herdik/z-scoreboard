<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/Image.php";
require "../classes/Team.php";
require "../classes/Url.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    // if redirect is active/true do not save data to database and not to use onother redirrect
    $redirect_status = false;

    $next_player_id = Player::nextPLayerId($connection);

    $user_email = $_POST["user_email"];
    $first_name = $_POST["first_name"];
    $second_name = $_POST["second_name"];
    $country = $_POST["country"];
    $player_club = $_POST["player_club"];
    $player_club_id = $_POST["player_club_id"];
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

                    if(!file_exists("../uploads/" . $next_player_id)){
                        // 0777 authorizations
                        mkdir("../uploads/" . $next_player_id, 0777, true);
                    }

                    // create path where will save image
                    $image_upload_path = "../uploads/" . $next_player_id . "/" . $new_image_name;

                    // upload image - change temporary image path for path to current registered player
                    move_uploaded_file($image_tmp_name, $image_upload_path);
                } else {
                    // redirect to error site
                    $not_allowed_extension = "nedovolená koncovka";
                    Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_allowed_extension");
                    $redirect_status = true;
                }
            }
        } else {
            // error 0 = something went wrong 
            // error 4 = is UPLOAD_ERR_NO_FILE
            if ($error == 4 || ($image_size == 0 && $error == 0)){
                $new_image_name = "no-photo-player";
            }
        }
    }
    
    if (!$redirect_status){
        // player_Image is current choose image by user or automatically choose unknown player image 'no-photo-player'
        $player_Image = $new_image_name;

        // create registered Player in Database
        if (is_numeric($player_club_id)) {
            // find choosed team
            $team_info = Team::getTeam($connection, $player_club_id);
            $player_club = $team_info["team_name"];
            $player_club_id = $team_info["team_id"];
        } else {
            // create new team
            $player_club_id = Team::createTeam($connection, $player_club, "no-photo-player", $country);
        }
        
        $player_Id = Player::createPlayer($connection, $user_email, $first_name, $second_name, $country, $player_club, $player_club_id, $player_Image, $player_cue, $player_break_cue, $player_jump_cue);

        // save image for registered player in Database for current registered Player
        $image_id = Image::insertPlayerImage($connection, $player_Id, $player_Image);

        // save default image in image gallery in player is registered with choosed image
        if ($player_Image != "no-photo-player"){
            Image::insertPlayerImage($connection, $player_Id, "no-photo-player");
        }

        if (!empty($player_Id)){
            Url::redirectUrl("/z-scoreboard/admin/player-profil.php?player_Id=$player_Id");
        } else {
            $not_added_player = "Nového hráča sa nepodarilo pridať";
            Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_added_player");
        }
    }
} else {
    $not_authorization = "Nepovolený prístup";
    Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_authorization");
}
?>
