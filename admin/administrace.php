<?php
require_once '../inc/db.php';

require '../inc/user_required.php';

$orderQuery = $db->prepare('SELECT * FROM bp_orders;');
$orderQuery->execute();
$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);

$usersQuery = $db->prepare('SELECT * FROM bp_users WHERE id!=:id');
$usersQuery->execute([
        ':id'=>$_SESSION['uzivatel_id']
]);
$users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

$catsQuery = $db->prepare('SELECT * FROM bp_themes');
$catsQuery->execute();
$cats = $catsQuery->fetchAll(PDO::FETCH_ASSOC);

$techQuery = $db->prepare('SELECT * FROM bp_techniques');
$techQuery->execute();
$techs = $techQuery->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
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
            text-align: center;
            background-color: #198F8C;
            color: white;
        }

        .tab{
            overflow: hidden;
            background: #198F8C;
            margin-left: 5em;
            margin-right: 5em;
            margin-top: 10px;
        }

        .tab button{
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
            color: white;
        }
        .tab button:hover {
            background-color: #202434;
        }
        .tab button.active {
            background-color: #202434;
        }
        .tabContent {
            display: none;
            padding: 15px 12px;
            border: none;
            background-color: #202434;
            margin-left: 5em;
            margin-right: 5em;
        }

        .hidden{
            display: none;
        }
    </style>
</head>
<body>

<?php include '../inc/navbar.php';?>

<h1>Informace o profilu</h1>

<div class="tab">
    <button class="tablinks active" onclick="openTab(event, 'information')">Uživatelské informace</button>
    <button class="tablinks" onclick="openTab(event, 'image_data')">Přidání techniky nebo tématiky</button>
    <button class="tablinks" onclick="openTab(event, 'gallery')">Rozšíření galerie</button>
    <button class="tablinks" onclick="openTab(event, 'users')">Výpis uživatelů</button>
    <button class="tablinks" onclick="openTab(event, 'orders')">Výpis objednávek</button>
</div>

<div id="information" class="tabContent">
    <h2>Uživatelské informace</h2>
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
            <th>Datum registrace</th>
            <td><?php echo htmlspecialchars($_SESSION['uzivatel_dr']);?></td>
        </tr>
    </table>
    <?php echo '<button class="btn_bot" onclick="location.href=\'../user/uprava.php\'" style="width: 200px; margin: 0 auto; margin-top: 10px;">Upravit</button>';?>
</div>

<div id="image_data" class="tabContent" style="height: 400px;">
    <h2>Přidání nových dat do galerie</h2>
    <div style="float: left; width: 50%;">
        <h3 style="color: white; padding: 10px; text-align: center; font-size: 30px;">Nová tématika</h3>
        <form method="post" enctype="multipart/form-data" action="admin_insert.php?type=theme">

            <label for="nazev">Název tématiky<span style="color: red;"> *</span></label><br>
            <input type="text" name="nazev" id="nazev" style="min-width: 100%; max-width: 100%; min-height: 80px;" required><br>
            <?php
            if (!empty($errors['nazev'])){
                echo '<div style="background: red; color: white;">'.$errors['nazev'].'</div>';
            }
            ?>

            <input type="submit" name="submit" value="Odeslat"/>
        </form>
    </div>
    <div style="float: right; width: 50%;">
        <h3 style="color: white; padding: 10px; text-align: center; font-size: 30px;">Nová technika</h3>
        <form method="post" enctype="multipart/form-data" action="admin_insert.php?type=technique">

            <label for="nazev">Název techniky<span style="color: red;"> *</span></label><br>
            <input type="text" name="nazev" id="nazev" style="min-width: 100%; max-width: 100%; min-height: 80px;" required><br>
            <?php
            if (!empty($errors['nazev'])){
                echo '<div style="background: red; color: white;">'.$errors['nazev'].'</div>';
            }
            ?>

            <input type="submit" name="submit" value="Odeslat"/>
        </form>
    </div>

</div>


