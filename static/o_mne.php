<?php session_start();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kateřina Beránková - O mně</title>
    <link rel="stylesheet" type="text/css" href="../resources/styles.css">
    <link rel="stylesheet" type="text/css" href="../resources/styles_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <style>
        .accordion {
            max-width: 700px;
            text-align: left;
            margin: 0 auto;
        }

        article {
            border: 1px solid #fff;
            border-radius: 12px;
            background: #1284C8;
            padding: 1em;
            margin: 1em auto;
            color:white;
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
                max-height: 200px;
            }
        }

        p{
            font-size: 25px;
            padding:10px;
        }

        h2{
            font-size: 42px;
        }
    </style>
</head>

<body>

<?php include '../inc/navbar.php';?>



<div class="o_mne">
    <div class="main">
        <div class="about_image">
        <img alt="Má fotografie" src="../images/umelkyne.jpg">
        </div>
        <div class="about_text">
            <h1>Kateřina Beránková</h1>
            <p>Jmenuji se Kateřina Beránková. Narodila jsem se v Praze, kde jsem vyrostla a vystudovala. Profesně se věnuji fyzioterapii, ale ve volném čase se zabývám tvorbou obrazů pro sebe či na zakázku.</p>
            <br>
            <p>Malbě a kresbě se věnuji od raného věku, chodila jsem do ZUŠ a nyní je to můj největší koníček. Hlavními motivy mé tvorby je příroda, zvířata – zvláště ptáci, krajina či fantasy. Inspirací mi jsou toulky přírodou v Česku i v zahraničí, četba knih či sledování filmů. Ráda se nechávám inspirovat i díly jiných umělců.</p>
            <br>
            <p>Vedle obrazů a kreseb se zabývám i tvorbou ilustrací s fantasy tématem, často na motiv mých oblíbených knih jako je Zaklínač apod. Tvořím ale také z vlastní fantasie nebo dle přání zákazníka – jsem otevřena návrhům i mimo svůj běžný repertoár.</p>
            <br>
            <p>Z technik nejvíce používám kresbu tužkou, kterou často kombinuji s pastelkami. Dále pak mám v oblibě kresbu pastelem, perokresbu, nebo také malbu akrylovými či olejovými barvami na plátno. Ráda ale zkouším i jiné technicky, například malbu kvašem nebo různé kombinace technik.</p>
            <button class="btn_bot" onclick="location.href='galerie.php'" style="width: 250px;">Více z mé tvorby naleznete v galerii</button>
        </div>

    </div>

</div>

<h2>FAQ - Často kladené dotazy</h2>

<div class="accordion">

    <article>
        <input id="article1" type="radio" name="articles" checked>
        <label for="article1">
            <h2>Chtěl bych si nechat vytvořit obraz dle svých představ. Jak to udělám?</h2>
        </label>
        <div>
            <p>Na webu v části „<a href="../order/objednavka.php" style=" font-size: 25px;font-weight: bold; margin: 0;">Objednávka</a>“ je kontaktní formulář, kde si můžete objednat obraz na zakázku včetně různých požadavků na velikost, téma, techniky apod. Objednání je nezávazné. Jakmile obdržím Váš požadavek, začneme se bavit o detailech a ceně obrazu.
            </p>
        </div>
    </article>

    <article>
        <input id="article2" type="radio" name="articles">
        <label for="article2">
            <h2>Jak dlouho trvá tvorba obrazu</h2>
        </label>
        <div>
            <p>Záleží na množství faktorů – velikosti obrazu, motivu, použité technice, rámování… Zpravidla se pohybujeme v rozmezí několika týdnů až měsíců. Odhadovaná doba dodání Vám bude sdělena před závazným objednáním.
            </p>
        </div>
    </article>

    <article>
        <input id="article3" type="radio" name="articles">
        <label for="article3">
            <h2>Kolik stojí obraz na zakázku</h2>
        </label>
        <div>
            <p>Cena je vždy dohodou a záleží na konkrétních požadavcích. Obecně platí čím složitější a detailnější motiv či větší obraz, tím delší doba vyhotovení a vyšší cena.
            </p>
        </div>
    </article>

    <article>
        <input id="article4" type="radio" name="articles">
        <label for="article4">
            <h2>Můžu si nechat obraz i zarámovat</h2>
        </label>
        <div>
            <p>Můžete. Sama obrazy nerámuji, ale můžu rámování zprostředkovat u ověřeného rámařství a dodat obraz už v rámu. Doba vyhotovení a cena se navýší o tuto službu.
            </p>
        </div>
    </article>

    <article>
        <input id="article5" type="radio" name="articles">
        <label for="article5">
            <h2>Rozmyslel jsem si to a chtěl bych mít na obraze něco jiného. Co s tím?</h2>
        </label>
        <div>
            <p>Pokud je tvorba obraz ve fázi návrhu, lze navrhovat změny. Jakmile dojde k samotné tvorbě díla, je možnost úpravy již omezená a nemůžu zaručit, že změna bude možná.
            </p>
        </div>
    </article>

    <article>
        <input id="article6" type="radio" name="articles">
        <label for="article6">
            <h2>Rozmyslel jsem si to a obraz už nechci- Co s tím?</h2>
        </label>
        <div>
            <p>Dokud není zaplacena záloha, lze proces objednávky obrazu kdykoliv ukončit. Pakliže však je záloha již zaplacena, nelze již od smlouvy odstoupit. Smlouva o dílo je vždy z principu uzavírána na míru pro konkrétního zákazníka, z tohoto není odstoupení od smlouvy možné.
            </p>
        </div>
    </article>

</div>




<?php include '../inc/footer.php' ?>
</body>
</html>
