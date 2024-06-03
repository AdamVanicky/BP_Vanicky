<?php

require_once '../inc/db.php';

require '../inc/user_required.php';

require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$chyby=[];
if(!empty($_POST))
{
    #region kontrola informací o zákazníkovi
    if(empty(trim($_POST['jmeno']))){
        $chyby['jmeno'] = 'Je nutno zadat jméno příjemce';
    }
    if(empty(trim($_POST['email']))){
        $chyby['email'] = 'Je nutno zadat email příjemce';
    }
    if(empty(trim($_POST['adresaUlice'])) || empty(trim($_POST['adresaPSC']))){
        $chyby['adresa'] = 'Je nutno zadat kompletní adresu příjemce';
    }
    if(empty(trim($_POST['phone']))){
        $chyby['phone'] = 'Je nutno zadat telefonní číslo příjemce';
    }

    $order_jmeno = htmlspecialchars(trim($_POST['jmeno']));
    $order_email = htmlspecialchars(trim($_POST['email']));
    $order_adresa = htmlspecialchars(trim($_POST['adresaUlice'])).';'.htmlspecialchars(trim($_POST['adresaPSC']));
    $order_phone = filter_var(trim($_POST['phone']),FILTER_SANITIZE_NUMBER_INT);
    $order_amount = filter_var($_POST['amount'],FILTER_SANITIZE_NUMBER_INT);

    #endregion

    if(empty($_POST['popis'])){
        $chyby['popis'] = 'Je důležité specifikovat, jaký obraz si přejete';
    }
    $order_popis = htmlspecialchars(trim($_POST['popis']));

    $uploadDirectory = '../images/uploads/';
    if(move_uploaded_file($_FILES['pic']['tmp_name'],$uploadDirectory.basename($_FILES['pic']['name']))) {
        $order_pic = $_FILES['pic']['name'];
    }else{
        $order_pic = '';
    }
    if(empty(trim($_POST['firma']))){
        $company=null;
    }else{
        $company=trim($_POST['firma']);
    }
    if(empty(trim($_POST['ico']))){
        $ico=null;
        $dic = null;
    }else{
        $ico=filter_var(trim($_POST['ico']),FILTER_SANITIZE_NUMBER_INT);;
        if(strlen($ico) != 8 || !is_numeric($ico)){
            $chyby['ico'] = 'Zadejte správné IČO';
        }
        $dic = 'CZ'.$ico;
    }


    if(empty($chyby)){
$saveQuery=$db->prepare('INSERT INTO bp_orders(stav, user_id, jmeno, email,firma,ico,dic, adresa, telefon,pocet, popis,keyword, obrazovy_material) VALUES (:stav,:user, :jmeno,:email,:firma,:ico,:dic,:adresa,:telefon,:pocet,:popis,:keyword,:obrazovy_material)');
$saveQuery->execute([
    ':stav'=>'Jednání',
        ':user'=>$_SESSION['uzivatel_id'],
        ':jmeno'=>$order_jmeno,
        ':email'=>$order_email,
    ':firma'=>$company,
    ':ico'=>$ico,
    ':dic'=>$dic,
    ':adresa'=>$order_adresa,
    ':telefon'=>$order_phone,
    ':pocet'=>$order_amount,
    ':popis'=>$order_popis,
    ':keyword'=>'Nová objednávka',
    ':obrazovy_material'=>$order_pic
]);

        define('PRIJEMCE', $order_email);
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
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
        $mailer->Subject='Děkuji za zaslání vašeho návrhu';

        $mailer->isHTML(true);
        $mailer->Body=file_get_contents('../inc/email_content.php');

        if ($mailer->send()) {
            $mailer->addAddress('adam.vanicky@gmail.com');

            if ($mailer->send()) {
                header('Location: ../index.php');
                exit();
            } else {
                echo "Vyskytla se chyba: " . $mailer->ErrorInfo;
            }

        } else {
            echo "Vyskytla se chyba: " . $mailer->ErrorInfo;
        }


    }
}





?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Objednávka</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php include '../inc/navbar.php';?>

<h1>Objednávka</h1>

