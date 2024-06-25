<?php
if($_SESSION['uzivatel_role'] != 'administrator'){
    header('Location: ../index.php');
    exit();
}