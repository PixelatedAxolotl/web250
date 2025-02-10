<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="../style/default.css">
    <title>LK Stewart's Lucky Sandfish | WEB250 | Introduction</title>

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
        <h2>INTRODUCTION</h2>
        <h3>Lauren-Kate "LK" Stewart</h3>

        <figure>
            <img src="images/sandfish.jpg" alt="image of a Sandfish Skink">
            <figcaption>
                <em>I'd rather not use a picture of myself so this is a Sandfish Skink.</em><br>
                Source:
                    <a href="https://commons.wikimedia.org/wiki/File:Apothekerskink01.jpg">Wilfried Berns</a>,
                    <a href="https://creativecommons.org/licenses/by-sa/2.0/de/deed.en">CC BY-SA 2.0 DE</a>, 
                    via Wikimedia Commons 
            </figcaption>
        </figure>

        <ul>
            <li>
                <p>
                    <strong>Personal Background:</strong>
                    I've lived in NC most of my life and right now
                    I'm a part time student at UNC Charlotte.
                </p>
            </li>
            <li>
                <p>
                    <strong>Professional Background:</strong>
                    Currently I work part time as a programmer/IT help
                    for a small business and as a TA at UNC Charlotte.
                </p>
            </li>
            <li>
                <p>
                    <strong>Academic Background:</strong>
                    Working on my BA in Computer Science at UNC Charlotte and am
                    taking classes here on Transient Study since CPCC offers a lot more online classes.
                </p>
            </li>
            <li>
                <p>
                    <strong>Background in this Subject:</strong>
                    I've taken some web and database classes at UNC Charlotte.
                </p>
            </li>
            <li>
                <p>
                    <strong>Primary Computer Platform:</strong>
                    Laptop running Windows 11.                
                </p>
            </li>
            <li>
                <p>
                    <strong>Courses I'm Taking &amp; Why:</strong>              
                </p>
                <ul>
                    <li>
                        <p><strong>REL110 - World Religions:</strong> This was one of my options to fulfill some of the liberal arts
                        credits I need and it was offered online.</p>
                    </li>
    
                    <li>
                        <p><strong>WEB250 - Database Driven Websites:</strong> This class looked fun. I like web development,
                        I like databases, and I need some elective credits.</p>
                    </li>
    
                    <li>
                        <p><strong>METR 1102 - Introduction to Meteorology:</strong> I'm taking this at UNC Charlotte to fulfill
                        my science with lab requirement. I picked it because it looked interesting,
                        wasn't chemistry or biology, and worked with my schedule.</p>
                    </li>
    
                    <li>
                        <p><strong>METR 1102L - Introduction to Meteorology Lab:</strong> Required lab that goes with METR 1102.</p>
                    </li>
                </ul>
            </li>
            <li>
                <p>
                    <strong>Funny/Interesting Item to Remember me by:</strong>
                    I like collecting postage stamps, particularly ones with reptile or space themes.
                </p>
            </li>
            <li>
                <p>
                    <strong>I'd also like to Share:</strong>
                    The binomial name of the Sandfish Skink is Scincus scincus which I think is funny.
                    They got their common name from the way they “swim” under the surface of sand.
                </p>
            </li>
        </ul>

        
    </main>

    <footer>
        <!--Content loaded in with PHP-->
        <?php
            include ('components/footer.php');
        ?>
    </footer>
</body>

</html>