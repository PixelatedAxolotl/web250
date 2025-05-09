<?php
    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();

        /*session is started  - must set a session variable here otherwise $_SESSION won't be accessible */
        if (! isset($_SESSION['loginMessage']))
        {
            $_SESSION['loginMessage'] = ['text'  => "Please login to view your stamp collection",
            'color' => "#b5c7ff;"];
        }

    }
    else
    {
        echo "<br>Session already started.<br>";
    }


    // get filename of the page + name of page to append to tile tag
    if (isset( $_GET["p"])) 
    {
        $pageFileName = $_GET["p"] . ".php";
        $pageTitle = $_GET["p"];
        $pageTitle = preg_replace('/([A-Z])/', ' $1', $pageTitle);
        $pageTitle = ucfirst($pageTitle);
    }
    else
    {
        $pageFileName = "home.php";
        $pageTitle = 'Home';
    }

    if (isset($_SESSION['isLoggedIn']) && $pageFileName == "home.php")
    {
        $pageFileName = "collection.php";
        $pageTitle = "Stamp Collection";
    }
    $pagePath = "contents/$pageFileName";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="LK">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
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
        if ($pageFileName == "collection.php")
        {
            echo '<script src="scripts/actionButtons.js"></script>';
        }
    ?>

    <!--Required validation script-->
    <script src="https://lint.page/kit/880bd5.js" crossorigin="anonymous" defer></script>
</head>

<body>

<header>
    <h1>Lucky Sandfish's Superb Stamp Storage</h1>
    <h2>Postage Stamps, we got 'em!</h2>
    <nav>
        <ul>
            <li><a href="?p=home">Home</a></li>
                <?php
                if (isset($_SESSION['isLoggedIn']))
                {
                    echo '<li><a href="?p=collection">My Stamps</a></li>';
                    echo '<li><a href="?p=login&action=logout">Logout</a></li>';
                }
                else
                {
                    echo '<li><a href="?p=login&login=1">Login</a></li>';
                } 
            ?>
           
        </ul>
    </nav>
</header>

<main>
    <!-- Load page content from selected file -->
    <!-- <h2><?php //echo strtoupper($pageTitle)?></h2>  -->
    <?php 
        include($pagePath);
    ?>
</main>

<footer>
        <p>Page Built by LK Stewart, &copy;2025</p>
</footer>
</body>

</html>