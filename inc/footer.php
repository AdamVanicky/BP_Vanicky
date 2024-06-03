
    <div class="footer" style="overflow: auto; width: 100%; border-bottom: 1px solid black; padding: 10px 0; margin-top: 50px; background-color: black;">

<br>
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

        <br>
        <div class="row" style="color:gray; text-align: center; padding-bottom: 20px;">
            Copyright © 2024 Kateřina Beránková - Všechna práva vyhrazena
        </div>
        <div class="row" style="text-align: center;">
            <a href="https://www.facebook.com/profile.php?id=100010270839139" target="_blank" class="fa fa-facebook"></a>
            <a href="https://x.com/Berankova1Katka?t=3_kBCLqMeLx863JRzsnzGA&s=08" target="_blank" class="fa fa-twitter"></a>
            <a href="https://www.instagram.com/katkabeee?utm_source=qr&igsh=dTllbDNkc3ZqeTA4" target="_blank" class="fa fa-instagram"></a>
        </div>
    </div>
    </body>
    </html>
