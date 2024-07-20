<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueSettings.php";
require "../classes/LeagueMatch.php";
// require "../classes/LeaguePlayer.php";



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
    if ((isset($_GET["league_id"]) and is_numeric($_GET["league_id"])) and (isset($_GET["league_group"]) and is_numeric($_GET["league_group"]))) {
        $league_infos = League::getLeague($connection, $_GET["league_id"]);
        $league_id = $_GET["league_id"];
        $league_name = $league_infos["league_name"];
        $league_group = $_GET["league_group"];
        $active_league = $league_infos["active_league"];
        $count_groups = LeagueSettings::getLeagueSettings($connection, $league_id, "count_groups");
        $league_matches = LeagueMatch::getAllLeagueMatches($connection, $league_id, $league_group, $league_infos["playing_format"]);
    } else {
        $league_infos = null;
        $league_id = null;
        $league_name = null;
        $league_group = null;
        $active_league = false;
        $count_groups = null;
        $league_matches = null;
    }
    
} else {
    echo "Nepovolený prístup";
}

// for print current round nr in  --- > <div class="leagueRound"> 
$round_nr = 1;
// counter to add last </div> for <div class="leagueRound">
$counter = 1;
?>


<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoznam súťaží - administrácia</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/league-matches.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Modal okno pre editácia ligového zápasu -->
    <?php require "../assets/modal-single.php" ?>
    <!-- Modal okno pre editácia ligového zápasu -->
    
    <?php require "../assets/admin-organizer-header.php" ?>

    <main>
        <!-- SIDE NAV BAR -->
        <section class="navigation-bar">
            <ul>
                <li><a href="./current-league.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Informácie</a></li>
                <li><a href="./admin-list_of_league_players.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Zoznam hráčov</a></li>
                <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                    <li><a href="./league-settings.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Nastavenia</a></li>
                <?php endif; ?>
                <li><a href="./admin-league-matches.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Ligové zápasy</a></li>
                <li><a href="./results.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>&league_group=1">Výsledky</a></li>
            </ul>

        </section>
        
        <!-- MAIN LEAGUE CONTENT -->
        <section class="league-content">

            <!-- <div class="league-match-container"> -->
                <!-- OVERVIEW WHEN ONE GROUP IN CURRENT LEAGUE AND SHOW ALL LEAGUE MATCHES-->

                <!-- Názov heading Liga -->
                <div class="leagueHeading">
                    <h1><?= htmlspecialchars($league_name) ?>
                        <!-- <span class="left-icon"></span>
                        <span class="right-icon"></span> -->
                    </h1> 
                </div>

                <!-- Liga Rozpis -->
                <div class="league-matches show-league-matches">
                
                <?php foreach ($league_matches as $league_match): ?>

                    <?php if ($round_nr === $league_match["round_number"]): ?> 
                    
                    <?php if ($league_match["round_number"] > 1): ?>
                    </div>
                    <?php endif ?>

                    <div class="leagueRound">
                        <h1><?= $round_nr . ".kolo" ?></h1>
                        <?php $round_nr++ ?>
                    <?php endif ?>
                        

                        <div class="matchInformation">

                        <!-- Affects background-color, font color, table nr and button text according match status based on Database -->

                        <?php if ($league_match["match_finished"]): ?>

                            <?php $button_text = "Ukončiť" ?>
                            <?php $match_color = "fisnishedLeagueMatch" ?>
                            <?php $table_font_color = "color:rgb(255, 255, 255)" ?>
                            <?php $table_nr = "X" ?>

                        <?php elseif ($league_match["match_started"]): ?> 

                            <?php $button_text = "Upraviť" ?>
                            <?php $match_color = "activeLeagueMatch" ?>
                            <?php $table_font_color = "color:rgb(255, 255, 255)" ?>

                            <?php $table_nr = "T " . strval(htmlspecialchars($league_match["table_number"])) ?>

                        <?php elseif ($league_match["match_waiting"]): ?>

                            <?php $button_text = "Čaká" ?>
                            <?php $match_color = "waitingLeagueMatch" ?>
                            <?php $table_font_color = "color:rgb(255, 255, 255)" ?>
                            <?php $table_nr = "-" ?>

                        <?php else: ?>

                            <?php $button_text = "Zapnúť" ?>
                            <?php $match_color = "" ?>
                            <?php $table_font_color = "color:rgb(0, 0, 0)" ?>

                            <?php $table_nr = "-" ?>

                        <?php endif ?>    

                            <div class="tableNr <?= htmlspecialchars($match_color) ?>">
                                <h3 class="tableNrText" style= "<?= htmlspecialchars($table_font_color) ?>"><?= htmlspecialchars($table_nr) ?></h3>
                            </div>

                            <!-- DISPLAY -> match rules and settings based on single double and teams-->
                            <?php if ($league_infos["playing_format"] === "single"): ?>
                                <?php require "../assets/league_match_single.php" ?> 

                            <?php elseif ($league_infos["playing_format"] === "doubles"): ?>
                                <?php require "../assets/league_match_doubles.php" ?>   
                                
                            <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                                <?php require "../assets/league_match_teams.php" ?> 
                            <?php endif ?>


                        </div>

                    <?php if (count($league_matches) === $counter): ?>    
                    </div>
                    <?php endif ?>

                    <?php endforeach ?>
                </div>

            <!-- </div> -->

        </section>          
    </main>

