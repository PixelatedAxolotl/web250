<!DOCTYPE html>
<html lang="en">
<?php
    include 'dbScripts/dbConnect.php';
?> 

<?php
    //whatever message should be inserted as HTML about the status of inserting/updating records
    $statusMessage = null; 
    //for form submission because I don't want to write it out every time
    $action = htmlspecialchars($_SERVER['PHP_SELF']);

    // handle crud operations
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['create'])) 
        {   
            // Capture the values posted to this php program from the text fields
            $vin =  trim( $_REQUEST['vin']) ;
            $make = trim( $_REQUEST['make']) ;
            $model = trim( $_REQUEST['model']) ;
            $price =  $_REQUEST['askingPrice'] ;
            print_r($_REQUEST);
            $vinExistQuery = "SELECT `Vin` FROM `inventory` WHERE `Vin` = '$vin'";
            $result = $mysqli->query($vinExistQuery);

            if (! $result) 
            {
                echo "Error getting cars from the database: " . mysql_error()."<br>";
            }

            if (mysqli_fetch_assoc($result) > 0) 
            {
               // will be printed above add new car form
               $statusMessage = "A car with the Vin [$vin] is already in the database";
            }
            else
            {
                //infinityFree doesn't like getcwd()
                if ($_SERVER['HTTP_HOST'] === 'localhost')
                {
                    $currentFolder =  getcwd();
                    $targetPath = $currentFolder . "../uploads/";
                }
                else
                {
                    $targetPath = "uploads/";
                }


                $targetPath = $targetPath . basename( $_FILES['image']['name']); 

                if(move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) 
                {
                    echo "The file ".  basename( $_FILES['image']['name']). " has been uploaded<br>". "\n";
                    $fileName =  $_FILES["image"]["name"];
                    $query = "INSERT INTO images (Vin, ImageFile) VALUES ('$vin', '$fileName')";

                    if (! $mysqli->query($query)) 
                    {
                        echo "Error entering $targetPath into database: " . mysql_error()."<br>";
                    
                    }
                }   // end of file move if
        
                //Build a SQL Query using the values from above
                $query = "INSERT INTO inventory
                          (Vin, Make, Model, Asking_price)
                          VALUES 
                          (
                            '$vin', 
                            '$make', 
                            '$model',
                            $price
                          )";

                // DEBUG: Print the query to the browser so you can see it
                //echo "<script>console.log('$query');</script>";

                /* Try to insert the new car into the database */
                if ($result = $mysqli->query($query)) 
                {
                    //echo "<p>You have successfully entered $make $model into the database.</p>";
                    
                    // will be printed above add new car form
                    $statusMessage = 'You have Successfully added a new car!';
                    
                    // prevents form from resubmitting on page reload
                    header("Location: " . $_SERVER['PHP_SELF']);
                }
                else
                {
                    echo "Error entering $vin into database: " . $mysqli->error."<br>";
                }
            } // end vinExist else
        } // end POST = create if 
        elseif (isset($_POST['update']))
        {
            //echo "YOU ARE UPDATING...";

            $vin = $_REQUEST['vin'] ;
            $make = $_REQUEST['make'] ;
            $model = $_REQUEST['model'] ;
            $price = $_REQUEST['askingPrice'] ;

            // only try to upload and update image if an image was selected in the edit form
            if ($_FILES['displayedImage']['size'])
            {
                
                //infinityFree doesn't like getcwd()
                if ($_SERVER['HTTP_HOST'] === 'localhost')
                {
                    $currentFolder =  getcwd();
                    $targetPath = $currentFolder . "../uploads/";
                }
                else
                {
                    $targetPath = "uploads/";
                }
                
                $targetPath = $targetPath . basename( $_FILES['displayedImage']['name']); 
                $imagename = "uploads/". basename( $_FILES['displayedImage']['name']); 
            
                if(move_uploaded_file($_FILES['displayedImage']['tmp_name'], $targetPath)) 
                {
                    //echo "The file ".  basename( $_FILES['displayedImage']['name']). " has been uploaded<br>". "\n";
                    
                    $fileName =  $_FILES["displayedImage"]["name"];

                    $query = "INSERT INTO images (Vin, ImageFile)
                              VALUES ('$vin', '$fileName')
                              ON DUPLICATE KEY UPDATE
                              ImageFile = '$fileName'";
                    
                    if ($result = $mysqli->query($query)) 
                    {
                        //echo "<p>Query Result: $result</P>\n";
                        //echo "<p>You have successfully entered $targetPath into the database.</P>\n";
                    }
                    else
                    {
                        echo "Error entering $vin into database: " . mysql_error()."<br>";
                    }
                }
            }
        
            // Build SQL query for updating values for record with matching Vin number
            $query = "UPDATE inventory SET 
                      Vin='$vin', 
                      Make='$make', 
                      Model='$model', 
                      Asking_price='$price'
                      WHERE
                      Vin='$vin'"; 
        
            // Print the query to the browser so you can see it
            //echo ($query. "<br>");
        
            /* Try to insert the new car into the database */
            if ($result = $mysqli->query($query)) 
            {
                //echo "<p>You have successfully updated the information for $make $model in the database.</P>";
                $statusMessage = "$make $model with the Vin $vin has been successfully updated";
            }
            else
            {
                echo "Error entering $vin into database: " . mysql_error()."<br>";
            }

            //clear header so that update won't resubmit on page reload
            //header("Location: " . $_SERVER['PHP_SELF']);
        } 
        elseif (isset($_POST['delete'])) 
        {
            
            print_r ($_REQUEST);
            $vin = $_REQUEST['Vin'];
            $query = "DELETE inventory.*, images.*  FROM inventory 
                      LEFT JOIN images ON inventory.Vin = images.Vin
                      WHERE inventory.Vin='$vin'";

            /* Try to query the database */
            if ($result = $mysqli->query($query)) 
            {
                echo "The vehicle with Vin $vin has been deleted.";
            }
            else
            {
                echo "Sorry, a vehicle with Vin of $vin cannot be found " . mysql_error()."<br>";
            }
        }
        else
        {
            echo nl2br ("\nINVALID POST REQUEST TYPE\n");
        } // end CRUD if
    } // end POST request if
