<?php

//create database 
include ("dbConnect.php");
$users = "users";

//create users table
$query = "CREATE TABLE IF NOT EXISTS $users
          (id INT AUTO_INCREMENT PRIMARY KEY,
           username varchar(30) NOT NULL,
           password varchar(30) NOT NULL,
           firstName varchar(45) NOT NULL,
           lastName varchar(60) NOT NULL)";


try 
{
    $result = $mysqli->query($query);

    echo "[$users] table successfully added to the database!";
} 
catch (mysqli_sql_exception $e) 
{
    echo "Query error: " . $e->getMessage();
}

//insert default users into users table
$query = "INSERT INTO `$users`
          (`username`, `password`, `firstName`, `lastName`)
          VALUES
          ('web250teacher', 'DapperViper666', 'Name', 'Other-Name'),
          ('luckySandfish', 'Not A Fish.', 'LK', 'W-S')";


try 
{
    $result = $mysqli->query($query);

    echo "<p>Success!</p>";

    // show any generated warnings
    echo nl2br ("Inserted rows: " . $mysqli->affected_rows);
    
} 
catch (mysqli_sql_exception $error) 
{
    echo "Query error: " . $error->getMessage();
}










?>