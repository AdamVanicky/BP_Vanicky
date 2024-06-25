<?php

session_start();

require_once '../inc/db.php';

if(!empty($_POST['email'])){

    $query=$db->prepare('SELECT * FROM bp_users WHERE email=:email LIMIT 1;');
    $query->execute([
        ':email' => $_POST['email']
    ]);

    $rows=$query->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($rows)){

        $uzivatelDB=$rows[0];
        if(password_verify($_POST['heslo'],$uzivatelDB['heslo'])){
            //login

            $_SESSION['uzivatel_id']=$uzivatelDB['id'];
            $_SESSION['uzivatel_jmeno']=$uzivatelDB['jmeno'];
            $_SESSION['uzivatel_prijmeni']=$uzivatelDB['prijmeni'];
            $_SESSION['uzivatel_email']=$uzivatelDB['email'];
            $_SESSION['uzivatel_dr'] = date('d.m.Y', strtotime($uzivatelDB['datum_registrace']));
            $_SESSION['uzivatel_role']=$uzivatelDB['role'];

            header("Location: ../index.php");
        }
        else{
            $formError = 'Tato kombinace jména a hesla je chybná!';
        }


    }
}?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Přihlášení</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
      <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
      <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
      <link rel="manifest" href="../site.webmanifest">
  </head>
  <body>

    <h1>Kateřina Beránková</h1>

    <h2>Přihlášení</h2>



    <form method="post">
      <label for="email">Váš email<span style="color: red;"> *</span></label><br/>
      <input type="email" name="email" id="email" value=""><br/><br/>

      <label for="heslo">Heslo<span style="color: red;"> *</span></label><br/>
      <input type="password" name="heslo" id="heslo" value=""><br/><br/>

        <?php
        if (!empty($formError)){
            echo '<p style="color:white; background-color: red; text-align: center;">'.$formError.'</p>';
        }
        ?>

      <input type="submit" value="Přihlásit se">
        <br/>

        <input type="button" onclick="location.href='registrace.php'" value="Nemáte uživatelský učet? Zaregistrujte se!"  style="height: 50px; width: 56%; font-size: 20px;">

        <input type="button" onclick="location.href='zapomenute_heslo.php'" value="Zapomněli jste heslo?" style="height: 50px; width: 33%; font-size: 20px;">
        <input type="button" onclick="location.href='../index.php'" value="Zpět" style="height: 50px; width: 9%; font-size: 20px;">
    </form>
  </body>
</html>