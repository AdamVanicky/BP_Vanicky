<?php

require '../inc/db.php';

require '../inc/user_required.php';

$special_addresses= ['vana23@vse.cz', 'adam.vanicky@gmail.com', 'katka.berankova1@gmail.com'];

if(in_array($_SESSION['uzivatel_email'], $special_addresses)) {

    $delete = $db->prepare('DELETE FROM bp_users WHERE id=?');
    $delete->execute([$_GET['id']]);

    header('Location: ../user/uzivatelske_informace.php');
    exit();
}
