<?php
require '../inc/db.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

if(!empty($_POST)){

    if(empty($_POST['heslo']) || mb_strlen($_POST['heslo'], 'utf-8')<5){

        $chyby="Hesla musí být delší než 5 znaků";
    }elseif(empty($_POST['heslo2']) || $_POST['heslo'] != $_POST['heslo2']){
        $chyby="Hesla se neshodují";

    }

    if(empty($chyby)){
        $query=$db->prepare('UPDATE bp_users SET heslo=:heslo WHERE id=:id');
        $query->execute([
            ':heslo' => password_hash($_POST['heslo'], PASSWORD_DEFAULT),
            ':id'=> $_GET['user']
        ]);

        header('Location: prihlaseni.php');
    }

}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Přihlášení</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
</head>
<body>

<h1>Kateřina Beránková</h1>

<h2>Obnova hesla</h2>

<form method="post">
    <label for="heslo">Nové heslo</label><br/>
    <input type="password" name="heslo" id="heslo" value="" required><br/><br/>

    <label for="heslo2">Potvrdit nové heslo</label><br/>
    <input type="password" name="heslo2" id="heslo2" value="" required><br/><br/>

    <?php
    if (!empty($chyby)){
        echo '<p style="color:white; background-color: red; text-align: center;">'.$chyby.'</p>';
    }
    ?>

    <input type="submit" value="Změnit heslo">
    <br/>
</form>
</body>
</html>