<?php require "../assets/footer.php" ?>
<script src="../js/header.js"></script>
<script>
     $(document).ready(function () {
        
        $('.match_button').click(function (e) { 
            e.preventDefault();
    
    
            if ($(this).val() === "Zapnúť"){
                
                let match_id = $(this).closest('.btnAndGame').find('.match_id').val();
                let league_id = $(this).closest('.btnAndGame').find('.league_id').val();
                
                let match_button = $(this);
    
                $.ajax({
                    method: "POST",
                    url: "change-status-match.php",
                    data: {
                        'click_start_btn': true,
                        'match_id': match_id,
                        'league_id': league_id,
                    },
                    success: function (response) {
    
                        if (response) {
                            
                            $.each(response, function (Key, value) { 
    
                                
                                // added color for current league match Table number text
                                match_button.parents('div').parent().eq(3).find('.tableNr h3').css(value['csstext'], value['csscolor']);
    
                                // added color for current league match Table number background
                                match_button.parents('div').parent().eq(3).find('.tableNr').addClass(value['addedclass']);
    
                                // added color for current league match - match info
                                match_button.parents('div').eq(1).addClass(value['addedclass']);
    
                                // change value for match button after current match is started
                                match_button.val(value['match_button']);
    
                            }); 
    
                        }        
                                    
                    }
                });
    
            }
    
            
            if ($(this).val() === "Čaká"){
                
                let match_id = $(this).closest('.btnAndGame').find('.match_id').val();
                let league_id = $(this).closest('.btnAndGame').find('.league_id').val();
                $('#checkFinish').hide();
                $('#checkFinish').prop('checked',false);
                $('#player1-score').prop("readonly", true);
                $('#player2-score').prop("readonly", true);
                $('#saveBtn').val("Potvrdiť");
    
                $('.chooseTable').show();
    
    
                $.ajax({
                    method: "POST",
                    url: "change-status-match.php",
                    data: {
                        'click_view_btn': true,
                        'match_id': match_id,
                        'league_id': league_id,
                    },
                    success: function (response) {
    
                        $.each(response, function (Key, value) { 
    
                            $('#modal-league-id').val((value['league_id']));
                            $('#match-id').val((value['match_id']));
                            $('#player1-name').text((value['player1_firstname']) + " " + (value['player1_second_name']));
                            $('#player1-score').val((value['score_1']))
                            $('#player2-name').text((value['player2_firstname']) + " " + (value['player2_second_name']));
                            $('#player2-score').val((value['score_2']))
    
                        });
    
                        document.querySelector("#modal").showModal();
                        
                            
                    }
                });
            };
            if ($(this).val() === "Upraviť"){
                
                let match_id = $(this).closest('.btnAndGame').find('.match_id').val();
                let league_id = $(this).closest('.btnAndGame').find('.league_id').val();
    
                $('#checkFinish').show();
                $('#checkFinish').prop('checked',false);
                $('#player1-score').prop("readonly", false);
                $('#player2-score').prop("readonly", false);
                $('#saveBtn').val("Uložiť");
    
                $('.chooseTable').hide();
                
                $.ajax({
                    method: "POST",
                    url: "change-status-match.php",
                    data: {
                        'click_view_btn': true,
                        'match_id': match_id,
                        'league_id': league_id,
                    },
                    success: function (response) {
    
                        $.each(response, function (Key, value) { 
    
                            $('#modal-league-id').val((value['league_id']));
                            $('#match-id').val((value['match_id']));
                            $('#player1-name').text((value['player1_firstname']) + " " + (value['player1_second_name']));
                            $('#player1-score').val((value['score_1']));
                            $('#player2-name').text((value['player2_firstname']) + " " + (value['player2_second_name']));
                            $('#player2-score').val((value['score_2']));
    
                        });
                        // $('#main-match').html(response);
                        document.querySelector("#modal").showModal();
                        
                        
                            
                    }
                });
            };
            
            
        });
    
    
        $('#saveBtn').click(function (e) { 
            e.preventDefault();
    
    
            
    
            if ($(this).val() === "Uložiť"){
                
                let modal_match_id = $(this).closest('.matchInfo').find('.modal_match_id').val();
                let modal_league_id = $(this).closest('.matchInfo').find('.modal_league_id').val();
                let modal_score_1 = $(this).closest('.matchInfo').find('#player1-score').val();
                let modal_score_2 = $(this).closest('.matchInfo').find('#player2-score').val();
                let checked = $('input[name=checkFinish]:checked');
                
    
                if(checked.length){
                    checked = true;
                } else {
                    checked = false;
                };
    
                $.ajax({
                    method: "POST",
                    url: "change-status-match.php",
                    data: {
                        'save_data_btn': true,
                        'match_id': modal_match_id,
                        'league_id': modal_league_id,
                        'score_1': modal_score_1,
                        'score_2': modal_score_2,
                        'match_finished': checked,
                    },
                    success: function (response) {
    
                        let match_id_to_change = $('.match_id').filter(function() { return this.value == modal_match_id });
    
                        let score_1 = match_id_to_change.parents('div').eq(1).find('.pl1-label');
                        let score_2 = match_id_to_change.parents('div').eq(1).find('.pl2-label');
    
                        $.each(response, function (Key, value) { 
    
                            score_1.text((value['score_1']));
                            score_2.text((value['score_2']));
    
                            if (checked) {
    
                                match_id_to_change.parents('div').parent().eq(3).find('.tableNr h3').text((value['closeMatch']));
    
                                // added color for current league match Table number background
                                match_id_to_change.parents('div').parent().eq(3).find('.tableNr').removeClass(value['removeclass']).addClass(value['addedclass']);
                        
                                
                                // added color for current league match - match info
                                match_id_to_change.parents('div').eq(1).removeClass(value['removeclass']).addClass(value['addedclass']);
                                
    
                                // change visibility for match button after current match is finished
                                match_id_to_change.closest('.btnAndGame').find('.match_button').hide();
                            }
    
                        });
    
                        document.querySelector("#modal").close();
                            
                    }
                });
    
            }
    
    
            if ($(this).val() === "Potvrdiť"){
    
                let modal_match_id = $(this).closest('.matchInfo').find('.modal_match_id').val();
                let modal_league_id = $(this).closest('.matchInfo').find('.modal_league_id').val();
                let modal_score_1 = $(this).closest('.matchInfo').find('#player1-score').val();
                let modal_score_2 = $(this).closest('.matchInfo').find('#player2-score').val();
                let modal_table_number = $('#table-number').find(":selected").val();
    
    
                $.ajax({
                    method: "POST",
                    url: "change-status-match.php",
                    data: {
                        'conf_table_and_match': true,
                        'match_id': modal_match_id,
                        'league_id': modal_league_id,
                        'score_1': modal_score_1,
                        'score_2': modal_score_2,
                        'table_number': modal_table_number,
                    },
                    success: function (response) {
                        
                        let match_id_to_change = $('.match_id').filter(function() { return this.value == modal_match_id });
    
                        let score_1 = match_id_to_change.parents('div').eq(1).find('.pl1-label');
                        let score_2 = match_id_to_change.parents('div').eq(1).find('.pl2-label');
                        let table_nr_match = match_id_to_change.parents('div').parent().eq(3).find('.tableNr h3')
    
                        $.each(response, function (Key, value) { 
                            
                            score_1.text((value['score_1']));
                            score_2.text((value['score_2']));
                            table_nr_match.text("T " + (value['table_number']));
    
                            // added color for current league match Table number text
                            table_nr_match.css(value['csstext'], value['csscolor']);
    
                            // added color for current league match Table number background
                            match_id_to_change.parents('div').parent().eq(3).find('.tableNr').removeClass(value['removeclass']).addClass(value['addedclass']);
                            
                            
    
                            // added color for current league match - match info
                            match_id_to_change.parents('div').eq(1).removeClass(value['removeclass']).addClass(value['addedclass']);
                            
    
                            // change value for match button after current match is started
                            // match_id_to_change.val(value['match_button']);
                            match_id_to_change.closest('.btnAndGame').find('.match_button').val(value['match_button']);
    
                        });
    
                        document.querySelector("#modal").close();
                            
                    }
                });
            }
            
    
        });
    
    });
</script>
</body>
</html>