?>


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Welcome to Lucky Sandfish's Used Cars</title>
    <link rel="preload" href="fonts/HeavyHeapRg-Regular.woff2" as="font" type="font/woff2">
    <link rel="stylesheet" href="styles/default.css">

</head>

<body>

    <header>
        <h1>Lucky Sandfish's<br>Used Cars</h1>
    </header>

<main>

    <h2>Welcome to Lucky Sandfish's Used Car Lot!</h2>
    <section>
        <h2>Add A Car:</h2>
        <?php
            if ($statusMessage)
            {
                echo "<h3>$statusMessage</h3>";
            }
        ?>
        
        <form action="<?php echo $action?>" method="POST" name="create" enctype="multipart/form-data">
            <label for="vin">
                Vin
                <input id="vin" name="vin" type="text" required>
            </label>


            <label for="make">
                Make
                <input id="make" name="make" type="text" required>
            </label>
            
            <label for="model">
                Model
                <input id="model" name="model" type="text" required>
            </label>

            <label for="askingPrice">
                Price
                <input id="askingPrice" name="askingPrice" type="text" pattern="^\d*(\.\d{0,2})?$" required
                        oninvalid="this.setCustomValidity('Please enter a valid price.\nDo not include a currency sign\nDo not include more than 2 decimals')"
                        oninput="this.setCustomValidity('')">
            </label>

            <fieldset name="imageUpload">
                <legend>Image (Optional)</legend>
                <label for="image">Upload Image</label>
                <input type="file" name="image" id="image">
                <button type="button" name="clearFile">Clear File</button>
            </fieldset>
	        
            <fieldset name="formControl">
                <input name="create" type="submit" value="Add Car">
                <input type="reset" value="Clear">
            </fieldset>
	    </form>
    </section>    
<?php

// Display cars with images in table
$query = "SELECT inventory.*, images.ImageFile 
          FROM inventory
          LEFT JOIN images ON inventory.Vin = images.Vin
          ORDER BY 
            inventory.Make ASC,
            inventory.Model ASC";

/* Try to query the database */
$result = $mysqli->query($query);
if (! $result) 
{
    echo "Error getting cars from the database: " . mysql_error()."<br>";
}

// START OF TABLE - Form wraps entire table (closed in echo)
echo <<<TABLE_HEAD
    <form action="$action" method="POST" name="edit" enctype="multipart/form-data">
        <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Vin Number</th>
                <th>Make</th>
                <th>Model</th>
                <th>Asking Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

TABLE_HEAD;

