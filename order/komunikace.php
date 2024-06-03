<?php
require_once '../inc/db.php';
require '../inc/user_required.php';

require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$orderQuery = $db->prepare('SELECT * FROM bp_orders WHERE id=:id LIMIT 1;');
$orderQuery->execute([
        ':id'=>$_GET['id']
]);
$order = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
if(!empty($order)) {
    $info = $order[0];
}else{
    $info = '';
}

$komQuery = $db->prepare('SELECT * FROM bp_communication WHERE order_id=:id;');
$komQuery->execute([
    ':id'=>$_GET['id']
]);
$komunikace = $komQuery->fetchAll(PDO::FETCH_ASSOC);

$errors=[];
if(!empty($_POST)){

        if(empty(trim($_POST['zprava']))){
            $errors['zprava']= 'Zpráva musí obsahovat nějaký text';
        }
        if(empty(trim($_POST['keyword']))){
            $order_keyword = $info['keyword'];
        }else{
            $order_keyword = htmlspecialchars(trim($_POST['keyword']));
        }


        if(empty($errors)) {
            $zprava = htmlspecialchars(trim($_POST['zprava']));
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $autor = $_SESSION['uzivatel_email'];
            $uploadDirectory = '../images/uploads/';
            if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadDirectory . basename($_FILES['pic']['name']))) {
                $com_pic = $_FILES['pic']['name'];
            } else {
                $com_pic = '';
            }
            $input = $db->prepare('INSERT INTO bp_communication(autor, order_id, zprava, obrazovy_material, edit) VALUES (:autor, :order_id,:zprava,:obrazovy_material, :edit);');
            $input->execute([
                ':autor' => $autor,
                ':order_id' => $id,
                ':zprava' => $zprava,
                ':obrazovy_material' => $com_pic,
                ':edit' => 'false'

            ]);


            define('PRIJEMCE', $info['email']);
            $mailer = new PHPMailer(true);
            $mailer->isSMTP();
            $mailer->Mailer = 'smtp';
            $mailer->Host = 'mail.webglobe.cz';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'no-reply@berankova-obrazy.cz';
            $mailer->Password = 'QaYxSw159753';
            $mailer->SMTPSecure = 'tls';
            $mailer->Port = 587;;

            $mailer->addAddress(PRIJEMCE);
            $mailer->setFrom('no-reply@berankova-obrazy.cz');

            $mailer->CharSet = 'utf-8';
            $mailer->Subject = 'Nová zpráva obdržena';

            $mailer->isHTML(true);
            $mailer->Body = file_get_contents('../inc/email_komunikace.php');

            if ($mailer->send()) {

                $mailer->addAddress('adam.vanicky@gmail.com');

                if ($mailer->send()) {
                    $location = 'Location: ../order/komunikace.php?id=' . $_GET['id'];
                    header($location);
                } else {
                    echo "Vyskytla se chyba: " . $mailer->ErrorInfo;
                }
            } else {
                echo "Vyskytla se chyba: " . $mailer->ErrorInfo;
            }
        }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Komunikace</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 50%;
            text-align: center;
            vertical-align: middle;
            margin-left: 9em;
        }

        #customers td, #customers th {
            border: 1px solid black;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: lightgray;}
        #customers tr:nth-child(odd){background-color: white;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #198F8C;
            color: white;
        }

        .hidden{
            display: none; !important;
        }
    </style>
</head>
<body>

<?php include '../inc/navbar.php';?>

<div style="position: relative;">
    <h1>Objednávka č. <?php echo $_GET['id'];?></h1>
    <?php echo '<button class="btn_bot" onclick="location.href=\'../user/uzivatelske_informace.php\'" style=" position: absolute; top: 8px; right: 16px; width: 100px;">Zpět</button>';

    if($_SESSION['uzivatel_role']==2) {
        echo '<button class="btn_bot" onclick="location.href=\'zmenit.php?id=' . $_GET['id'] . '\'" style=" position: absolute; top: 70px; right: 16px; width: 100px; padding:10px;">Změnit informace</button>';
    }
    ?>

</div>


<h2>Stav - <?php
    echo $info['stav'];

    ?></h2>

<h2>Objednávka a komunikace</h2>

<h3 style="color: white; text-align: center; padding-top: 10px; padding-bottom: 10px; font-size: 30px;">Informace z prvního zadání objednávky</h3>

<table id="customers" style="margin: 0 auto;">
    <?php
    if(!empty($info)){
        $adresa = explode(';', $info['adresa']);
    echo'<tr><th>Příjemce</th><td>'.htmlspecialchars($info['jmeno']).'</td></tr>';
        echo'<tr><th>Adresa</th><td>'.htmlspecialchars($adresa[0]).' '.htmlspecialchars($adresa[1]).'</td></tr>';
        echo'<tr><th>Telefon</th><td>+420 '.htmlspecialchars($info['telefon']).'</td></tr>';
        echo'<tr><th>Email</th><td>'.htmlspecialchars($info['email']).'</td></tr>';
        echo'<tr><th>Datum vložení</th><td>'.htmlspecialchars(date('d. m. Y',strtotime($info['datum_vlozeni']))).'</td></tr>';
        echo'<tr><th>Krátký popis</th><td>'.htmlspecialchars($info['keyword']).'</td></tr>';
        echo'<tr><th>Detailní popis</th><td>'.htmlspecialchars($info['popis']).'</td></tr>';
        echo '<tr><th>Obrazový materiál</th>';
        if($info['obrazovy_material'] != ''){
            echo'<td><a href="../images/uploads/'.$info['obrazovy_material'].'" target="_blank"><img src="../images/uploads/'.$info['obrazovy_material'].'" alt="Obrazový materiál k objednávce se nepovedlo načíst" style="width: 150px;"></a></td>';
        }
        else{
            echo '<td>Nebyl vložen</td>';
        }
        echo'</tr>';
    }

    ?>
