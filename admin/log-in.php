<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/Url.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();


    $log_user_email = $_POST["user_email"];
    $log_password = $_POST["password"];

    
    // login is successful
    if (Player::authentication($connection, $log_user_email, $log_password)){

        // player_Id for player who is logged in
        $id = Player::getUserId($connection, $log_user_email)["player_Id"];

        // prevents 'Fixation attack'
        session_regenerate_id(true);

        // Set session for user who is logged in
        $_SESSION["is_logged_in"] = true;
        
        // set session for user ID
        $_SESSION["logged_in_user_id"] = $id;

        // Nastavenie role uživatela
        $_SESSION["role"] = Player::getUserRole($connection, $id);

        Url::redirectUrl("/z-scoreboard/admin/admin-players-list.php");
     
    } else {
        $error = "Neúspešné prihlásenie !!!";
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/fail-login.css">
    <title>Neúspešné prihlásenie</title>
</head>
<body>
    <main>

        <?php if(!empty($error)): ?>
            <h1><?= $error ?></h1>
            <a href="../signin.php">Naspäť na prihlásenie</a>
        <?php endif; ?>


    </main>
    
</body>
</html>