 <?php
//mySQL->localhost
$serverName = 'sql100.infinityfree.com';
$username = 'if0_38272035';
$password = 'remoteAxolotl';
$databaseName = 'if0_38272035_Cars';

$mysqli = new mysqli($serverName, $username, $password, $databaseName);    //('localhost', 'root', '', 'Cars');
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
printf("Connected to Cars Database!\n");
//select a database to work with
$mysqli->select_db("if0_38272035_Cars");
printf("Selected Cars Database!"); 
?>