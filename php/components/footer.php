
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