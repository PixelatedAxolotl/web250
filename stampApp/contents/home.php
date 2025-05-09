<?php
    echo <<<GREETING
            <h3 style="color: {$_SESSION['loginMessage']['color']};">{$_SESSION['loginMessage']['text']}</h3>
    GREETING;
    
    if (! isset($_SESSION['isLoggedIn']))
    {
        echo "<p>Welcome to Lucky Sandfish's Superb Stamp Storage! This is a site where you can keep an online inventory of
                 your stamp collection. You can add information about each individual stamp, including Scott Catalogue Number,
                 issue year, emission, and an image associated with the stamp. You can also add stamps that are not yet part of your
                 collection but would like to find.</p>";
    }
?>
