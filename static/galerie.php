<?php

session_start();

require_once '../inc/db.php';

$catsQuery = $db->prepare('SELECT * FROM bp_themes');
$catsQuery->execute();
$cats = $catsQuery->fetchAll(PDO::FETCH_ASSOC);


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - Galerie</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        hr{
            color: #1284C8;
            height: 15px;
            background: #1284C8;
        }


        .hidden {
            display: none; !important;
        }

    </style>
</head>
<body>

<?php include '../inc/navbar.php';?>

<h1>Galerie předchozí tvorby</h1>

<?php
echo '<select style="width: 25%;
            padding: 12px 20px;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; text-align: center; margin: 0 auto;">
<option class="filter-option" data-filter="*" tabindex="-1" style="background: none; color:black;" onclick="filterImages(event)">Vše</option>';
foreach ($cats as $cat){
    echo '<option class="filter-option" data-filter="'.htmlspecialchars($cat['name']).'" tabindex="-1" style="background: none; color:black;" onclick="filterImages(event)">'.htmlspecialchars($cat['name']).'</option>';
}
echo '</select>';
?>

<div class="gallery" style="margin-top: 15px;">
    <?php

    $geter=$db->prepare('select * from bp_images');
    $geter->execute();
    $gets=$geter->fetchAll(PDO::FETCH_ASSOC);

        foreach ($gets as $get){
            echo '<div style="background: #202434;" class="'.$get['category'].'">';
            printf("<img src='../images/gallery/%s'>",rawurldecode(basename($get['zdroj'])));
            echo '<hr>';
            echo '<h3 style="color: white; padding: 10px; text-align: center; font-size: 30px;">'.htmlspecialchars($get['nazev']).'</h3>';
            echo '<p style="color: white; padding: 10px;">'.htmlspecialchars($get['popis']).'</p>';
            echo '<p style="color: white; padding: 10px;">Kategorie: '.htmlspecialchars($get['category']).'</p>';
            echo '<p style="color: white; padding: 10px;">Technika: '.htmlspecialchars($get['technique']).'</p>';
            echo '</div>';
        }

    ?>

</div>

<script>
    window.addEventListener("load", ()=>{
        let all = document.querySelectorAll(".gallery img");

        if(all.length>0){
            for(let img of all){
                img.onclick=()=>img.classList.toggle("full");
            }
        }
    })
    function filterImages(e) {
        const images = document.querySelectorAll(".gallery div");
        let filter = e.target.dataset.filter;
        if (filter === '*') {
            images.forEach(image => image.classList.remove('hidden'));
        }  else {
            images.forEach(image => {
                image.classList.contains(filter) ?
                    image.classList.remove('hidden') :
                    image.classList.add('hidden');
            });
        }
    }

</script>
<?php include '../inc/footer.php' ?>
</body>
</html>