<div id="gallery" class="tabContent">
    <h2>Rozšíření galerie</h2>
    <form method="post" enctype="multipart/form-data" action="admin_insert.php?type=gallery">

        <label for="nazev">Název obrázku<span style="color: red;"> *</span></label><br>
        <input type="text" name="nazev" id="nazev" style="min-width: 100%; max-width: 100%; min-height: 80px;" required><br>
        <?php
        if (!empty($errors['nazev'])){
            echo '<div style="background: red; color: white;">'.$errors['nazev'].'</div>';
        }
        ?>

        <label for="popis">Popis obrazu<span style="color: red;"> *</span></label><br>
        <textarea name="popis" id="popis" style="min-width: 100%; max-width: 100%; min-height: 80px;" required></textarea><br>
        <?php
        if (!empty($errors['popis'])){
            echo '<div style="background: red; color: white;">'.$errors['popis'].'</div>';
        }
        ?>

        <fieldset>
            <legend>Zvolte tématiku, jenž vystihuje nové dílo</legend>

            <?php
            foreach ($cats as $cat){

                echo '<div style="padding: 5px;">';
                echo '<input type="radio" name="categories" value="'.$cat['name'].'"/>
        <label for="categories[]">'.htmlspecialchars($cat['name']).'</label>';
                echo '</div>';

            }


            ?>
        </fieldset><br>

        <fieldset>
            <legend>Zvolte techniku, kterou bylo dílo vytvořeno</legend>

            <?php
            foreach ($techs as $tech){

                echo '<div style="padding: 5px;">';
                echo '<input type="radio" name="techniques" value="'.$tech['name'].'"/>
        <label for="categories[]">'.htmlspecialchars($tech['name']).'</label>';
                echo '</div>';

            }


            ?>
        </fieldset><br>

        <label for="pic">Obrazový materiál</label><br>
        <input type="file" name="pic" id="pic" style="width: 50%;" required/><br>

        <input type="submit" name="submit" value="Odeslat"/>
    </form>

</div>




<div id="users" class="tabContent">
    <h2>Výpis uživatelů</h2>
    <table id="customers" style="margin: 0 auto;">
        <tr>
            <th>Jméno</th>
            <th>Příjmení</th>
            <th>Emailová adresa</th>
            <th>Počet objednávek</th>
            <th>Datum registrace</th>
            <th></th>
        </tr>

        <?php
            foreach ($users as $user){
                $orderSpecificQuery = $db->prepare('SELECT * FROM bp_orders WHERE autor=:autor;');
                $orderSpecificQuery->execute([
                        ':autor'=>$user['email']
                ]);

                $ordersSpecific = $orderSpecificQuery->fetchAll(PDO::FETCH_ASSOC);



                echo '<tr>';
                echo '<td>'.htmlspecialchars($user['jmeno']).'</td>';
                echo '<td>'.htmlspecialchars($user['prijmeni']).'</td>';
                echo '<td>'.htmlspecialchars($user['email']).'</td>';
                echo '<td>'.count($ordersSpecific).'</td>';
                echo '<td>'.htmlspecialchars(date('d. m. Y',strtotime($user['datum_registrace']))).'</td>';
                if($user['role'] != 'administrator'){
                    echo '<td><a href="../user/delete.php?id='.$user['id'].'" style="color: #e65321;">Smazat</a></td>';
                }else{
                    echo '<td></td>';
                }
                echo'</tr>';
            }

        ?>
    </table>
</div>


<?php
echo '<div id="orders" class="tabContent">';
    echo'<h2>Výpis objednávek</h2>';

echo '<select style="width: 25%;
            padding: 12px 20px;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; text-align: center; margin: 0 auto; margin-bottom: 10px;">
