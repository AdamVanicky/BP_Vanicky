<?php
use Mpdf\Mpdf;

require_once '../inc/db.php';

require '../inc/user_required.php';

require '../inc/admin_require.php';

require_once '../vendor/autoload.php';

if($_GET['type'] == 'theme'){

    if(isset($_POST['submit'])){
        if(empty(trim($_POST['nazev']))){
            $errors['nazev'] = 'Není zadán název tématiky';
        }

        if(empty($errors)){
            $nazev = trim($_POST['nazev']);

            $input = $db->prepare('INSERT INTO bp_themes (name) VALUES (:nazev)');
            $input->execute([
                ':nazev'=>$nazev,
            ]);


            header('Location: administrace.php');
        }
    }

}elseif($_GET['type'] == 'technique'){
    if(isset($_POST['submit'])){
        if(empty(trim($_POST['nazev']))){
            $errors['nazev'] = 'Není zadán název techniky';
        }

        if(empty($errors)){
            $nazev = trim($_POST['nazev']);

            $input = $db->prepare('INSERT INTO bp_techniques (name) VALUES (:nazev)');
            $input->execute([
                ':nazev'=>$nazev,
            ]);


            header('Location: administrace.php');
        }
    }
}elseif($_GET['type']=='gallery'){
    if(isset($_POST['submit'])){
        if(empty(trim($_POST['nazev']))){
            $errors['nazev'] = 'Není zadán název obrázku';
        }
        if(empty(trim($_POST['popis']))){
            $errors['popis'] = 'Není zadán popis obrázku';
        }
        if(!isset($_FILES['pic'])){
            $errors['image'] = 'Není vybrán správný obrázek';
        }
        $uploadDirectory = '../images/galerie/';
        if(empty($errors) && move_uploaded_file($_FILES['pic']['tmp_name'],$uploadDirectory.basename($_FILES['pic']['name']))){
            $nazev = trim($_POST['nazev']);
            $popis = trim($_POST['popis']);
            $zdroj = $_FILES['pic']['name'];
            $category = $_POST['categories'];
            $technique = $_POST['techniques'];

            $input = $db->prepare('INSERT INTO bp_images (nazev, popis,category,technique, zdroj) VALUES (:nazev, :popis,:category,:technique, :zdroj)');
            $input->execute([
                ':nazev'=>$nazev,
                ':popis'=>$popis,
                ':category'=>$category,
                ':technique'=>$technique,
                ':zdroj'=>$zdroj
            ]);


            header('Location: administrace.php');
        }
    }
}else{
    $orderQuery = $db->prepare('SELECT * FROM bp_orders WHERE id!=:id;');
    $orderQuery->execute([
        ':id'=>$_GET['id']
    ]);
    $orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
    $order = $orders[0];

    $obj = explode(';',$order['adresa']);
    $date = strtotime("+7 day");

    $mpdf = new Mpdf();
    $mpdf->SetTitle('Faktura objednávky č. '.$_GET['id']);
    $mpdf->SetAuthor('Kateřina Beránková');
    $mpdf->defaultheaderline=0;
    $mpdf->SetHeader('|Faktura objednávky č. '.$_GET['id'].'|');
    $mpdf->defaultfooterline=0;
    $mpdf->SetFooter('Faktura objednávky č. '.$_GET['id'].'|{DATE F j, Y}|{PAGENO}');
    $stylesheet = file_get_contents('../resources/styles.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    $mpdf->WriteHTML('<img src="../images/Logo.jpg" alt="Logo autorky" style="width: 100px;"/><h1 style="color: black; position: absolute; top: 60px; right: -50px; width: 100%;">Kateřina Beránková</h1>');

    $mpdf->WriteHTML('<h3 style="color: black;">Detaily fakturace</h3>
<ul style="list-style: none;">
<li>Datum zadání objednávky: '.date('d. m. Y',strtotime($order['datum_vlozeni'])).'</li>
<li>Datum fakturace: '.date('d. m. Y',time()).'</li>
<li>Datum splatnosti: '.date('d. m. Y',$date).'</li>
</ul>');

    if(empty($order['firma'])){
        $input_customer = '<div style="width:50%; position: absolute; top: 280px; right: 16px;"><h2 style="color: black;">Odběratel</h2>
<ul style="list-style: none;"><br>
<li>Jmeno: '.$order['jmeno'].'</li>
<li>Adresa: '.$obj[0].', '.$obj[1].'</li>
<li>Telefon: +420 '.$order['telefon'].'</li>
</ul></div>';
    }else{
        $input_customer = '<div style="width:50%; position: absolute; top: 280px; right: 16px;"><h2 style="color: black;">Odběratel</h2>
<ul style="list-style: none;"><br>
<li>Jmeno: '.$order['jmeno'].'</li>
<li>Adresa: '.$obj[0].', '.$obj[1].'</li>
<li>Telefon: +420 '.$order['telefon'].'</li>
<hr>
<li>Firma: '.$order['firma'].'</li>
<li>IČO: '.$order['ico'].'</li>
<li>DIČ: '.$order['dic'].'</li>
</ul></div>';
    }
    $mpdf->WriteHTML('<div style="width:50%;"><h2 style="color: black;">Dodavatel</h2>
<ul style="list-style: none;">
<li>Jmeno: Kateřina Beránková</li>
<li>Adresa: Na Hrádku 8</li>
<li>Praha 2 12000</li>
<li>Telefon: +420 604 591 256</li>
</ul></div>');

    $mpdf->WriteHTML($input_customer);

$input_order = '<h2 style="color: black;">Detaily objednávky</h2><table border="1">
<tr>
<th>ID</th>
<th>Název objednávky</th>
<th>Počet obrazů</th>
<th>Cena za kus</th>
<th>DPH</th>
<th>Cena po zdanění</th>
</tr>
<tr>
<td>'.$_GET['id'].'</td>
<td>'.$order['keyword'].'</td>
<td>'.$order['pocet'].'</td>
<td>'.$order['cena'].'</td>
<td>'.$order['cena']*0.15.' [15%]</td>
<td>'.$order['cena']+$order['cena']*0.15.'</td>
</tr>
</table>';
    $mpdf->WriteHTML($input_order);

    $mpdf->Output();
}
