<?php

require "./classes/Database.php";


// pripojenie do databázi
$database = new Database();
$connection = $database->connectionDB();

$sql = "SELECT *
        FROM player_user";

$stmt = $connection->prepare($sql);

$stmt->execute();

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($players);


