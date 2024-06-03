<?php

    require_once '../inc/db.php';

require '../inc/user_required.php';


if($_SESSION['uzivatel_role'] == 2) {
    header('Location: ../admin/administrace.php');
}

$orderQuery = $db->prepare('SELECT * FROM bp_orders WHERE user_id=:id;');
$orderQuery->execute([
        ':id'=>$_SESSION['uzivatel_id']
]);

$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Informace o profilu</title>
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
            background: #1284C8;
        }

        #customers td, #customers th {
            border: 1px solid black;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #198F8C;
            color: white;
        }
    </style>
</head>
<body>

<?php include '../inc/navbar.php';?>

<h1>Informace o profilu</h1>

<h2>Uživatelské informace</h2>

<div style="">

    <table id="customers" style="margin: 0 auto; width: 30%;"">
    <tr>
        <th>Jméno</th>
        <td><?php echo htmlspecialchars($_SESSION['uzivatel_jmeno']).' '.htmlspecialchars($_SESSION['uzivatel_prijmeni']); ?></td>
    </tr>
    <tr>
        <th>Emailová adresa</th>
        <td><?php echo htmlspecialchars($_SESSION['uzivatel_email']); ?></td>
    </tr>
    <tr>
        <th>Celkový počet zadaných objednávek</th>
        <td><?php echo count($orders);?></td>
    </tr>
    <tr>
        <th>Datum registrace</th>
        <td><?php echo htmlspecialchars($_SESSION['uzivatel_dr']);?></td>
    </tr>
    </table>
    <?php echo '<button class="btn_bot" onclick="location.href=\'../user/uprava.php\'" style="width: 200px; margin: 0 auto; margin-top: 10px;">Upravit</button>';?>
    <?php echo '<button class="btn_bot" onclick="location.href=\'../user/delete.php?id='.$_SESSION['uzivatel_id'].'\'" style="width: 200px; margin: 0 auto; margin-top: 10px;">Smazat</button>';?>
</div>

<h2>Správa objednávek</h2>
<?php

if(!empty($orders)){
    echo'<table id="customers" style="margin: 0 auto; overflow: auto;">';
    echo'<tr>
        <th></th>
        <th>Poznávací název</th>
        <th>Stav</th>
        <th>Cena</th>
        <th></th>
</tr>';
    foreach($orders as $order){
        echo '<tr>';
        echo '<td>'.$order['id'].'</td>';
        echo '<td>'.htmlspecialchars($order['keyword']).'</td>';
        echo '<td>'.htmlspecialchars($order['stav']).'</td>';
        if(isset($order['cena'])){
            echo '<td>'.$order['cena'].',- Kč</td>';
        }else{
            echo '<td>Nebyla stanovena cena</td>';
        }
        echo '<td><a href="../order/komunikace.php?id='.$order['id'].'" style="color: #e65321;">Otevřít komunikaci</a></td>';
        echo '</tr>';
    }
    echo'</table>';
}
else{
    echo '<p style="color:white; font-size: 25px; margin-left: 5em; margin-top: 15px;">V tento moment nemáte žádnou zadanou objednávku. Můžete tak učinit <a href="../order/objednavka.php" style="color: #e65321;">zde</a></p>';
}
?>


<?php include '../inc/footer.php';?>
</body>
</html>
