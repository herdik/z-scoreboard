<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/Image.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

// database connection
$database = new Database();
$connection = $database->connectionDB();

if ($_SERVER["REQUEST_METHOD"] === "GET"){
    if (isset($_GET["player_Id"]) and is_numeric($_GET["player_Id"])){
        $images = Image::getAllImages($connection, $_GET["player_Id"]);
    } else {
        $images = null;
    }
}



?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galéria</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/image-gallery.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>
    
   
    <main>
        <h1>Galéria obrázkov</h1>
        <h5>Zostávajúci limit pre obrázky: <?= count($images) ?> / 6</h5>
        <section class="imgGallery">

            <article class="pictures">
                <?php foreach($images as $one_image): ?>
                    <div class="my-images">
                        <?php if (htmlspecialchars($one_image["image_name"]) === "no-photo-player"): ?>
                            <img src="../img/<?= htmlspecialchars($one_image["image_name"]) ?>.png" id="no-player" alt="">
                        <?php else: ?>
                            <img src="../uploads/<?= htmlspecialchars($one_image["user_id"]) ?>/<?= htmlspecialchars($one_image["image_name"]) ?>" alt="">
                            <a href="delete-image.php?image_id=<?= htmlspecialchars($one_image["image_id"]) ?>&player_Id=<?= htmlspecialchars($one_image["user_id"]) ?>&image_name=<?= htmlspecialchars($one_image["image_name"]) ?>" class="imageX" >X</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>  
            </article>
              
        </section>
        

    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>