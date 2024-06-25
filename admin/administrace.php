<?php
require_once '../inc/db.php';

require '../inc/user_required.php';

require '../inc/admin_require.php';

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
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: auto;
            text-align: center;
            vertical-align: middle;
            background: #ddd;
        }

        #customers td, #customers th {
            border: 1px solid black;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #1284C8;}

        #customers th {
            border: 1px solid black;
            padding: 8px;
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
         .accordion {
             max-width: 100%;
             text-align: left;
             margin: 0 auto;
             display: none;
         }

        article {
            border: 1px solid #fff;
            border-radius: 12px;
            background: #202434;
            padding: 1em;
            margin: 1em auto;
        }

        input[type="radio"] {
            appearance: none;
            position: fixed;
            top: -100vh; left: -100vh;

            & ~ div {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.5s;
            }

            &:checked ~ div {
                max-height: 100%;
            }
        }

        p{
            font-size: 25px;
            padding:10px;
        }

        @media only screen and (max-width: 1350px) {
            .accordion{
                display: block;
            }
            .admin_items{
                display: none;
            }
            #customers td{
                padding: 3px;
            }
        }
    </style>
</head>
<body>

<?php include '../inc/navbar.php';?>

<h1>Administrace</h1>
<div class="admin_items">
<div class="tab">
    <button class="tablinks active" onclick="openTab(event, 'orders')">Výpis objednávek</button>
    <button class="tablinks" onclick="openTab(event, 'users')">Výpis uživatelů</button>
    <button class="tablinks" onclick="openTab(event, 'information')">Uživatelské informace</button>
    <button class="tablinks" onclick="openTab(event, 'image_data')">Přidání techniky nebo tématiky</button>
    <button class="tablinks" onclick="openTab(event, 'gallery')">Rozšíření galerie</button>
</div>
<div id="users" class="tabContent">
    <h2>Výpis uživatelů</h2>
    <table id="customers" style="margin: 0 auto;">
        <tr>
            <th>Jméno</th>
            <th>Příjmení</th>
            <th>Emailová adresa</th>
            <th>Telefonní číslo</th>
            <th>Počet objednávek</th>
            <th>Datum registrace</th>
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
            echo '<td>'.htmlspecialchars($user['telefon']).'</td>';
            echo '<td>'.count($ordersSpecific).'</td>';
            echo '<td>'.htmlspecialchars(date('d. m. Y',strtotime($user['datum_registrace']))).'</td>';
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
            <th>Příjemce</th>
            <th>Email příjemce</th>
            <th>Poznávací název</th>
            <th>Stav</th>
            <th>Cena</th>
            <th>Zaplaceno</th>
            <th>Poslední změna</th>
            <th>Editovat informace</th>
            <th>Otevřít komunikaci</th>
            <th>Generovat fakturu</th>
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
        echo '<td>'.htmlspecialchars($order['jmeno']).'</td>';
        echo '<td>'.htmlspecialchars($order['email']).'</td>';
        echo '<td>'.htmlspecialchars($order['poznavaci_nazev']).'</td>';
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
        if($_SESSION['uzivatel_role'] == 'administrator') {
            echo '<td><a href="../order/zmenit.php?id=' . $order['id'] . '" style="color: #e65321;"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 24px;"></i>
</a></td>';
        }else{
            echo '<td></td>';
        }
        echo '<td><a href="../order/komunikace.php?id='.$order['id'].'" style="color: #e65321;"><i class="fa fa-sign-in" aria-hidden="true" style="font-size: 24px;"></i>
</a></td>';
        echo '<td><a href="admin_insert.php?type=invoice&id=' . $order['id'] . '" style="color: #e65321;"><i class="fa fa-file-text" aria-hidden="true" style="font-size: 24px;"></i>
