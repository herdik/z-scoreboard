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
    <link rel="stylesheet" href="../css/reg-add-player-user.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="registration-form">
            <h1>Registrácia nového hráča</h1>
            <form action="after-reg-add-player-user.php" method="POST">
                <input type="text" name="user_name" placeholder="Užívateľské meno" required>
                <input type="text" name="first_name" placeholder="Meno" required>
                <input type="text" name="second_name" placeholder="Priezvisko" required>
                <input type="text" name="country" placeholder="Krajina" required>
                <input type="text" name="player_club" placeholder="Klub" required>
                <input type="text" name="player_Image" placeholder="Obrázok">
                <input type="text" name="player_cue" placeholder="Hracie tágo">
                <input type="text" name="player_break_cue" placeholder="Rozbíjacie tágo">
                <input type="text" name="player_jump_cue" placeholder="Skákacie tágo">
                <input class="btn" type="submit" value="Zaregistrovať">
                
            </form>

        </section>
        
        
    </main>
    
    <?php require "../assets/footer.php" ?>

</body>
</html>