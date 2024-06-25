<?php
require '../inc/db.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

if(!empty($_POST)){


    if(empty($_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
        $formError="Nutno zadat platný e-mail!";
    }else{
        $formError = '';
    }
    $query=$db->prepare('SELECT * FROM bp_users WHERE email=:email LIMIT 1;');
    $query->execute([
        ':email' => $_POST['email']
    ]);
    $rows=$query->fetchAll(PDO::FETCH_ASSOC);


    if(empty($rows)){
        $formError = 'Uživatel s tímto emailem není v databázi';
    }else{
        $row = $rows[0];
    }


    if($formError == ''){


        define('PRIJEMCE', $_POST['email']);
        $mailer = new PHPMailer(true);
        $mailer->Mailer = 'smtp';
        $mailer->Host = 'mail.webglobe.cz';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'no-reply@berankova-obrazy.cz';
        $mailer->Password = 'QaYxSw159753';
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = 587;

        $mailer->addAddress(PRIJEMCE);
        $mailer->setFrom('no-reply@berankova-obrazy.cz');

        $mailer->CharSet='utf-8';
        $mailer->Subject='Obnova hesla';

        $mailer->isHTML(true);

        $body = '<h1>Vážený uživateli</h1>

<p>Tento email byl zadán pro zaslání odkazu na obnovu hesla. Tak můžete učinit <a href="https://eso.vse.cz/~vana23/SP/user/obnova_hesla.php?user='.$row['id'].'">zde</a></p>

<hr>
<p>Na tento email neodpovídejte. Byl automaticky vygenerován z <a href="https://eso.vse.cz/~vana23/SP/index.php">této webové stránky</a>.</p>';
        $mailer->Body=$body;

        if ($mailer->send()) {
            header('Location: ../index.php');
        } else {
            echo "Vyskytla se chyba: " . $mailer->ErrorInfo;
        }
    }


}
?><!DOCTYPE html>
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

<h2>Obnova hesla</h2>

<form method="post">
    <label for="email">Zadejte email pro zaslání odkazu pro změnu hesla</label><br/>
    <input type="email" name="email" id="email" value=""><br/><br/>

    <?php
    if (isset($formError)){
        echo '<p style="color:white; background-color: red; text-align: center;">'.$formError.'</p>';
    }
    ?>

    <input type="submit" value="Odeslat">
    <br/>

    <input type="button" onclick="location.href='prihlaseni.php'" value="Zpět">
</form>
</body>
</html>
