<?php
    require "../classes/Database.php";
    require "../classes/Player.php";
    require "../classes/Team.php";

    // verifying by session if visitor have access to this website
    require "../classes/Authorization.php";
    // get session
    session_start();
    // authorization for visitor - if has access to website 
    if (!Auth::isLoggedIn()){
        die ("nepovolený prístup");
    }

    // empty variables for arguments to form.php 
    $player_infos["player_Id"] = null;
    $player_infos["user_email"] = "";
    $player_infos["first_name"] = "";
    $player_infos["second_name"] = "";
    $player_infos["country"] = "";
    $player_infos["player_club"] = "";
    $player_infos["player_Image"] = "";
    $player_infos["player_cue"] = "";
    $player_infos["player_break_cue"] = "";
    $player_infos["player_jump_cue"] = "";

    // control if user choose image from image gallery because for edit and registration are used same form
    $image_id = null;
    $image_sequence = null;
    

    // database connection
    $database = new Database();
    $connection = $database->connectionDB();
    $teams_infos = Team::getAllTeams($connection);
?>


<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrácia hráča</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/reg-add-player.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="registration-form">
            <h1>Registrácia nového hráča</h1>
            <form action="after-reg-add-player.php" method="POST" enctype="multipart/form-data">

                <?php require "../assets/form.php" ?>
                <!-- <input type="email" name="user_email" placeholder="Email" required>
                <input type="text" name="first_name" placeholder="Meno" required>
                <input type="text" name="second_name" placeholder="Priezvisko" required>
                <input type="text" name="country" placeholder="Krajina" required>
                <input type="text" name="player_club" placeholder="Klub" required>
                <input type="text" name="player_Image" placeholder="Obrázok">
                <input type="text" name="player_cue" placeholder="Hracie tágo">
                <input type="text" name="player_break_cue" placeholder="Rozbíjacie tágo">
                <input type="text" name="player_jump_cue" placeholder="Skákacie tágo"> -->
                <input class="btn" type="submit" name="submit" value="Zaregistrovať">
                
            </form>

        </section>
        
        
    </main>
    
    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
    <script src="../js/show-img-name.js"></script>
    <script src="../js/get-club-value.js"></script>
</body>
</html>