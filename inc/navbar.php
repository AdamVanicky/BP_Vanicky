<div class="navbar" style="overflow: auto; width: 100%; border-bottom: 1px solid black; padding: 10px 0; background-color: #202434;">
    <div style="float: left">
        <?php
        if(isset($active)){
            echo'<a href="index.php"><img src="images/Logo.jpg" alt="" class="logo"/><span style="margin-left: 15px; font-size: 35px;">Kateřina Beránková</span></a>';
        }else{
            echo '<a href="../index.php"><img src="../images/Logo.jpg" alt="" class="logo"/><span style="margin-left: 15px; font-size: 35px;">Kateřina Beránková</span></a>';
        }
        ?>

    </div>
    <div style="float: right; padding: 20px 0;" id="myLinks" class="topnav">
        <?php
        if(isset($active)){
            echo'<a href="static/galerie.php" class="links">Galerie</a>
        <a href="static/o_mne.php" class="links">O mně</a>
        <a href="order/objednavka.php" class="links">Objednávka</a>';


        if(isset($_SESSION['uzivatel_id'])){
            echo '<a href="user/uzivatelske_informace.php" class="links">' .htmlspecialchars($_SESSION['uzivatel_jmeno']).'</a>';
            echo '<a href="user/odhlaseni.php" class="links" id="tr">Odhlásit se</a>';
        }else{
            echo '<a href="user/prihlaseni.php" class="links" id="tr">Přihlásit se</a>';
        }
}else{
            echo'<a href="../static/galerie.php" class="links">Galerie</a>
        <a href="../static/o_mne.php" class="links">O mně</a>
        <a href="../order/objednavka.php" class="links">Objednávka</a>';

            if(isset($_SESSION['uzivatel_id'])){
                echo '<a href="../user/uzivatelske_informace.php" class="links">' .htmlspecialchars($_SESSION['uzivatel_jmeno']).'</a>';
                echo '<a href="../user/odhlaseni.php" class="links" id="tr">Odhlásit se</a>';
            }else{
                echo '<a href="../user/prihlaseni.php" class="links" id="tr">Přihlásit se</a>';
            }
        }
        ?>

        <a href="https://www.facebook.com/profile.php?id=100010270839139" target="_blank" class="fa fa-facebook"></a>
        <a href="https://x.com/Berankova1Katka?t=3_kBCLqMeLx863JRzsnzGA&s=08" target="_blank" class="fa fa-twitter"></a>
        <a href="https://www.instagram.com/katkabeee?utm_source=qr&igsh=dTllbDNkc3ZqeTA4" target="_blank" class="fa fa-instagram"></a>
    </div>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()" style="font-size: 50px; margin-right: 1em;">
        <i class="fa fa-bars"></i>
    </a>
</div>

<script>

    function myFunction() {
        var x = document.getElementById("myLinks");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }

</script>


