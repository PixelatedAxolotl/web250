<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="../style/default.css">
    <title>LK Stewart's Lucky Sandfish | WEB250 | HOME</title>

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
        <!--Content loaded in with PHP-->
        <?php
            include ('components/header.html');
        ?>
    </header>

    <main>
        <h2>HOME</h2>

        <p>
            I'm Lauren-Kate (LK) Stewart and this is my home page for WEB 250.
            You can learn more about me in my <a href="introduction.html">Introduction</a> and you can
            see what I'm currently working on by clicking the links at the bottom of the page.
        </p>
    </main>

    <footer>
        <!--Content loaded in with PHP-->
        <?php
            include ('components/footer.php');
        ?>
    </footer>
</body>

</html>