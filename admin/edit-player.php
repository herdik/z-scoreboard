<?php 


    require "../classes/Database.php";
    require "../classes/Player.php";

    // connection to Database
    $database = new Database();
    $connection = $database->connectionDB();

    if (isset($_GET["player_Id"]) and is_numeric($_GET["player_Id"])){
        $player_infos = Player::getUser($connection, $_GET["player_Id"]);
    } else {
        $player_infos = null;
    }

    
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
    <link rel="stylesheet" href="../css/reg-add-player.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="registration-form">
            <h1>Editácia hráča</h1>
            <form action="after-edit-player.php" method="POST">
                <input type="hidden" name="player_Id" value="<?= htmlspecialchars($player_infos["player_Id"]) ?>">
                <input type="text" name="user_name" placeholder="Používateľské meno" value="<?= htmlspecialchars($player_infos["user_name"]) ?>" required>
                <input type="text" name="first_name" placeholder="Meno" value="<?= htmlspecialchars($player_infos["first_name"]) ?>" required>
                <input type="text" name="second_name" placeholder="Priezvisko" value="<?= htmlspecialchars($player_infos["second_name"]) ?>" required>
                <input type="text" name="country" placeholder="Krajina" value="<?= htmlspecialchars($player_infos["country"]) ?>" required>
                <input type="text" name="player_club" placeholder="Klub" value="<?= htmlspecialchars($player_infos["player_club"]) ?>" required>
                <input type="text" name="player_Image" placeholder="Obrázok" value="<?= htmlspecialchars($player_infos["player_Image"]) ?>">
                <input type="text" name="player_cue" placeholder="Hracie tágo" value="<?= htmlspecialchars($player_infos["player_cue"]) ?>">
                <input type="text" name="player_break_cue" placeholder="Rozbíjacie tágo" value="<?= htmlspecialchars($player_infos["player_break_cue"]) ?>">
                <input type="text" name="player_jump_cue" placeholder="Skákacie tágo" value="<?= htmlspecialchars($player_infos["player_jump_cue"]) ?>">
                <input class="btn" type="submit" value="Upraviť">
                
            </form>

        </section>
        
        
    </main>
    
    <?php require "../assets/footer.php" ?>

</body>
</html>