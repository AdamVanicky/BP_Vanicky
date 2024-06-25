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
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <style>
        hr{
            color: #1284C8;
            height: 15px;
            background: #1284C8;
        }

        .hidden{
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
            box-sizing: border-box; text-align: center; margin: 0 auto;" name="Gallery_filter" id="Gallery_filter" onchange="getSelectedValue()">
<option class="filter-option"  tabindex="-1" style="background: none; color:black;" value="all">Vše</option>';
foreach ($cats as $cat){
    echo '<option class="filter-option"  tabindex="-1" style="background: none; color:black;" value="'.htmlspecialchars($cat['name']).'">'.htmlspecialchars($cat['name']).'</option>';
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
    function getSelectedValue(){
        const selectValue = document.getElementById("Gallery_filter").value;
        let selectElement = document.querySelector('[name=Gallery_filter]');
        let acceptedValues = [...selectElement.options].map(o => o.value);
        const galleryCards = document.querySelectorAll('.gallery div');
        if(selectValue === "all"){
            galleryCards.forEach(card => card.classList.remove('hidden'));
        }
        else{
            galleryCards.forEach(card => {
                if(acceptedValues.includes(selectValue)) {
                    if(card.classList.contains(selectValue)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                } else {
                    card.classList.remove('hidden');
                }
            })
        }
    }
</script>
<?php include '../inc/footer.php' ?>
</body>
</html>
