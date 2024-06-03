<?php

session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková</title>
    <link rel="stylesheet" type="text/css" href="resources/styles.css">
    <link rel="stylesheet" type="text/css" href="resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php $active='home'; include 'inc/navbar.php'; ?>

<div class="container">

    <!-- Full-width images with number text -->
    <div class="mySlides" style="background-color: black;">
        <div class="numbertext">1 / 5</div>
        <img src="images/gallery/ptak_dest.jpg" style="width:50%; height: 450px; display: block; margin: auto;">
    </div>

    <div class="mySlides" style="background-color: black;">
        <div class="numbertext">2 / 5</div>
        <img src="images/gallery/chobotnice.jpg" style="width:50%; height: 450px; display: block; margin: auto;">
    </div>

    <div class="mySlides" style="background-color: black;">
        <div class="numbertext">3 / 5</div>
        <img src="images/gallery/rybnik.jpg" style="width:50%; height: 450px; display: block; margin: auto;">
    </div>

    <div class="mySlides" style="background-color: black;">
        <div class="numbertext">4 / 5</div>
        <img src="images/gallery/papousek_ara_par.jpg" style="width:50%; height: 450px; display: block; margin: auto;">
    </div>

    <div class="mySlides" style="background-color: black;">
        <div class="numbertext">5 / 5</div>
        <img src="images/gallery/rys.jpg" style="width:50%; height: 450px; display: block; margin: auto;">
    </div>

    <!-- Image text -->
    <div class="caption-container">
        <p id="caption"></p>
    </div>

    <!-- Thumbnail images -->
    <div class="row">
        <div class="column">
            <img class="demo cursor" src="images/gallery/ptak_dest.jpg" style="width:100%; height: 175px;" onclick="currentSlide(1)" alt="Pták v dešti">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/gallery/chobotnice.jpg" style="width:100%; height: 175px;" onclick="currentSlide(2)" alt="Chobotnice">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/gallery/rybnik.jpg" style="width:100%; height: 175px;" onclick="currentSlide(3)" alt="Rybník">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/gallery/papousek_ara_par.jpg" style="width:100%; height: 175px;" onclick="currentSlide(4)" alt="Pár papoušků Ara">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/gallery/rys.jpg" style="width:100%; height: 175px;" onclick="currentSlide(5)" alt="Rys">
        </div>
    </div>
</div>

<h1>Kateřina Beránková</h1>
<h2>Obrazy na přání</h2>
<div id="boxes">
    <div id="leftbox">
        <h2>Více o mně</h2>
        <p class="mp_text">Přečtěte si více o mně a o tom, jaké tvorbě se věnuji, mých zkušenostech a také o tom, jaké druhy děl můžete očekávat nebo si objednat.</p>
        <br/>
        <button class="btn_bot" onclick="location.href='static/o_mne.php'">Zobrazit informace</button>
    </div>

    <div id="middlebox">
        <h2>Galerie</h2>
        <p class="mp_text">Prohlédněte si mou předchozí tvorbu. Seznamte se tak s mými preferovanými technikami tvorby a tématikami. Získejte tak námět pro novou objednávku.</p>
        <br/>
        <button class="btn_bot" onclick="location.href='static/galerie.php'">Zobrazit galerii</button>
    </div>

    <div id="rightbox">
        <h2>Objednávka</h2>
        <p class="mp_text">Máte návrh na obraz nebo malbu na míru? Vyhovuje vám styl mé tvorby a tématiky, které realizuji? V tom případě neváhejte a ozvěte se.</p>
        <br/>
        <button class="btn_bot" onclick="location.href='order/objednavka.php'">Zaslat návrh</button>
    </div>

</div>


<?php include 'inc/footer.php' ?>


<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("demo");
        let captionText = document.getElementById("caption");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
        captionText.innerHTML = dots[slideIndex-1].alt;
        setTimeout(showSlides, 5000); // Change image every 2 seconds
    }

    function currentSlide(n) {
        displaySlide(slideIndex = n);
    }

    function displaySlide(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("demo");
        let captionText = document.getElementById("caption");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
        captionText.innerHTML = dots[slideIndex-1].alt;
    }


</script>
</body>
</html>
