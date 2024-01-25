<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/Url.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

if ($_SERVER["REQUEST_METHOD"] === "GET"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    var_dump($_GET);
} else {
    echo "Nepovolený prístup";
}
?>