<?php
session_start();

require_once '../inc/db.php';
$chyby=[];
if(isset($_POST['edit'])){
    if(empty($_POST['jmeno'])){
        $chyby[] = 'Musíte zadat nové jméno';
    }
    if(empty($_POST['prijmeni'])){
        $chyby[] = 'Musíte zadat nové příjmení';
    }
    if(empty($_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
        $chyby[] = 'Musíte zadat platný email';
    }
    if(!empty($_POST['heslo']) && mb_strlen($_POST['heslo'], 'utf-8')<5){

        $chyby[]="Hesla musí být delší než 5 znaků";
    }elseif(!empty($_POST['heslo2']) || $_POST['heslo'] != $_POST['heslo2']){
        $chyby[]="Hesla se neshodují";

    }
    if(empty(trim($_POST['phone']))){
        $chyby[] = 'Je nutno zadat telefonní číslo příjemce';
    }


    $id = $_SESSION['uzivatel_id'];
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $email = $_POST['email'];
    $tel = $_POST['phone'];


    $sel = $db->prepare('SELECT * FROM bp_users WHERE id=:id LIMIT 1');
    $sel->execute([
            ':id'=>$id
    ]);

    $mezi = $sel->fetchAll(PDO::FETCH_ASSOC);
    $shoda = $mezi[0];

    if($shoda['id'] == $id){
        if(empty($_POST['heslo'])){

                $edit =$db->prepare('UPDATE bp_users SET jmeno=:jmeno, prijmeni=:prijmeni, email=:email, telefon=:telefon WHERE id=:id');
                $edit->execute([
                    ':id'=>$id,
                    ':jmeno'=>$jmeno,
                    ':prijmeni'=>$prijmeni,
                    ':email'=>$email,
                    ':telefon'=>$tel
                ]);

        }
        else{
            $edit =$db->prepare('UPDATE bp_users SET jmeno=:jmeno, prijmeni=:prijmeni, email=:email, telefon=:telefon,heslo=:heslo WHERE id=:id');
            $edit->execute([
                ':id'=>$id,
                ':jmeno'=>$jmeno,
                ':prijmeni'=>$prijmeni,
                ':email'=>$email,
                ':telefon'=>$tel,
                ':heslo'=>password_hash($_POST['heslo'], PASSWORD_DEFAULT)
            ]);
        }


        $_SESSION['uzivatel_id']=$id;
        $_SESSION['uzivatel_jmeno']=$jmeno;
        $_SESSION['uzivatel_prijmeni']=$prijmeni;
        $_SESSION['uzivatel_email']=$email;
        $_SESSION['uzivatel_tel']=$tel;

        header('Location: ../user/uzivatelske_informace.php');
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Úprava osobních údajů</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>
<body>


<h1>Kateřina Beránková</h1>

<h2>Úprava osobních údajů</h2>


<form method="post">

    <label for="jmeno">Jméno</label><span style="color: red;"> *</span><br>
    <input type="text" name="jmeno" id="jmeno" value="<?php echo htmlspecialchars($_SESSION['uzivatel_jmeno']); ?>" required/><br>

    <label for="prijmeni">Příjmení</label><span style="color: red;"> *</span><br>
    <input type="text" name="prijmeni" id="prijmeni" value="<?php echo htmlspecialchars($_SESSION['uzivatel_prijmeni']); ?>" required/><br>

    <label for="email">E-mailová adresa</label><span style="color: red;"> *</span><br>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['uzivatel_email']); ?>" required/><br>

    <label for="phone">Telefonní číslo<span style="color: red;"> *</span></label><br>
    <input type="tel" id="phone" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{3}" required value="<?php echo htmlspecialchars($_SESSION['uzivatel_tel']); ?>"/><br>

    <hr>
    <label for="heslo">Změnit heslo</label><br>
    <input type="password" name="heslo" id="heslo" value=""/><br>

    <label for="heslo2">Potvrzení nového hesla</label><br>
    <input type="password" name="heslo2" id="heslo2" value=""/><br>

    <input type="checkbox" name="consent" id="consent" required>
    <label for="consent">Souhlasím se <a href="../static/ochrana_osobnich_udaju.php" style="color:#e65321;">zpracováním nových osobních údajů</a></label>

    <?php

    if(!empty($chyby)){
        echo '<div style="background-color:red; color:white; text-align: center;">'.implode('<br>', $chyby).'</div>';
    }

    ?>

    <input type="submit" name="edit" value="Změnit"> <br>
    <input type="button" onclick="location.href='uzivatelske_informace.php'" value="Zpět" style="height: 50px; width: 49%; font-size: 20px;">
</form>

</body>
</html>