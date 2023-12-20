<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/Url.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();


    $log_user_name = $_POST["user_name"];
    $log_password = $_POST["password"];

    
    // login is successful
    if (Player::authentication($connection, $log_user_name, $log_password)){

        // player_Id for player who is logged in
        $id = Player::getUserId($connection, $log_user_name);

        // prevents 'Fixation attack'
        session_regenerate_id(true);

        // Set session for user who is logged in
        $_SESSION["is_logged_in"] = true;
        
        // set session for user ID
        $_SESSION["logged_in_user_id"] = $id;

        // Nastavenie role uživatela
        $_SESSION["role"] = Player::getUserRole($connection, $id);

        Url::redirectUrl("/z-scoreboard/admin/admin-players-list.php");
     
    }

}
