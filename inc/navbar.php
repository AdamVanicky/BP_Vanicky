<div class="navbar" style="overflow: auto; width: 100%; border-bottom: 1px solid black; padding: 10px 0; background-color: #202434;">
    <div style="float: left">
        <?php
        if(isset($active)){
            echo'<a href="index.php"><img src="images/Logo.jpg" alt="" class="logo"/><span style="margin-left: 15px; font-size: 35px;">Beránková Kateřina</span></a>';
        }else{
            echo '<a href="../index.php"><img src="../images/Logo.jpg" alt="" class="logo"/><span style="margin-left: 15px; font-size: 35px;">Beránková Kateřina</span></a>';
        }
        ?>

    </div>
    <div style="float: right; padding: 20px 0;" class="wide-nav">
        <?php
        if(isset($active)){
            echo'<a href="static/galerie.php" >Galerie</a>
        <a href="static/o_mne.php" >O mně</a>
        <a href="order/objednavka.php" >Objednávka</a>';


            if(isset($_SESSION['uzivatel_id'])){
                echo '<a href="user/uzivatelske_informace.php" >' .htmlspecialchars($_SESSION['uzivatel_jmeno']).'</a>';
                echo '<a href="user/odhlaseni.php"  >Odhlásit se</a>';
            }else{
                echo '<a href="user/prihlaseni.php"  >Přihlásit se</a>';
            }
        }else{
            echo'<a href="../static/galerie.php" >Galerie</a>
        <a href="../static/o_mne.php" >O mně</a>
        <a href="../order/objednavka.php" >Objednávka</a>';

            if(isset($_SESSION['uzivatel_id'])){
                echo '<a href="../user/uzivatelske_informace.php" >' .htmlspecialchars($_SESSION['uzivatel_jmeno']).'</a>';
                echo '<a href="../user/odhlaseni.php" >Odhlásit se</a>';
            }else{
                echo '<a href="../user/prihlaseni.php"  >Přihlásit se</a>';
            }
        }
        ?>

    </div>
    <div style="float: right; padding: 20px 0;" id="myLinks" class="sidepanel">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <?php
        if(isset($active)){
            echo'<a href="static/galerie.php" >Galerie</a>
        <a href="static/o_mne.php" >O mně</a>
        <a href="order/objednavka.php" >Objednávka</a>';


        if(isset($_SESSION['uzivatel_id'])){
            echo '<a href="user/uzivatelske_informace.php" >' .htmlspecialchars($_SESSION['uzivatel_jmeno']).'</a>';
            echo '<a href="user/odhlaseni.php"  >Odhlásit se</a>';
        }else{
            echo '<a href="user/prihlaseni.php"  >Přihlásit se</a>';
        }
}else{
            echo'<a href="../static/galerie.php" >Galerie</a>
        <a href="../static/o_mne.php" >O mně</a>
        <a href="../order/objednavka.php" >Objednávka</a>';

            if(isset($_SESSION['uzivatel_id'])){
                echo '<a href="../user/uzivatelske_informace.php" >' .htmlspecialchars($_SESSION['uzivatel_jmeno']).'</a>';
                echo '<a href="../user/odhlaseni.php" >Odhlásit se</a>';
            }else{
                echo '<a href="../user/prihlaseni.php"  >Přihlásit se</a>';
            }
        }
        ?>

    </div>
    <button class="openbtn" onclick="openNav()"><i class="fa fa-bars" style="font-size: 50px;"></i></button>

</div>

<script>
    function openNav() {
        document.getElementById("myLinks").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("myLinks").style.width = "0";
    }
</script>


