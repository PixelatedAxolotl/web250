 <?php
// THIS RUNS EVERY TIME THE DATABASE IS QUERIED

// get sever + database info for either localhost or remote server
include('dbConfig.php');

// create new connection
$mysqli = new mysqli($serverName, $username, $password, $databaseName);

/* check connection */
if ($mysqli->connect_errno) 
{
    printf ("Connect failed: %s')", $mysqli->connect_error);
    exit();
}
else
{
    //echo "Connected to DB<br>";
}

?>