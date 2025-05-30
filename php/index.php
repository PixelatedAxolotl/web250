<?php
    // get filename of the page + name of page to append to tile tag
    if (isset( $_GET["p"])) 
    {
        $pageFileName = $_GET["p"] . ".html";
        $pageTitle = $_GET["p"];
        $pageTitle = preg_replace('/([A-Z])/', ' $1', $pageTitle);
        $pageTitle = ucfirst($pageTitle);
    }
    elseif (isset($_GET['ph']))
    {
        $pageFileName = $_GET["ph"] . ".php";
        $pageTitle = $_GET["ph"];
        $pageTitle = preg_replace('/([A-Z])/', ' $1', $pageTitle);
        $pageTitle = ucfirst($pageTitle);
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

    <?php
        if ($pageFileName == "introductionForm.html")
        {
            echo '<script src="scripts/introductionForm.js"></script>';
            echo '    <link rel="stylesheet" type="text/css" href="../style/introductionForm.css">';
        }
    ?>

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
                <a href="#">Course Assignments</a>
                <ul>
                    <li><a href="http://web250.great-site.net/multipage/superduper_php">Super Duper PHP</a></li>
                    <li><a href="http://web250.great-site.net/multipage/superduper_static">Super Duper Static</a></li>
                    <li><a href="http://web250.great-site.net/joyOriginal">Joy of PHP</a></li>
                    <li><a href="http://web250.great-site.net/joyOriginal/samsusedcars.html">Sam's Used Cars</a></li>
                    <li><a href="http://web250.great-site.net/carApp">Car App A</a></li>
                    <li><a href="http://web250.great-site.net/carApp2">Car App B</a></li>
                    <li><a href="https://pixelatedaxolotl.github.io/web250/fizzbuzz.html">Fizz Buzz</a></li>
                    <li><a href="?p=introductionForm">IntroductionForm</a></li>
                    <li><a href="../stampApp">Final Project</a></li>
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
            <li><a href="https://www.freecodecamp.org/LK_WS"> freeCodeCamp</a></li>
            <li><a href="https://www.codecademy.com/profiles/LK_WS">Codecademy</a></li>
            <li><a href="https://jsfiddle.net/u/LK_WS/fiddles/">JSFiddle</a></li>
            <li><a href="http://www.linkedin.com/in/Lauren-Kate-Stewart">LinkedIn</a></li>
        </ul>
    </nav>


                    <p>Page Built by LK Stewart, &copy;2025</p>
</footer>
</body>

</html>