// Loop through all the rows returned by the query, creating a table row for each
$rowNumber = 0;
while ($resultArray = mysqli_fetch_assoc($result))
{
    $carImage = "uploads/" . $resultArray['ImageFile'];
    if (!$resultArray['ImageFile'])
    {
        //set a default image
        $carImage = "images/sandfish.jpg";
    }
    
    $carForm = <<<HTML
            <tr id="$rowNumber">
                <td>
                    <label>
                        <input disabled type="file" name="displayedImage" value="displayedImage">
                        <img src="$carImage" alt="image associated with the car in this row">

                    </label>
                </td>
                <td>
                    <input disabled name="vin" value="$resultArray[Vin]">
                </td>
                <td>
                    <input disabled name="make" type="text" value="$resultArray[Make]">
                </td>
                <td> 
                    <input disabled name="model" type="text" value="$resultArray[Model]">
                </td>
                <td> 
                    <input disabled name="askingPrice" type="text" value="$resultArray[Asking_price]">
                </td>

                <td>
                    <button name="toggleEdit" type="button"><img src="images/editIcon.svg" alt="Edit Car Information"></button>
                    <button name="cancelEdit" type="reset"><img src="images/cancelIcon.svg" alt="Cancel Edit"></button>


                    <button formaction="?action=update&vin=$resultArray[Vin]" name="update" type="submit"><img src="images/checkmarkIcon.svg" alt="Update Info"></button>

                    <button formaction="?action=delete&vin=$resultArray[Vin]" name="delete" type="submit"><img src="images/deleteIcon.svg" alt="Delete Car"></button>
                </td>

            </tr>
    HTML;
    $rowNumber++;
    echo $carForm;
}

echo "</tbody></table></form>";    // END OF FORM + TABLE

$mysqli->close(); // Close db object at end of PHP code
?>

</main>

    <footer>
        <p>Site designed by Definetly A Real Company, &copy;2025</p>
        <p><a href="dbScripts/setupCarsDatabase.php">Reset Database - USE WITH CAUTION</a></p>

    </footer>


    <!-- JS Zone 
         Placed here so that it is parsed after the car list table and all its buttons
         have been created
    -->
    <script>
            const editButtons = document.querySelectorAll("button[name=\"toggleEdit\"]");
            
            /* edit, submit/update, delete, and cancel events for buttons in car display table */
            editButtons.forEach((button) => 
            {
                
                const formRow = button.closest('tr');
                const editableFields = formRow.querySelectorAll('input[type="text"], input[type="file"]');
                const updateButton = formRow.querySelector('button[name="update"]');
                const deleteButton = formRow.querySelector('button[name="delete"');
                const cancelButton = formRow.querySelector('button[name="cancelEdit');
                const displayedImage = formRow.querySelector('label img');
                

                button.addEventListener('click', function(event)
                {
                    console.log(updateButton); 

                    updateButton.setAttribute("style", "display: inline-block;");
                    cancelButton.setAttribute("style", "display: inline-block");
                    displayedImage.setAttribute("style", "cursor: pointer");

                    button.setAttribute("style", "display: none");
                    deleteButton.setAttribute("style", "display: none;");
                    editableFields.forEach((field) =>
                    {
                        field.removeAttribute('disabled');
                    }); // end editableFields foreach
                }); // end edit button listener

                formRow.querySelector("button[name=\"cancelEdit\"]").addEventListener('click', function(event)
                {
                    
                    updateButton.setAttribute("style", "display: none;");
                    cancelButton.setAttribute("style", "display: none;");
                    button.setAttribute("style", "display: inline-block");
                    deleteButton.setAttribute("style", "display: inline-block;");

                    editableFields.forEach((field) =>
                    {
                        field.setAttribute('disabled', "");
                    }); // end editableFields foreach

                }); // end cancel button listener
            }); // end edit button foreach

            document.querySelector("button[name='clearFile']").addEventListener('click', function(event)
            {
                document.querySelector("input[name='image']").value = '';
                document.querySelector("label[for=image]").innerHTML = 'Image (Optional)';
            })

            // replace label text with selected filename when adding new car
            document.querySelector("#image").addEventListener('change', function(event)
            {
                document.querySelector("label[for=image]").innerHTML = event.target.value.split('\\').pop();
            })



    </script>
        <!--Required validation script-->
        <script src="https://lint.page/kit/880bd5.js" crossorigin="anonymous"></script>


</body>
</html>