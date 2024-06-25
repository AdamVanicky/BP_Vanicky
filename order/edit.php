<?php
require_once '../inc/db.php';

require '../inc/user_required.php';


$komQuery = $db->prepare('SELECT * FROM bp_communication WHERE id=:id LIMIT 1;');
$komQuery->execute([
    ':id'=>$_GET['id']
]);
$komunikace = $komQuery->fetchAll(PDO::FETCH_ASSOC);
$zprava = $komunikace[0];

$orderQuery = $db->prepare('SELECT * FROM bp_orders WHERE id=:id LIMIT 1;');
$orderQuery->execute([
    ':id'=>$zprava['order_id']
]);
$order = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
if(!empty($order)) {
    $info = $order[0];
}else{
    $info = '';
}

$minutes_to_add = 10;
$time = strtotime($zprava['datum_vlozeni'].' + '.$minutes_to_add.' minute');
if($_SESSION['uzivatel_email'] != $zprava['autor'] || $time < time()){
    $location = 'Location: ../order/komunikace.php?id='.$_GET['id'];
    header($location);
    exit();
}

if(!empty($_POST)) {
    if (empty(trim($_POST['zprava']))) {
        $errors['zprava'] = 'Zpráva musí obsahovat nějaký text';
    }

    if(empty($errors)){
        $message = trim($_POST['zprava']);
        $autor = $_SESSION['uzivatel_email'];
            $input = $db->prepare('UPDATE bp_communication SET zprava=:zprava, edit=:edit WHERE id=:id');
            $input->execute([
                ':id'=>$_GET['id'],
                ':zprava'=>$message,
                ':edit'=>$_POST['edit']

            ]);



        $location = 'Location: ../order/komunikace.php?id='.$info['id'];
        header($location);
    }
}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Úprava zprávy</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>

<body>

<h1>Editace zprávy v komunikaci</h1>


<?php
if($info['stav'] != 'Zamítnuto'){
    echo '<form method="post" enctype="multipart/form-data">';

    echo'<label for="zprava">Zpráva<span style="color: red;"> *</span></label><br>
    <textarea name="zprava" id="zprava" style="min-width: 100%; max-width: 100%; min-height: 80px;" required>'.htmlspecialchars($zprava['zprava']).'</textarea><br>';

    if (!empty($errors['zprava'])){
        echo '<div style="background: red; color: white;">'.$errors['zprava;'].'</div>';
    }

    echo '<input name="edit" id="edit" type="text" value="true" hidden="hidden">';

    echo'<input type="submit" value="Odeslat"/>
</form>';
}

?>

</body>
</html>