<option class="filter-option" data-filter="*" tabindex="-1" style="background: none; color:black;" onclick="filterOrders(event)">Vše</option>';
echo'<option class="filter-option" data-filter="Zpracováváno" tabindex="-1" style="background: none; color:black;" onclick="filterOrders(event)">Zpracováváno</option>
<option class="filter-option" data-filter="Jednání" tabindex="-1" style="background: none; color:black;" onclick="filterOrders(event)">Jednání</option>
<option class="filter-option" data-filter="Zamítnuto" tabindex="-1" style="background: none; color:black;" onclick="filterOrders(event)">Zamítnuto</option>';
echo '</select>';

        if(!empty($orders)){
            echo'<table id="customers" style="margin: 0 auto;">';
            echo'<tr class="heading">
            <th></th>
            <th>Příjemce</th>
            <th>Email příjemce</th>
            <th>Poznávací název</th>
            <th>Stav</th>
            <th>Cena</th>
            <th>Zaplaceno</th>
            <th>Poslední změna</th>
            <th></th>
            <th></th>
            <th></th>
    </tr>';
            foreach($orders as $order){
                $comSpecificQuery = $db->prepare('SELECT * FROM bp_communication WHERE order_id=:id;');
                $comSpecificQuery->execute([
                    ':id'=>$order['id']
                ]);
                $comsSpecific = $comSpecificQuery->fetchAll(PDO::FETCH_ASSOC);
                $mostRecent=0;
                foreach($comsSpecific as $date){
                    $curDate = strtotime($date['datum_vlozeni']);
                    if ($curDate > $mostRecent) {
                        $mostRecent = $curDate;
                    }
                }
                $update = $mostRecent > 0 ? date('d. m. Y H:i:s',$mostRecent) : 'Komunikace objednávky neobsahuje zprávy';
                echo '<tr class="'.$order['stav'].' '.htmlspecialchars($order['jmeno']).'">';
                echo '<td>'.htmlspecialchars($order['id']).'</td>';
                echo '<td>'.htmlspecialchars($order['jmeno']).'</td>';
                echo '<td>'.htmlspecialchars($order['email']).'</td>';
                echo '<td>'.htmlspecialchars($order['keyword']).'</td>';
                echo '<td>'.htmlspecialchars($order['stav']).'</td>';
                if(isset($order['cena'])){
                    echo '<td>'.$order['cena'].',- Kč</td>';
                }else{
                    echo '<td>Nebyla stanovena cena</td>';
                }
                if($order['zaplaceno'] > 0){
                    echo '<td>'.$order['zaplaceno'].',- Kč</td>';
                }else{
                    echo '<td>Zákazník doposud nic nezaplatil</td>';
                }
                echo '<td>'.htmlspecialchars($update).'</td>';
                echo '<td><a href="../order/komunikace.php?id='.$order['id'].'" style="color: #e65321;">Otevřít komunikaci</a></td>';
                echo '<td><a href="admin_insert.php?type=invoice&id=' . $order['id'] . '" style="color: #e65321;">Generovat fakturu</a></td>';
                if($_SESSION['uzivatel_role'] == 'administrator') {
                    echo '<td><a href="../order/zmenit.php?id=' . $order['id'] . '" style="color: #e65321;">Změnit informace</a></td>';
                }
                echo '</tr>';
            }
            echo'</table>';
        }
        else{
            echo '<p style="color:white; font-size: 30px; margin-left: 5em; margin-top: 15px;">Prozatím si nikdo nic neobjednal</p>';
        }
echo '</div>';
?>




<?php include '../inc/footer.php';?>

<script>
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabContent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        if(evt) evt.currentTarget.className += " active";
        else document.querySelector('button.tablinks').className += " active";
    }
    document.body.addEventListener('DOMContentLoaded',openTab(event,'information'));
    function filterOrders(e) {
        const orders = document.querySelectorAll(".tabContent tr");
        const header = document.querySelector(".tabContent .heading");
        let filter = e.target.dataset.filter;
        if (filter === '*') {
            orders.forEach(order => order.classList.remove('hidden'));
        }  else {
            orders.forEach(order => {
                order.classList.contains(filter) ?
                    order.classList.remove('hidden') :
                    order.classList.add('hidden');
            });
        }
        header.classList.remove('hidden');
    }
</script>
</body>
</html>
