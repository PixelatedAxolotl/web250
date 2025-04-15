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

    <!--Content loaded in with PHP-->
    <?php include_once ('components/header.html'); ?>

    <main>
        <h2>HOME</h2>

        <p>
            I'm Lauren-Kate (LK) Stewart and this is my home page for WEB 250.
            You can learn more about me in my <a href="introduction.html">Introduction</a> and you can
            see what I'm currently working on by clicking the links at the bottom of the page.
        </p>
    </main>

        <!--Content loaded in with PHP-->
        <?php include_once ('components/footer.php'); ?>
</body>

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

    <table>
        <tbody>
            <tr>
                <td>
                    <p>Page Built by LK Stewart, &copy;2025</p>
                </td>

                <td>
                    
                    <?php
                        $validation_link = "https://validator.w3.org/check?uri=" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    ?>
                    <a href="<?php echo htmlspecialchars($validation_link); ?>" >
                        Validate HTML
                    </a>
                </td>

                <td>|</td>

                <td>
                    <a href="https://jigsaw.w3.org/css-validator/check/referer">
                        Validate CSS
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <!--Populate referer for validation links 
        Loaded here to prevent errors when inserting footer with js-->
    <script>
        document.getElementById("validation_link_html").setAttribute("href", "https://validator.w3.org/check?uri=" + location.href);

        document.getElementById("validation_link_css").setAttribute("href", "https://jigsaw.w3.org/css-validator/validator?uri=" + location.href);
    </script>
</footer>

</html>