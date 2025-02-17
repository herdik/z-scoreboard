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

    // connection to Database
    $database = new Database();
    $connection = $database->connectionDB();

    if (isset($_GET["player_Id"]) and is_numeric($_GET["player_Id"])){
        $player_infos = Player::getUser($connection, $_GET["player_Id"]);
    } else {
        $player_infos = null;
    }

    // control if user choose image from image gallery
    if ((isset($_GET["image_id"]) and is_numeric($_GET["image_id"])) and (isset($_GET["sequence"]) and is_numeric($_GET["sequence"]))){
        $image_id = $_GET["image_id"];
        $image_sequence = $_GET["sequence"];
    } else {
        $image_id = null;
        $image_sequence = null;
    }
    $teams_infos = Team::getAllTeams($connection);
?>



<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editácia hráča</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/edit-player.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="registration-form">
            <h1>Editácia hráča</h1>
            <form action="after-edit-player.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="player_Id" value="<?= htmlspecialchars($player_infos["player_Id"]) ?>">
                <?php require "../assets/form.php" ?>
                <input class="btn" type="submit" name="submit" value="Upraviť">
                
            </form>

        </section>
        
        
    </main>
    
    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
    <script src="../js/show-img-name.js"></script>
    <script src="../js/get-club-value.js"></script>
</body>
</html>