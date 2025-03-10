<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta content="en-us" http-equiv="Content-Language" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Welcome to Lucky Sandfish's Used Cars</title>
    <style>
        body
        {
            color:aquamarine;
            background-color: blueviolet;
            font-family: Tahoma, Arial, sans-serif;

            max-width: 900px;
            margin: 0 auto;
            width: 90%;
        }

        h1, h2, header, footer
        {
            text-align: center;
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        a, a:visited
        {
            color: greenyellow;
        }

        a:hover
        {
            color: aqua;
        }

        p form
        {
            display: none;
        }

        p form[visibility="1"]
        {
            display: inline-block;
        }

        button
        {
            background: none;
            border: none;
        }

        input:disabled
        {
            background-color: rgba(177, 174, 174, 0.83);
            border: solid 1px crimson;
            color: black;
            cursor: not-allowed;
        }

        button[name="toggleEdit"] img:hover
        {
            cursor: pointer;
            background: rgb(9, 149, 135);
            border-radius: 4px;
            box-shadow: 0 0 3px 3px rgb(0, 213, 153);
            transform: scale(1.1);
            transition: 0.25s;

        }

        button[name="delete"] img:hover
        {
            cursor: pointer;
            background: rgb(93, 12, 4);
            border-radius: 4px;
            box-shadow: 0 0 3px 3px rgb(236, 51, 27);
            transform: scale(1.1);
            transition: 0.25s;

        }

        input[name="update"]
        {
            display: none;
        }
    </style>
</head>

<body>
<header>
    
    <a href="samsusedcars.php"><img style="float: left;" height="120" src="images/usedcars.jpg" width="184" /></a>
    <h1>Lucky Sandfish's Used Cars</h1>
    <caption>The companion web site that goes with the <a href="index.html">Joy 
		of PHP</a> Book</caption>
    <hr />
</header>

<main>
    <?php
        include 'db_scripts/dbConnect.php';

    ?> 
    <h2>Welcome to LK's Used car lot!</h2>
    <section>
        <h2>Add A Car</h2>
        
        <form action="" method="POST">
            VIN: <input name="VIN" type="text">
            Make: <input name="Make" type="text">
            Model: <input name="Model" type="text">
            Price: <input name="Asking_Price" type="text">
	        <input name="create" type="submit" value="Add Car">
            <input type="reset" value="Reset">
	    </form>
    </section>
    <section>
        <h2>Edit A Car</h2>
        <p>
            <?php
                $visible = 0;

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
                        echo $query;
 
                        /* Try to insert the new car into the database */
                        if ($result = $mysqli->query($query)) {
                            echo "<p>You have successfully entered $Make $Model into the database.</p>";
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
                        echo ($query. "<br>");
                    
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
                        echo "Hello There";
                        print_r($_POST);
                        $vin = $_POST['VIN'];
                        $query = "DELETE FROM inventory WHERE VIN='$vin'";
                        echo "$query <BR>";
                        /* Try to query the database */
                        if ($result = $mysqli->query($query)) {
                        Echo "The vehicle with VIN $vin has been deleted.";
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




        </p>
    </section>
    <p><a href="ViewCarsAddImage.php">Add Images to Cars</a></p>
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

// START OF TABLE
// Create the table headers
echo <<<TABLE_HEAD
    <table id='Grid' style='width: 80%'>
    <tr>
    <th style='width: 50px'>Make</th>
    <th style='width: 50px'>Model</th>
    <th style='width: 50px'>Asking Price</th>
    <th style='width: 50px'>Action</th>
    </tr>
TABLE_HEAD;

// Loop through all the rows returned by the query, creating a table row for each
$rowNumber = 0;
while ($result_ar = mysqli_fetch_assoc($result))
{
    $carForm = <<<HTML
        <form action="" method="POST">
            <!-- here but hidden so that form can send vin as request param -->
            <input name="VIN" type="hidden" value="$result_ar[VIN]">

            <tr id="$rowNumber">
                <td>
                    <input disabled='true' name="Make" type="text" value="$result_ar[Make]">
                </td>
                <td> 
                    <input disabled name="Model" type="text" value="$result_ar[Model]">
                </td>
                <td> 
                    <input disabled name="Asking_Price" type="text" value="$result_ar[ASKING_PRICE]">
                </td>
                <td>
                    <button name="toggleEdit" type="button"><img src="images/editIcon.svg" alt="Edit Car Information"></button>
                </td>
                <td>
                    <input formaction="?action=UPDATE&VIN=$result_ar[VIN]" name="update" type="submit" value="Update">
                </td>
                <td>
                    <button formaction="?action=delete&VIN=$result_ar[VIN]" name="delete" type="submit"><img src="images/deleteIcon.svg" alt="Delete Car"></button>
                </td>
            </tr>
        </form>
    HTML;
    $rowNumber++;
    echo $carForm;
}

echo "</table>";    // END OF TABLE

$mysqli->close(); // Close db object at end of code
?>
</main>

    <footer>
        <p>Site designed by Definetly A Real Company, &copy;2025</p>

    </footer>


    <!-- JS Zone -->
    <script>
            const editButtons = document.querySelectorAll("button[name=\"toggleEdit\"]");
            
            editButtons.forEach(button => 
            {
                //console.log(button.closest("tr"));
                button.addEventListener('click', function(event)
                {
                    const formRow = button.closest('tr');
                    const editableFields = formRow.querySelectorAll('input[type="text"]');
                    const updateButton = formRow.querySelector('input[name="update"]');
                    console.log(updateButton); 
                    updateButton.setAttribute("style", "display: block;");
                    editableFields.forEach(field =>
                    {
                        field.removeAttribute('disabled');
                    })
                });
            });

            console.log(editButtons);
    </script>

</body>

</html>