
<html>
<head>
    <title>Car Saved</title>
</head>
<body bgcolor="#FFFFFF" text="#000000" >

<?php 
// Capture the values posted to this php program from the text fields

$VIN =  trim( $_REQUEST['VIN']) ;
$Make = trim( $_REQUEST['Make']) ;
$Model = trim( $_REQUEST['Model']) ;
$Price =  $_REQUEST['Asking_Price'] ;


//Build a SQL Query using the values from above

$query = "INSERT INTO inventory
  (VIN, Make, Model, ASKING_PRICE)
   VALUES (
   '$VIN', 
   '$Make', 
   '$Model',
    $Price
    )";
    
// Print the query to the browser so you can see it
echo ($query. "<br>");
//echo ("EVENTUALLY QUERY WILL READ SOMETHING LIKE: INSERT INTO `inventory` (`VIN`, `YEAR`, `Make`, `Model`, `TRIM`, `EXT_COLOR`, `INT_COLOR`, `ASKING_PRICE`, `SALE_PRICE`, `PURCHASE_PRICE`, `MILEAGE`, `TRANSMISSION`, `PURCHASE_DATE`, `SALE_DATE`) VALUES ('asdf2', NULL, 'assf', 'asf', NULL, NULL, NULL, '333333', NULL, NULL, NULL, NULL, NULL, NULL) <br>");
//$query = "INSERT INTO `inventory` (`VIN`, `YEAR`, `Make`, `Model`, `TRIM`, `EXT_COLOR`, `INT_COLOR`, `ASKING_PRICE`, `SALE_PRICE`, `PURCHASE_PRICE`, `MILEAGE`, `TRANSMISSION`, `PURCHASE_DATE`, `SALE_DATE`, `Primary_Image`) VALUES ('672', '1966', 'car', 'XX', NULL, NULL, NULL, NULL, 66.33, NULL, NULL, NULL, NULL, NULL, NULL);";

include ' dbScripts/dbConnect.php';

/* Try to insert the new car into the database */
if ($result = $mysqli->query($query)) {
    echo "<p>You have successfully entered $Make $Model into the database.</p>";
    //echo mysql_error();
}
else
{
    echo "Error entering $VIN into database: " . $mysqli->error."<br>";
}

echo nl2br ("YOU ARE HERE:\n" . print_r($result));
$mysqli->close();
include 'footer.php'
?>
</body>
</html>