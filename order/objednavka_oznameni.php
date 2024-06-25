<?php

require_once '../inc/db.php';

require '../inc/user_required.php';

$orderQuery = $db->prepare('SELECT * FROM bp_orders WHERE autor=:autor;');
$orderQuery->execute([
    ':autor'=>$_SESSION['uzivatel_email']
]);
$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
$ids = [];
foreach ($orders as $order){
    $ids[] = $order['id'];
}

$order_id = max($ids);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Objednávka</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>

<?php include '../inc/navbar.php';?>

<img src="../images/Logo.jpg" width="200" height="196" style="display: block;
  margin: 30px auto;">
<h1>Děkuji za Vaši objednávku</h1>
<p style="color:white; font-size: 30px; margin: 20px; text-align: center;">Objednávka č. <?php echo $order_id; ?></p>

<p style="color:white; font-size: 30px; margin: 20px; text-align: center;">Náš systém Vám zaslal email potvrzující přijetí Vaší objednávky</p>
<button class="btn_bot" onclick="location.href='../user/uzivatelske_informace.php'" style="font-size: 17px; width: 250px;">Zobrazit mé objednávky zde</button>


<?php include '../inc/footer.php';?>
</body>
</html>

