<?php

require_once '../inc/db.php';
require '../inc/user_required.php';

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

$errors=[];
if(!empty($_POST)){
    if ($_POST['status'] === '') {
        $stav = $info['stav'];
    } else {
        $stav = $_POST['status'];
    }
    if (empty(trim($_POST['keyword']))) {
        $order_keyword = $info['keyword'];
    } else {
        $order_keyword = htmlspecialchars(trim($_POST['keyword']));
    }
    if ($_POST['cena'] > 0) {
        $cena = $_POST['cena'];
    } else {
        $cena = $info['cena'];
    }
    if ($_POST['paid'] > 0) {
        $paid = $_POST['paid'];
    } else {
        $paid = $info['zaplaceno'];
    }


    $zmena = $db->prepare('UPDATE bp_orders SET stav=:stav,cena=:cena,zaplaceno=:zaplaceno, keyword=:keyword WHERE id=:id');
    $zmena->execute([
        ':stav' => $stav,
        ':cena' => $cena,
        ':zaplaceno' => $paid,
        ':keyword' => $order_keyword,
        ':id' => $_GET['id']
    ]);

    $location = 'Location: ../order/komunikace.php?id=' . $_GET['id'];
    header($location);
    exit();
}
    ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Komunikace</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
</head>
<body>
<?php
echo '<form method="post" enctype="multipart/form-data">';

echo'
<label for="status">Změnit stav</label>
            <select name="status" id="status" style="width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; text-align: center;">
                <option value="" selected>--nezměněno--</option>
                <option value="Zpracováváno" >Zpracováváno</option>
                <option value="Zamítnuto" >Zamítnuto</option>
                <option value="Jednání" >Jednání</option>
            </select>';

echo'<label for="cena">Stanovit cenu objednávky</label><br>
             <input type="number" name="cena" id="cena" style="min-width: 100%; max-width: 100%; min-height: 80px;" value="'.($info['cena'] > 0 ? $info['cena']: 0).'"><br>';

echo'<label for="paid">Zákazník již zaplatil</label><br>
             <input type="number" name="paid" id="paid" style="min-width: 100%; max-width: 100%; min-height: 80px;" value="'.$info['zaplaceno'].'"><br>';

echo'<label for="keyword">Zkuste definovat pár slovy (maximálně 5 slov) základní charakteristiku objednávky</label><br>
             <input type="text" name="keyword" id="keyword" style="min-width: 100%; max-width: 100%; min-height: 80px;" value="'.$info['keyword'].'"><br>';


echo'<input type="submit" value="Odeslat"/>
</form>';
?>
</body>
</html>