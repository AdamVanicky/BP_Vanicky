<?php

session_start();

if(!isset($_SESSION["uzivatel_id"])){
    header('Location: ../user/prihlaseni.php');
    die();
}

$stmt = $db->prepare("SELECT * FROM bp_users WHERE id = ? LIMIT 1");
$stmt->execute(array($_SESSION["uzivatel_id"]));


$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser){
    session_destroy();
    header('Location: ../index.php');
    die();
}