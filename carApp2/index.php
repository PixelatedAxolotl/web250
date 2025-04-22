<!DOCTYPE html>
<html lang="en">
<?php
    include 'dbScripts/dbConnect.php';
    session_start();
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
            $vin =  trim( $_REQUEST['VIN']) ;
            $make = trim( $_REQUEST['Make']) ;
            $model = trim( $_REQUEST['Model']) ;
            $price =  $_REQUEST['Asking_Price'] ;

            $vinExistQuery = "SELECT `VIN` FROM `inventory` WHERE `VIN` = '$vin'";
            $result = $mysqli->query($vinExistQuery);

            if (! $result) 
            {
                echo "Error getting cars from the database: " . mysql_error()."<br>";
            }

            if (mysqli_fetch_assoc($result) > 0) 
            {
               // will be printed above add new car form
               $statusMessage = "A car with the VIN [$vin] is already in the database";
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
                    $query = "INSERT INTO images (VIN, ImageFile) VALUES ('$vin', '$fileName')";

                    if (! $mysqli->query($query)) 
                    {
                        echo "Error entering $targetPath into database: " . mysql_error()."<br>";
                    
                    }
                }   // end of file move if
        
                //Build a SQL Query using the values from above
                $query = "INSERT INTO inventory
                          (VIN, Make, Model, ASKING_PRICE)
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

            $vin = $_REQUEST['VIN'] ;
            $make = $_REQUEST['Make'] ;
            $model = $_REQUEST['Model'] ;
            $price = $_REQUEST['Asking_Price'] ;

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

                    $query = "INSERT INTO images (VIN, ImageFile)
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
        
            // Build SQL query for updating values for record with matching VIN number
            $query = "UPDATE inventory SET 
                      VIN='$vin', 
                      Make='$make', 
                      Model='$model', 
                      ASKING_PRICE='$price'
                      WHERE
                      VIN='$vin'"; 
        
            // Print the query to the browser so you can see it
            //echo ($query. "<br>");
        
            /* Try to insert the new car into the database */
            if ($result = $mysqli->query($query)) 
            {
                //echo "<p>You have successfully updated the information for $make $model in the database.</P>";
                $statusMessage = "$make $model with the VIN $vin has been successfully updated";
            }
            else
            {
                echo "Error entering $vin into database: " . mysql_error()."<br>";
            }

            //clear header so that update won't resubmit on page reload
            header("Location: " . $_SERVER['PHP_SELF']);
        } 
        elseif (isset($_POST['delete'])) 
        {
            
            print_r ($_REQUEST);
            $vin = $_REQUEST['VIN'];
            $query = "DELETE inventory.*, images.*  FROM inventory 
                      LEFT JOIN images ON inventory.VIN = images.VIN
                      WHERE inventory.VIN='$vin'";

            /* Try to query the database */
            if ($result = $mysqli->query($query)) 
            {
                echo "The vehicle with VIN $vin has been deleted.";
            }
            else
            {
                echo "Sorry, a vehicle with VIN of $vin cannot be found " . mysql_error()."<br>";
            }
        }// end CRUD if

        //print_r ($_SESSION['isLoggedIn']);
        //handle user login
        if (isset($_POST['login']))
        {
            echo "Hello There!";
            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];


            $userExistQuery = "SELECT `firstName`, `lastName` FROM `users` 
            WHERE `username` = '$username'
            AND `password` = '$password'";

            try 
            {
                $result = $mysqli->query($userExistQuery);
                $rowsReturned = mysqli_num_rows($result);
                echo "ROWS: $rowsReturned";

                if ($rowsReturned)
                {
                    $_SESSION['isLoggedIn'] = true;
                    $userInformationArray = mysqli_fetch_assoc($result);
                    [$_SESSION['firstName'], $_SESSION['lastName']] = [$userInformationArray['firstName'], $userInformationArray[ 'lastName']];
                }
            } 
            catch (mysqli_sql_exception $e) 
            {
                echo "Database Error: " . $e->getMessage();
            }
            
        }
        elseif (isset($_POST['logout']))
        {
            session_unset();
            session_destroy();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        else
        {
            echo nl2br ("\nINVALID POST REQUEST TYPE\n");
        } 


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
    <?php
        if (isset($_SESSION['isLoggedIn']))
        {
            echo <<<GREETING
                        <h3>Hi {$_SESSION['firstName']} {$_SESSION['lastName']}!</h3>
                    GREETING;

            echo <<<USER_LOGOUT
                        <form action="$action" method="POST" name="logout">
                            <button type="submit" name="logout">Logout</button>
                        </form>
                        
                        <form action="dbScripts/setupCarsDatabase.php" method="POST" name="resetDatabase" 
                            onsubmit="return confirm('You sure about this buddy?');">
                            <button type="submit" name="resetDatabase">Reset Database</button>
                        </form>
                    USER_LOGOUT;
        }
    ?>
    
    <?php
      if (!isset($_SESSION['isLoggedIn']))
      {
        echo <<<LOGIN_FORM
                <form action="$action" method="POST" name="login">
                    <label for="username">Username:</label>
                    <input id="username" name="username" type="text" placeholder="Enter your Username" required>
                    <label for="password">Password:</label>
                    <input id="password" name="password" type="text" placeholder="Enter your Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            LOGIN_FORM;
      }
    ?>

    <section>
        <h2>Add A Car:</h2>
        <?php
            if ($statusMessage)
            {
                echo "<h3>$statusMessage</h3>";
            }

            if (isset($_SESSION['isLoggedIn']))
            {

        ?>
            
            <form action="<?php echo $action?>" method="POST" name="create" enctype="multipart/form-data">
                <label for="vin">
                    VIN
                    <input id="vin" name="VIN" type="text" required>
                </label>


                <label for="make">
                    Make
                    <input id="make" name="Make" type="text" required>
                </label>
                
                <label for="model">
                    Model
                    <input id="model" name="Model" type="text" required>
                </label>

                <label for="askingPrice">
                    Price
                    <input id="askingPrice" name="Asking_Price" type="text" required>
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
            <?php  } //end of login status check if ?>
    </section>    

<?php
          
// Display cars with images in table
$query = "SELECT inventory.*, images.ImageFile 
          FROM inventory
          LEFT JOIN images ON inventory.VIN = images.VIN
          ORDER BY 
            inventory.Make ASC,
            inventory.Model ASC";

/* Try to query the database */
$result = $mysqli->query($query);
if (! $result) 
{
    echo "Error getting cars from the database: " . mysql_error()."<br>";
}
// START OF TABLE - Form wraps entire table (closed another echo after table gen)
?>

<form action="$action" method="POST" name="edit" enctype="multipart/form-data">
    <table>
    <thead>
        <tr>
            <th>Image</th>
            <th>VIN Number</th>
            <th>Make</th>
            <th>Model</th>
            <th>Asking Price</th>
            <?php
            if (isset($_SESSION['isLoggedIn']))
            {
                echo '<th>Action</th>';
            } 
            ?>
        </tr>
    </thead>
    <tbody>


<?php
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
                        <input disabled type="file" name="displayedImage">
                        <img src="$carImage" alt="image associated with the car in this row">

                    </label>
                </td>
                <td>
                    <input disabled name="VIN" value="$resultArray[VIN]">
                </td>
                <td>
                    <input disabled name="Make" type="text" value="$resultArray[Make]">
                </td>
                <td> 
                    <input disabled name="Model" type="text" value="$resultArray[Model]">
                </td>
                <td> 
                    <input disabled name="Asking_Price" type="text" value="$resultArray[ASKING_PRICE]">
                </td>
    HTML;
    if (isset($_SESSION['isLoggedIn']))
    {
        $carForm .= <<<ACTION_BUTTONS
                    <td>
                        <button name="toggleEdit" type="button"><img src="images/editIcon.svg" alt="Edit Car Information"></button>
                        <button name="cancelEdit" type="reset"><img src="images/cancelIcon.svg" alt="Cancel Edit"></button>


                        <button formaction="?action=update&VIN=$resultArray[VIN]" name="update" type="submit"><img src="images/checkmarkIcon.svg" alt="Update Info"></button>

                        <button formaction="?action=delete&VIN=$resultArray[VIN]" name="delete" type="submit"><img src="images/deleteIcon.svg" alt="Delete Car"></button>
                    </td>

        ACTION_BUTTONS;
    }

    $carForm .= '</tr>';
    $rowNumber++;
    echo $carForm;
}

echo "</tbody></table></form>";    // END OF FORM + TABLE

$mysqli->close(); // Close db object at end of PHP code
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
            });

            // replace label text with selected filename when adding new car
            document.querySelector("#image").addEventListener('change', function(event)
            {
                document.querySelector("label[for=image]").innerHTML = event.target.value.split('\\').pop();
            });



    </script>
        <!--Required validation script-->
        <script src="https://lint.page/kit/880bd5.js" crossorigin="anonymous"></script>


</body>
</html>