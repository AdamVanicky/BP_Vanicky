
    <div class="footer" style="overflow: auto; width: 100%; border-bottom: 1px solid black; padding: 10px 0; margin-top: 50px; background-color: black;">

<br>

        <div style="padding: 20px 0;" class="wide-nav">
            <?php
            if(isset($active)){
                echo'<div class="row" style="text-align:center; ">
            <a href="static/galerie.php">Galerie</a>
            <a href="static/o_mne.php">O mně</a>
            <a href="order/objednavka.php">Objednávka</a>
            <a href="static/obchodni_podminky.php">Obchodní podmnínky</a>
            <a href="static/ochrana_osobnich_udaju.php">Zásady ochrany osobních údajů</a>
        </div>';
            }else{
                echo'<div class="row" style="text-align:center; ">
            <a href="../static/galerie.php">Galerie</a>
            <a href="../static/o_mne.php">O mně</a>
            <a href="../order/objednavka.php">Objednávka</a>
            <a href="../static/obchodni_podminky.php">Obchodní podmnínky</a>
            <a href="../static/ochrana_osobnich_udaju.php">Zásady ochrany osobních údajů</a>
        </div>';
            }
            ?>
            <div class="row" style="text-align: center;">
                <a href="https://www.facebook.com/profile.php?id=100010270839139" target="_blank" class="fa fa-facebook"></a>
                <a href="https://x.com/Berankova1Katka?t=3_kBCLqMeLx863JRzsnzGA&s=08" target="_blank" class="fa fa-twitter"></a>
                <a href="https://www.instagram.com/katkabeee?utm_source=qr&igsh=dTllbDNkc3ZqeTA4" target="_blank" class="fa fa-instagram"></a>
            </div>
        </div>

        <div style="padding: 20px 0;" id="myFLinks" class="sidepanel_footer">
            <a href="javascript:void(0)" class="closebtn" id="closer" onclick="closeNav_Footer()">×</a>
    <?php
    if(isset($active)){
        echo'<div class="row" style="text-align:center; " id="content">
            <a href="static/galerie.php">Galerie</a>
            <a href="static/o_mne.php">O mně</a>
            <a href="order/objednavka.php">Objednávka</a>
            <a href="static/obchodni_podminky.php">Obchodní podmnínky</a>
            <a href="static/ochrana_osobnich_udaju.php">Zásady ochrany osobních údajů</a>
        </div>';
        }else{
        echo'<div class="row" style="text-align:center; " id="content">
            <a href="../static/galerie.php">Galerie</a>
            <a href="../static/o_mne.php">O mně</a>
            <a href="../order/objednavka.php">Objednávka</a>
            <a href="../static/obchodni_podminky.php">Obchodní podmnínky</a>
            <a href="../static/ochrana_osobnich_udaju.php">Zásady ochrany osobních údajů</a>
        </div>';
    }
    ?>
            <div class="row" style="text-align: center;" id="content_socials">
                <a href="https://www.facebook.com/profile.php?id=100010270839139" target="_blank" class="fa fa-facebook"></a>
                <a href="https://x.com/Berankova1Katka?t=3_kBCLqMeLx863JRzsnzGA&s=08" target="_blank" class="fa fa-twitter"></a>
                <a href="https://www.instagram.com/katkabeee?utm_source=qr&igsh=dTllbDNkc3ZqeTA4" target="_blank" class="fa fa-instagram"></a>
            </div>
        </div>
        <button class="openbtn_footer" onclick="openNav_Footer()"><i class="fa fa-bars" style="font-size: 50px;"></i></button>
        <div class="row" style="color:gray; text-align: center; padding-bottom: 20px;">
            Copyright © 2024 Kateřina Beránková - Všechna práva vyhrazena
        </div>

    </div>

    <script>
        function openNav_Footer() {
            document.getElementById("content").style.display = "block";
            document.getElementById("content_socials").style.display = "block";
            document.getElementById("myFLinks").style.height = "250px";
            document.getElementById("closer").style.display = "block";
        }

        function closeNav_Footer() {
            document.getElementById("content").style.display = "none";
            document.getElementById("content_socials").style.display = "none";
            document.getElementById("myFLinks").style.height = "0";
            document.getElementById("closer").style.display = "none";
        }
    </script>
