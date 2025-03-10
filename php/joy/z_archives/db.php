 <?php
//mySQL->localhost
$mysqli = new mysqli('localhost', 'root', '', 'Cars' );
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//select a database to work with
$mysqli->select_db("Cars");
 
?>


ADD SAMS USED CARS LINK TO WEB250
ADD NAV LINKS
formEDIT model does not work with model that includes spaces

if0_38272035_Cars	if0_38272035	(Your vPanel Password)	sql100.infinityfree.com