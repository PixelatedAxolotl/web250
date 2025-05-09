<?php 

//create database 
include ("dbConnect.php");

$stamp = "stamp";
$stampImages = "stamp_images";

$dropQuery = "DROP TABLE IF EXISTS `$stamp`";

if ($mysqli->query($dropQuery)) 
{
    echo "Database table [$stamp] dropped</P>";
}
else
{
    echo "<p>Error: </p>" . $mysqli->error;
}

$query = "CREATE TABLE IF NOT EXISTS `$stamp` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'scott numbers will repeat over different countries so only country + number is really unique',
            `user_id` int(11) NOT NULL,
            `country` varchar(65) NOT NULL,
            `scott_number` varchar(10) DEFAULT NULL,
            `emission` varchar(40) NOT NULL,
            `series` varchar(60) DEFAULT NULL,
            `issue_year` year(4) NOT NULL,
            `format` varchar(40) DEFAULT 'Stamp',
            `perforation` varchar(15) DEFAULT NULL,
            `collection_status` enum('have','want','','') NOT NULL,
            `quantity` int(11) NOT NULL,
            `notes` varchar(500) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
            )";

if ($mysqli->query($query)) 
{
    echo "Database table [$stamp] created</P>";
}
else
{
    echo "<p>Error: </p>" . $mysqli->error;
}




// Setup stamp_images table
$dropQuery = "DROP TABLE IF EXISTS `$stampImages`";

if ($mysqli->query($dropQuery)) 
{
    echo "Database table [$stampImages] dropped</P>";
}
else
{
    echo "<p>Error: </p>" . $mysqli->error;
}

//Create image table - MOVED FROM OTHER FILE 
$query = " CREATE TABLE IF NOT EXISTS `stamp_images` (
            `stamp_id` int(11) NOT NULL,
            `is_display_image` tinyint(1) NOT NULL DEFAULT 1,
            `file_name` varchar(60) NOT NULL,
            PRIMARY KEY (`stamp_id`),
            KEY `stamp_id` (`stamp_id`)
            ) ";
if ($mysqli->query($query))
{
    echo nl2br ("Database table [$stampImages] created\n");

    // show any generated warnings
    echo nl2br (implode("\n", preg_split("/\d\s+/", $mysqli->info)) . "\n\n");
    
    $result = $mysqli->query("SHOW WARNINGS");
    $rows = $result->fetch_all(MYSQLI_NUM);
    foreach ($rows as $row)
    {
        echo nl2br (implode($row) . "\n");
    }
}
else
{
echo mysql_error();
    echo "<p>Error inserting images: </p>" . printf("Errormessage: %s\n", $mysqli->error);
    echo "<p>***********</p>";
    echo $query;
    echo "<p>***********</p>";
}

echo "<br><br><a href='../index.php'>Home</a>";
$mysqli->close();

?>