</table>

    <h3 style="color: white; text-align: center; padding-top: 10px; padding-bottom: 10px; font-size: 30px;">Komunikace objednávky</h3>


        <button class="filter-option" data-filter="vy" tabindex="-1" onclick="filterCommunication(event)">Vy</button>
        <button class="filter-option" data-filter="<?php if($_SESSION['uzivatel_role']==2) { $output='Zákazník'; echo'zakaznik'; }else{ $output='Umělkyně'; echo 'umelkyne'; }  ?>" tabindex="-1" onclick="filterCommunication(event)"><?php echo $output;?></button>
        <button class="filter-option" data-filter="*" tabindex="-1" onclick="filterCommunication(event)">Vše</button>
<div style="height: 500px; overflow: auto;" id="scroller" class="messages">
    <?php
    if(empty($komunikace)){
        echo '<p style="color:white; font-size: 20px; margin-left: 8em; margin-top: 15px;">Není zde prozatím žádná komunikace, vyčkejte na mé vyjádření nebo napište první zprávu vy.</p>';
    }
    else{
        foreach($komunikace as $polozka){
            $minutes_to_add = 10;
            $time = strtotime($polozka['datum_vlozeni'].' + '.$minutes_to_add.' minute');
            if($polozka['autor']!=$_SESSION['uzivatel_email']){
                $barvaBack = '#202434';
                $barvaText = '#1284C8';
                $vypisAutor = $polozka['autor'];
                $posun = '-50px';
                if($_SESSION['uzivatel_role']==2) {
                    $class='zakaznik';
                }else{
                    $class='umelkyne';
                }
            }else{
                $barvaBack = '#1284C8';
                $barvaText = '#202434';
                $vypisAutor = $polozka['autor'].' (Vy)';
                $posun = '50px';
                $class='vy';
            }
            echo '<div style="background:'.$barvaBack.'; margin: 10px auto; padding: 12px; max-width: 800px; border-radius: 12px; transform: translateX('.$posun.'); overflow:auto;" class="'.$class.'">';
                echo '<p style="color: white;">Odeslal uživatel: '.htmlspecialchars($vypisAutor).'</p>';
                echo '<p style="color: white;">Dne: '.htmlspecialchars($polozka['datum_vlozeni']).'</p>';
                if($_SESSION['uzivatel_email'] == $polozka['autor'] && $time >= time()){
                    echo '<button class="btn_bot" onclick="location.href=\'edit.php?id='.$polozka['id'].'\'" style=" position: absolute; top: 8px; right: 16px; width: 100px;">Editovat</button>';
                }
                if($polozka['edit'] == 'true'){
                    echo '<p  style=" color:white; position: absolute; top: 22px; right: 100px; width: 100px;">Upraveno</p>';
                }
                echo '<p style="color: white; margin-top: 15px; padding: 10px; background: '.$barvaText.'; border-radius: 12px;">'.htmlspecialchars($polozka['zprava']).'</p>';
                if($polozka['obrazovy_material'] != ''){
                    echo '<a href="../images/uploads/'.$polozka['obrazovy_material'].'" target="_blank"><img src="../images/uploads/'.$polozka['obrazovy_material'].'" alt="" style="width: 150px; padding: 15px;"></a>';
                }


            echo '</div>';
        }
    }
    ?>
</div>

<?php
if($info['stav'] != 'Zamítnuto'){
echo '<form method="post" enctype="multipart/form-data">';

echo'<label for="zprava">Zpráva</label><br>
    <textarea name="zprava" id="zprava" style="min-width: 100%; max-width: 100%; min-height: 80px;"></textarea><br>';

if (!empty($errors['zprava'])){
echo '<div style="background: red; color: white;">'.$errors['zprava;'].'</div>';
}


echo'<label for="pic">Doplňující obrazový materiál</label><br>
    <input type="file" name="pic" id="pic" style="width: 50%;"/><br>';

echo'<input type="submit" value="Odeslat"/>
</form>';
}
?>

<?php include '../inc/footer.php';?>
<script>
    var objDiv = document.getElementById("scroller");
    objDiv.scrollTop = objDiv.scrollHeight;

    function filterCommunication(e) {
        const coms = document.querySelectorAll(".messages div");
        let filter = e.target.dataset.filter;
        if (filter === '*') {
            coms.forEach(com => com.classList.remove('hidden'));
        }  else {
            coms.forEach(com => {
                com.classList.contains(filter) ?
                    com.classList.remove('hidden') :
                    com.classList.add('hidden');
            });
        }
    }
</script>
</body>
</html>