<p style="color:white; font-size: 20px; margin-left: 5em; margin-right: 5em; margin-top: 15px;">
    Je mi ctí, že jste si mne zvolili pro naplnění vašeho přání o obraz na míru. Než ale budu schopna začít s tvorbou onoho vysněného díla, budu potřebovat pár doplňujících informací.

    Srdečně vás tedy prosím, aby jste se přesunuli na nižší část této stránky a vyplnili krátky formulář. S jeho pomocí zjistím více o vás i o vašem přání.

    Před vyplněním formuláře vás prosím a přečtení, jaké jsou <a href="../static/obchodni_podminky.php" style="color: #e65321;">obchodní podmínky</a> a jak budou <a href="../static/ochrana_osobnich_udaju.php" style="color: #e65321;">zpracovávány vaše osobní údaje</a>. Pokud se vším souhlasíte, pak ve formuláře zaškrtněte pole se souhlasem.

</p>

<form method="post" enctype="multipart/form-data">

    <div style="float: right; width: 49%;">
        <label for="firma">Název firmy</label><br>
        <input type="text" name="firma" id="firma" value="" style="width: 100%;"/><br>
    </div>
    <label for="jmeno">Jméno příjemce<span style="color: red;"> *</span></label><br>
    <input type="text" name="jmeno" id="jmeno" value="" style="width: 50%;" required/><br>
    <?php
    if (!empty($chyby['jmeno'])){
        echo '<div style="background: red; color: white;">'.$chyby['jmeno'].'</div>';
    }
    ?>


    <div style="float: right; width: 49%;">
        <label for="ico">IČO</label><br>
        <input type="text" name="ico" id="ico" value="" style="width: 50%;" /><br>
        <?php
        if (!empty($chyby['ico'])){
            echo '<div style="background: red; color: white;">'.$chyby['ico'].'</div>';
        }
        ?>
    </div>
    <label for="email">Email příjemce<span style="color: red;"> *</span></label><br>
    <input type="email" name="email" id="email" value="" style="width: 50%;" required/><br>
    <?php
    if (!empty($chyby['email'])){
        echo '<div style="background: red; color: white;">'.$chyby['email'].'</div>';
    }
    ?>

    <label for="adresaUlice">Adresa: Ulice a číslo popisné<span style="color: red;"> *</span></label><br>
    <input type="text" id="adresaUlice" name="adresaUlice" required style="width: 50%;"/><br>

    <label for="adresaPSC">Adresa: Město a PSČ<span style="color: red;"> *</span></label><br>
    <input type="text" id="adresaPSC" name="adresaPSC" required style="width: 50%;"/><br>
    <?php
    if (!empty($chyby['adresa'])){
        echo '<div style="background: red; color: white;">'.$chyby['adresa'].'</div>';
    }
    ?>
    <label for="phone">Telefonní číslo<span style="color: red;"> *</span></label><br>
    <input type="tel" id="phone" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{3}" required style="width: 50%;"/><br>
    <?php
    if (!empty($chyby['phone'])){
        echo '<div style="background: red; color: white;">'.$chyby['phone'].'</div>';
    }
    ?>

    <label for="amount">Počet obrazů</label><br>
    <input type="number" id="amount" name="amount" value="1" style="width: 50%;"/><br>

    <label for="popis">Popis objednávky<span style="color: red;"> *</span></label><br>
    <textarea name="popis" id="popis" style="min-width: 100%; max-width: 100%; min-height: 80px;" required></textarea><br>
    <?php
    if (!empty($chyby['popis'])){
        echo '<div style="background: red; color: white;">'.$chyby['popis'].'</div>';
    }
    ?>

    <label for="pic">Obrazový materiál</label><br>
    <input type="file" name="pic" id="pic" style="width: 50%;"/><br>

    <input type="checkbox" name="consent" id="consent" required>
    <label for="consent">Souhlasím se <a href="../static/ochrana_osobnich_udaju.php" style="color:#e65321;">zpracováním osobních údajů</a> a <a href="../static/obchodni_podminky.php" style="color:#e65321;">obchodními podmínkami</a><span style="color: red;"> *</span></label>

    <input type="submit" value="Odeslat návrh"/>

</form>

<?php include '../inc/footer.php' ?>
</body>
</html>

