<div class="general-match <?= htmlspecialchars($match_color) ?>">
                                
    <!-- print profil image for player1A -->
    <?php if ($league_match["player1A_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_1A"]) . "/" . htmlspecialchars($league_match["player1A_image"]) ?> alt="">
    <?php endif ?> 

    <span class="pl1-span player1AB-span">

            <!-- print player name and flag for player1AB -->
        <?php if ($league_match["player1A_firstname"] === "Voľno" && $league_match["player1A_country"] === "none"): ?>
            <!-- Player name 1A -->
            <?= htmlspecialchars($league_match["player1A_firstname"]) ?>
        <?php else: ?>
            <!-- flag info 1A-->
            <img src="../img/countries/<?= htmlspecialchars($league_match["player1A_country"]) ?>.png" alt="">
        
            <!-- Player name 1A -->
            <?= htmlspecialchars($league_match["player1A_firstname"]) . " " . htmlspecialchars($league_match["player1A_second_name"]) ?>

            <!-- club info 1A-->
            <div class="main-info-player">    
                <?= htmlspecialchars($league_match["player1A_club"]) ?>
            </div> 

            <!-- flag info 1B-->
            <img src="../img/countries/<?= htmlspecialchars($league_match["player1B_country"]) ?>.png" alt="">

            <!-- Player name 1B -->
            <?= htmlspecialchars($league_match["player1B_firstname"]) . " " . htmlspecialchars($league_match["player1B_second_name"]) ?>
            
            <!-- club info 1B-->
            <div class="main-info-player">    
                <?= htmlspecialchars($league_match["player1B_club"]) ?>
            </div>

        <?php endif ?>     
    </span>

    <!-- print profil image for player1B -->
    <?php if ($league_match["player1B_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_1B"]) . "/" . htmlspecialchars($league_match["player1B_image"]) ?> alt="">
    <?php endif ?>

    <label class="dl1-label"><?= htmlspecialchars($league_match["score_1"]) ?></label>

    <div class="btnAndGame">
        <img src=<?="../img/" . htmlspecialchars($league_match["choosed_game"]). "-ball.png" ?> alt=<?= htmlspecialchars($league_match["choosed_game"]). "-ball" ?>>

        <?php if ($button_text === "Zapnúť"): ?>

            <!-- <form id="status-match" action="./change-status-match.php" method="post"> -->
                <!-- <button name="match_button" type="submit" value="Zapnúť"></button> -->
    
        <?php elseif ($button_text === "Čaká"): ?>
            <!-- <form id="status-match" action="./change-status-match.php" method="post"> -->
                
        <?php endif ?>
                <input type="hidden" class="league-format" name="league-format" value="<?= htmlspecialchars($league_infos["playing_format"]) ?>" readonly>
                <input type="hidden" class="match_id" name="match_id" value="<?= htmlspecialchars($league_match["match_id"]) ?>" readonly>
                <input type="hidden" class="league_id" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                <input type="hidden" class="league_group" name="league_group" value="<?= htmlspecialchars($league_group) ?>" readonly>
                <input class="match_button <?php if($league_match["match_finished"]) { echo 'fisnishedLeagueMatchHide'; } ?>" name="btn_value" type="submit" value="<?= htmlspecialchars($button_text) ?>">
                <a href="#"></a>
            <!-- </form> -->
    </div>

    <label class="dl2-label"><?= htmlspecialchars($league_match["score_2"]) ?></label>

    <!-- print profil image for player 2A -->
    <?php if ($league_match["player2A_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_2A"]) . "/" . htmlspecialchars($league_match["player2A_image"]) ?> alt="">
    <?php endif ?>
    
    <span class="pl2-span player1AB-span">

        <!-- print player name and flag for player2AB -->
        <?php if ($league_match["player2A_firstname"] === "Voľno" && $league_match["player2A_country"] === "none"): ?>
            <!-- Player name 2A -->
            <?= htmlspecialchars($league_match["player2A_firstname"]) ?>
            
        <?php else: ?>
            <!-- flag info 2A-->
            <img src="../img/countries/<?= htmlspecialchars($league_match["player2A_country"]) ?>.png" alt="">
        
            <!-- Player name 2A -->
            <?= htmlspecialchars($league_match["player2A_firstname"]) . " " . htmlspecialchars($league_match["player2A_second_name"]) ?>

            <!-- club info 2A-->
            <div class="main-info-player">    
                <?= htmlspecialchars($league_match["player2A_club"]) ?>
            </div> 

            <!-- flag info 2B-->
            <img src="../img/countries/<?= htmlspecialchars($league_match["player2B_country"]) ?>.png" alt="">

            <!-- Player name 2B -->
            <?= htmlspecialchars($league_match["player2B_firstname"]) . " " . htmlspecialchars($league_match["player2B_second_name"]) ?>
            
            <!-- club info 2B-->
            <div class="main-info-player">    
                <?= htmlspecialchars($league_match["player2B_club"]) ?>
            </div>
        <?php endif ?> 
    </span>

    <!-- print profil image for player 2B -->
    <?php if ($league_match["player2B_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_2B"]) . "/" . htmlspecialchars($league_match["player2B_image"]) ?> alt="">
    <?php endif ?>
    
</div> 