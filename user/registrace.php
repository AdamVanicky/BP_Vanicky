<?php
session_start();

require '../inc/db.php';

if (!empty($_POST)) {

    $chyby = [];
    if(empty($_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
        $chyby[]="Nutno zadat platný e-mail!";
    }else{
        $query=$db->prepare('SELECT * FROM bp_users WHERE email=:email LIMIT 1;');
        $query->execute([
            ':email' => $_POST['email']
        ]);
        $rows=$query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($rows)){
            $chyby[]="Uživatel s tímto emailem již existuje";
        }
    }
    if(empty($_POST['heslo']) || mb_strlen($_POST['heslo'], 'utf-8')<5){

        $chyby[]="Hesla musí být delší než 5 znaků";
    }elseif(empty($_POST['heslo2']) || $_POST['heslo'] != $_POST['heslo2']){
        $chyby[]="Hesla se neshodují";

    }
    if(empty(trim($_POST['phone']))){
        $chyby[] = 'Je nutno zadat telefonní číslo příjemce';
    }


    if(empty($chyby)){
        $query=$db->prepare('INSERT INTO bp_users(jmeno, prijmeni, email,telefon, heslo,role) VALUES(:jmeno, :prijmeni, :email,:telefon, :heslo, :role);');
        $query->execute([
                ':jmeno'=>$_POST['jmeno'],
            ':prijmeni'=>$_POST['prijmeni'],
            ':email' => $_POST['email'],
            ':telefon'=>$_POST['phone'],
            ':heslo' => password_hash($_POST['heslo'], PASSWORD_DEFAULT),
            ':role'=>'uzivatel'
        ]);

        $query=$db->prepare('SELECT * FROM bp_users WHERE email=:email LIMIT 1;');
        $query->execute([
            ':email' => $_POST['email']
        ]);
        $rows=$query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($rows)){

            $uzivatelDB=$rows[0];
            $_SESSION['uzivatel_id']=$uzivatelDB['id'];
            $_SESSION['uzivatel_jmeno']=$uzivatelDB['jmeno'];
            $_SESSION['uzivatel_prijmeni']=$uzivatelDB['prijmeni'];
            $_SESSION['uzivatel_email']=$uzivatelDB['email'];
            $_SESSION['uzivatel_tel']=$uzivatelDB['telefon'];
            $_SESSION['uzivatel_dr'] = date('d.m.Y', strtotime($uzivatelDB['datum_registrace']));
            $_SESSION['uzivatel_role']=$uzivatelDB['role'];

            header('Location: ../index.php');
        }
    }

}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Registrace</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>
<body>

<h1>Kateřina Beránková</h1>

<h2>Nová registrace</h2>


<form method="post">



    <label for="jmeno">Jméno<span style="color: red;"> *</span></label><br>
    <input type="text" name="jmeno" id="jmeno" value="" required/><br>

    <label for="prijmeni">Příjmení<span style="color: red;"> *</span></label><br>
    <input type="text" name="prijmeni" id="prijmeni" value="" required/><br>

    <label for="email">E-mailová adresa<span style="color: red;"> *</span></label><br>
    <input type="email" name="email" id="email" value="" required/><br>

    <label for="phone">Telefonní číslo<span style="color: red;"> *</span></label><br>
    <input type="tel" id="phone" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{3}" required style="width: 50%;" placeholder="123321123"/><br>

    <label for="heslo">Heslo<span style="color: red;"> *</span></label><br>
    <input type="password" name="heslo" id="heslo" value="" required/><br>

    <label for="heslo2">Potvrzení hesla<span style="color: red;"> *</span></label><br>
    <input type="password" name="heslo2" id="heslo2" value="" required/><br>

    <input type="checkbox" name="consent" id="consent" required>
    <label for="consent">Souhlasím se <a href="../static/ochrana_osobnich_udaju.php" style="color:#e65321;">zpracováním osobních údajů</a><span style="color: red;"> *</span></label>

    <?php

    if(!empty($chyby)){
        echo '<div style="background-color:red; color:white; text-align: center;">'.implode('<br>', $chyby).'</div>';
    }

    ?>

    <input type="submit" value="Zaregistrovat se"> <br><input type="button" onclick="location.href='prihlaseni.php'" value="Máte uživatelský učet? Přihlašte se!" style="height: 50px; width: 50%; font-size: 20px;">
    <input type="button" onclick="location.href='../index.php'" value="Zpět" style="height: 50px; width: 49%; font-size: 20px;">
</form>

</body>
</html>
