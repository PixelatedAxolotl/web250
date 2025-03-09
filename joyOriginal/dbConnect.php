 <?php
// THIS RUNS EVERY TIME THE DATABASE IS QUERIED


// get sever + database info for either localhost or remote server
include_once('dbConfig.php');

// create new connection
$mysqli = new mysqli($serverName, $username, $password);

/* check connection */
if ($mysqli->connect_errno) 
{
    printf ("Connect failed: %s')", $mysqli->connect_error);
    exit();
}

echo "<script>console.log('Connected to Server [$serverName]')</script>";

/* if on localhost check if database exists on server and if not try to create it*
   Infinityfree does not allow higher privilage queries like SHOW DATABASES :(
*/
if ($ISLOCAL)
{
    // check if db exists
    $findQuery = "SHOW DATABASES LIKE \"$databaseName\"";

    if ($mysqli->query($findQuery)->num_rows > 0)
    {
        echo "<script>console.log('Found [$databaseName] database!')</script>";
    }
    else
    {
        /*echo nl2br ("Could not find [$databaseName]\n
                     Creating [$databaseName] now...");
        */
        $q_create_database = "CREATE DATABASE IF NOT EXISTS $databaseName";
        if ($mysqli->query($q_create_database) === TRUE)
        {
            echo "<script>console.log('<p>Database [$databaseName] created</P>')</script>";
        }
        else
        {
            echo "<script>console.log('Had trouble with this SQL: [$q_create_database]')</script>";
            echo "<script>console.log('Error: $mysqli->error')</script>";
        } 
    }
} //else database must be created on hosting tools for remote hosted version

//select a database to work with
if (!$mysqli->select_db($databaseName))
{
    echo  ("<script>console.log('Could not connect to [$databaseName]\n
                                 :(\tGoodbye...')</script>");
    exit;
}

echo ("<script>console.log('Successfully Connected to [$databaseName]')</script>");

?>