<?php
    // get filename of the page + name of page to append to tile tag
    if (isset( $_GET["p"])) 
    {
        $pageFileName = $_GET["p"] . ".html";
        $pageTitle = ucfirst($_GET["p"]);
    }
    else
    {
        $pageFileName = "home.html";
        $pageTitle = 'Home';
    }
    $pagePath = "contents/$pageFileName";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="LK">
    <link rel="stylesheet" type="text/css" href="../style/default.css">
    <link rel="icon" href="images/wizard.ico">

    <title>LK Stewart's Lucky Sandfish | WEB250 | <?php echo $pageTitle ?></title>

    <!--     
        Structure of HTML5 semantic layout swiped from PHP Fiddle and modified
        to include validing code and validation buttons and some other tweaks

        +===================================+
        |               header              |
        +===================================+
        |                 nav               |
        +=====================+=============+
        |                     |             |
        |       section       |    aside    |
        |
        +=====================+=============+    
        |          footer/tagline           |
        |         validation buttons        |
        +===================================+
        
        The code from Internet
        
        http://phpfiddle.org        
    -->

    <!--Required validation script-->
    <script src="https://lint.page/kit/880bd5.js" crossorigin="anonymous"></script>
</head>

<body>

<header>
    <h1>LK Stewart's Lucky Sandfish | WEB250</h1>
    <nav>
        <ul>
            <li><a href="?p=home">Home</a></li>
            <li><a href="?p=introduction">Introduction</a></li>
            <li><a href="?p=contract">Contract</a></li>
            <li><a href="https://pixelatedaxolotl.github.io/web250/">Static Version</a></li>
            <li>
                <a href="#">Outside Sites</a>
                <ul>
                    <li><a href="http://web250.great-site.net/multipage/superduper_php">Super Duper PHP</a></li>
                    <li><a href="http://web250.great-site.net/multipage/superduper_static">Super Duper Static</a></li>
                    <li><a href="http://web250.great-site.net/joyOriginal">Joy of PHP</a></li>
                    <li><a href="http://web250.great-site.net/joyOriginal/samsusedcars.html">Sam's Used Cars</a></li>
                    <li><a href="http://web250.great-site.net/carApp">Lucky Sandfish's Used Cars</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<main>
    <!-- Load page content from selected file -->
    <h2><?php echo strtoupper($pageTitle)?></h2> 
    <?php include($pagePath)?>
</main>

<footer>
    <nav>
        <ul>
            <li><a href="https://github.com/PixelatedAxolotl">GitHub</a></li>
            <li><a href="https://pixelatedaxolotl.github.io/">GitHub.io</a></li>
            <li><a href="https://pixelatedaxolotl.github.io/web250/">WEB250.io</a></li>
            <li><a href="https://www.freecodecamp.org/LK_WS">FreeCodeCamp</a></li>
            <li><a href="https://www.codecademy.com/profiles/LK_WS">Codecademy</a></li>
            <li><a href="https://jsfiddle.net/u/LK_WS/fiddles/">JSFiddle</a></li>
            <li><a href="http://www.linkedin.com/in/Lauren-Kate-Stewart">LinkedIn</a></li>
        </ul>
    </nav>


                    <p>Page Built by LK Stewart, &copy;2025</p>



                    
                    <?php
                        $validation_link = "https://validator.w3.org/check?uri=" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    ?>
                    <a href="<?php echo htmlspecialchars($validation_link); ?>" >
                        Validate HTML
                    </a>

                    <a href="https://jigsaw.w3.org/css-validator/check/referer">
                        Validate CSS
                    </a>

</footer>
</body>

</html>