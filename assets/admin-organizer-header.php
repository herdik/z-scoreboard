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

        <ul>

            <li><a href="./admin-competitions.php">Súťaže</a></li>
            <li><a href="./reg-add-player.php">Registrácia hráča</a></li>
            <li><a href="./admin-players-list.php">Zoznam hráčov</a></li>
            <li><a href="">Challenge Matches</a></li>
            <li><a href="">Scoreboard</a></li>
            <li><a href="./log-out.php"><?= htmlspecialchars($user_info["first_name"]). " " .htmlspecialchars($user_info["second_name"]) ?></a></li>
            

        </ul>

        
    </nav>

    <div class="menu-icon">
                <i class="fa-solid fa-bars"></i>
                <!-- <i class="fa-solid fa-xmark"></i> -->
    </div>

</header>