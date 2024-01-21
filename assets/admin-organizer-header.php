<?php
    if (is_numeric($_SESSION["logged_in_user_id"]) and isset($_SESSION["logged_in_user_id"])){
        $user_info = Player::getUser($connection, $_SESSION["logged_in_user_id"]);
    } else {
        $user_info = "Odhlásiť";
    }   
?>

<header>
    <div class="logo">
        <a href="">
            <img src="../img/logo-white-manila.png" alt="">
        </a>
    </div>

    <nav>

        <ul id="main-menu">

            <li><a href="./admin-competitions.php">Súťaže</a></li>
            
            <?php if ($_SESSION["role"] === "admin" || $_SESSION["role"] === "organizer"): ?>
            <li><a href="./reg-add-player.php">Registrácia hráča</a></li>            
            <?php endif ?> 
            
            <li><a href="./admin-players-list.php">Zoznam hráčov</a></li>
            <li><a href="">Challenge Matches</a></li>
            <li><a href="">Scoreboard</a></li>
            <li id="current-player">

                <?php if (htmlspecialchars($user_info["player_Image"]) === "no-photo-player"): ?>
                    <img src="../img/no-photo-player.png" alt="no-photo-player.png">
                <?php else: ?>
                    <img src="../uploads/<?= htmlspecialchars($user_info["player_Id"]) ?>/<?= htmlspecialchars($user_info["player_Image"]) ?>" alt="<?= htmlspecialchars($user_info["first_name"]). " " .htmlspecialchars($user_info["second_name"]) ?>">
                <?php endif; ?>

                <a href="#"><?= htmlspecialchars($user_info["first_name"]). " " .htmlspecialchars($user_info["second_name"]) ?></a>
                <ul id="second-menu">
                    <li><a href="./player-profil.php?player_Id=<?= htmlspecialchars($user_info["player_Id"]) ?>">Môj profil</a></li>
                    <li><a href="./log-out.php">Odhlásiť</a></li>
                </ul>
                
            </li>
            

        </ul>

        
    </nav>

    <div class="menu-icon">
                <i class="fa-solid fa-bars"></i>
                <!-- <i class="fa-solid fa-xmark"></i> -->
    </div>

</header>