</a></td>';

        echo '</tr>';
    }
    echo'</table>';
}
else{
    echo '<p style="color:white; font-size: 30px; margin-left: 5em; margin-top: 15px;">Prozatím si nikdo nic neobjednal</p>';
}
echo '</div>';
?>

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
        <th>Telefonní číslo</th>
        <td><?php echo htmlspecialchars($_SESSION['uzivatel_tel']); ?></td>
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
</div>

<div class="accordion">

    <article>
        <input id="article1" type="radio" name="articles" checked>
        <label for="article1">
            <h2>Výpis uživatelů</h2>
        </label>
        <div>
            <table id="customers" style="margin: 0 auto;">
                <tr>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Emailová adresa</th>
                    <th>Telefonní číslo</th>
                    <th>Počet objednávek</th>
                    <th>Datum registrace</th>
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
                    echo '<td>'.htmlspecialchars($user['telefon']).'</td>';
                    echo '<td>'.count($ordersSpecific).'</td>';
                    echo '<td>'.htmlspecialchars(date('d. m. Y',strtotime($user['datum_registrace']))).'</td>';
                    echo'</tr>';
                }

                ?>
            </table>
        </div>
    </article>

    <article>
        <input id="article2" type="radio" name="articles">
        <label for="article2">
            <h2>Výpis objednávek</h2>
        </label>
        <div style="overflow: auto; column-width: auto;">
            <?php

            if(!empty($orders)){
                echo'<table id="customers" style="margin: 0 auto;>';
                echo'<tr class="heading">
            <th>Příjemce</th>
            <th>Email příjemce</th>
            <th>Poznávací název</th>
            <th>Stav</th>
            <th>Cena</th>
            <th>Zaplaceno</th>
            <th>Poslední změna</th>
            <th>Editovat informace</th>
            <th>Otevřít komunikaci</th>
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
                    echo '<td>'.htmlspecialchars($order['jmeno']).'</td>';
                    echo '<td>'.htmlspecialchars($order['email']).'</td>';
                    echo '<td>'.htmlspecialchars($order['poznavaci_nazev']).'</td>';
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
                    if($_SESSION['uzivatel_role'] == 'administrator') {
                        echo '<td><a href="../order/zmenit.php?id=' . $order['id'] . '" style="color: #e65321;"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 24px;"></i>
</a></td>';
                    }else{
                        echo '<td></td>';
                    }
                    echo '<td><a href="../order/komunikace.php?id='.$order['id'].'" style="color: #e65321;"><i class="fa fa-sign-in" aria-hidden="true" style="font-size: 24px;"></i>
</a></td>';

                    echo '</tr>';
                }
                echo'</table>';
            }
            else{
                echo '<p style="color:white; font-size: 30px; margin-left: 5em; margin-top: 15px;">Prozatím si nikdo nic neobjednal</p>';
            }

            ?>
        </div>
    </article>

    <article>
        <input id="article3" type="radio" name="articles">
        <label for="article3">
            <h2>Uživatelské informace</h2>
        </label>
        <div>
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
                <th>Telefonní číslo</th>
                <td><?php echo htmlspecialchars($_SESSION['uzivatel_tel']); ?></td>
            </tr>
            <tr>
                <th>Datum registrace</th>
                <td><?php echo htmlspecialchars($_SESSION['uzivatel_dr']);?></td>
            </tr>
            </table>
            <?php echo '<button class="btn_bot" onclick="location.href=\'../user/uprava.php\'" style="width: 200px; margin: 0 auto; margin-top: 10px;">Upravit</button>';?>
        </div>
    </article>

    <article>
        <input id="article4" type="radio" name="articles">
        <label for="article4">
            <h2>Přidání nových dat do galerie</h2>
        </label>
        <div>
            <div style="float: left; width: 40%;">
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
            <div style="float: right; width: 40%;">
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
    </article>

    <article>
        <input id="article5" type="radio" name="articles">
        <label for="article5">
            <h2>Rozšíření galerie</h2>
        </label>
        <div>
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
    </article>

</div>





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
    document.body.addEventListener('DOMContentLoaded',openTab(event,'orders'));
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
