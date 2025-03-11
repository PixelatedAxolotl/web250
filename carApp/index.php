<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<?php
        include 'db_scripts/dbConnect.php';

    ?> 
<?php
    $visible = 0;
    $success = false;
    $action = htmlspecialchars($_SERVER['PHP_SELF']);

    // handle crud operations
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['create'])) 
        {
            
            // Capture the values posted to this php program from the text fields
            $VIN =  trim( $_REQUEST['VIN']) ;
            $Make = trim( $_REQUEST['Make']) ;
            $Model = trim( $_REQUEST['Model']) ;
            $Price =  $_REQUEST['Asking_Price'] ;


            //Build a SQL Query using the values from above
            $query = "INSERT INTO inventory
            (VIN, Make, Model, ASKING_PRICE)
            VALUES 
            (
                '$VIN', 
                '$Make', 
                '$Model',
                $Price
            )";

            // DEBUG: Print the query to the browser so you can see it
            //echo $query;

            /* Try to insert the new car into the database */
            if ($result = $mysqli->query($query)) 
            {
                echo "<p>You have successfully entered $Make $Model into the database.</p>";
                $success = true;
            }
            else
            {
                echo "Error entering $VIN into database: " . $mysqli->error."<br>";
            }
        } 
        elseif (isset($_POST['update']))
        {
            
            $VIN = $_REQUEST['VIN'] ;
            $Make = $_REQUEST['Make'] ;
            $Model = $_REQUEST['Model'] ;
            $Price = $_REQUEST['Asking_Price'] ;
        
            //Build a SQL Query using the values from above
        
            $query = "UPDATE inventory SET 
        
                VIN='$VIN', 
                Make='$Make', 
                Model='$Model', 
                ASKING_PRICE='$Price'
        
                WHERE
        
                VIN='$VIN'"; 
        
            // Print the query to the browser so you can see it
            //echo ($query. "<br>");
        
            /* Try to insert the new car into the database */
            if ($result = $mysqli->query($query)) 
            {
                echo "<p>You have successfully entered $Make $Model into the database.</P>";
            }
            else
            {
                echo "Error entering $VIN into database: " . mysql_error()."<br>";
            }
        
        } 
        elseif (isset($_POST['delete'])) 
        {
            //print_r($_POST);
            $vin = $_POST['VIN'];
            $query = "DELETE FROM inventory WHERE VIN='$vin'";
            //echo "$query <BR>";
            /* Try to query the database */
            if ($result = $mysqli->query($query)) 
            {
                echo "The vehicle with VIN $vin has been deleted.";
            }
            else
            {
                echo "Sorry, a vehicle with VIN of $vin cannot be found " . mysql_error()."<br>";
            }
        }
        else
        {
            echo nl2br ("\nINVALID POST REQUEST TYPE\n");
        }



    } // end POST request if
?>




<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Welcome to Lucky Sandfish's Used Cars</title>
    <link rel="stylesheet" href="styles/default.css">

    <!--Required validation script-->
    <script src="https://lint.page/kit/880bd5.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        
        <a href="index.php"><img style="float: left;" height="120" src="images/sandfish.jpg" alt="Sandfish Skink in sand" width="184" /></a>
        <h1>Lucky Sandfish's Used Cars</h1>
    </header>

<main>

    <h2>Welcome to LK's Used car lot!</h2>
    <section>
        <h2>Add A Car</h2>
        <?php
            if ($success)
            {
                echo "<h3>You have Successfully added a new car!</h3>";
            }
        ?>
        
        <form action="<?php echo $action?>" method="POST" name="create">
            VIN: <input name="VIN" type="text">
            Make: <input name="Make" type="text">
            Model: <input name="Model" type="text">
            Price: <input name="Asking_Price" type="text">
	        <input name="create" type="submit" value="Add Car">
            <input type="reset" value="Reset">
	    </form>
    </section>

    <p><a href="">Add Images to Cars</a></p>
    <p><a href="db_scripts/setupCarsDatabase.php">Reset Database - USE WITH CAUTION</a></p>
    

<?php

//DISPLAY CARS
$query = "SELECT * FROM inventory";
/* Try to query the database */
if ($result = $mysqli->query($query)) 
{
   // Don't do anything if successful.
}
else
{
    echo "Error getting cars from the database: " . mysql_error()."<br>";
}

// START OF TABLE - Form wraps entire table (closed in echo)
// Create the table headers

echo <<<TABLE_HEAD
    <form action="$action" method="POST">
        <table id='Grid' style='width: 80%'>
        <thead>
            <tr>
                <th style='width: 0px'></th>
                <th style='width: 50px'>Make</th>
                <th style='width: 50px'>Model</th>
                <th style='width: 50px'>Asking Price</th>
                <th style='width: 50px'>Action</th>
            </tr>
        </thead>
        <tbody>

TABLE_HEAD;

// Loop through all the rows returned by the query, creating a table row for each
$rowNumber = 0;
while ($result_ar = mysqli_fetch_assoc($result))
{
    $carForm = <<<HTML


            <tr id="$rowNumber">
                <td>
                    <!-- here but hidden so that form can send vin as request param -->
                    <input name="VIN" type="hidden" value="$result_ar[VIN]">
                </td>
                <td>
                    <input disabled name="Make" type="text" value="$result_ar[Make]">
                </td>
                <td> 
                    <input disabled name="Model" type="text" value="$result_ar[Model]">
                </td>
                <td> 
                    <input disabled name="Asking_Price" type="text" value="$result_ar[ASKING_PRICE]">
                </td>

                <td>
                    <button name="toggleEdit" type="button"><img src="images/editIcon.svg" alt="Edit Car Information"></button>
                    <input formaction="?action=UPDATE&VIN=$result_ar[VIN]" name="update" type="submit" value="Update">
                    <button formaction="?action=delete&VIN=$result_ar[VIN]" name="delete" type="submit"><img src="images/deleteIcon.svg" alt="Delete Car"></button>
                </td>

            </tr>



    HTML;
    $rowNumber++;
    echo $carForm;
}

echo "</tbody></table></form>";    // END OF FORM + TABLE

$mysqli->close(); // Close db object at end of code
?>
</main>

    <footer>
        <p>Site designed by Definetly A Real Company, &copy;2025</p>

    </footer>


    <!-- JS Zone 
         Placed here so that it is parsed after the car list table and all its buttons
         have been created
    -->
    <script>
            const editButtons = document.querySelectorAll("button[name=\"toggleEdit\"]");
            
            editButtons.forEach((button) => 
            {
                //console.log(button.closest("tr"));
                button.addEventListener('click', function(event)
                {
                    const formRow = button.closest('tr');
                    const editableFields = formRow.querySelectorAll('input[type="text"]');
                    const updateButton = formRow.querySelector('input[name="update"]');
                    console.log(updateButton); 
                    updateButton.setAttribute("style", "display: block;");
                    editableFields.forEach((field) =>
                    {
                        field.removeAttribute('disabled');
                    });
                });
            });

            console.log(editButtons);
    </script>

</body>
</html>