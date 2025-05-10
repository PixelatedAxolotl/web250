<!DOCTYPE html>
<html lang="en">
<?php
    include 'dbScripts/dbConnect.php';
    session_start();
?> 


<!-- PHP Function Zone -->
<?php
    function uploadImage($carData, $image, $mysqli)
    {
        $vin = $carData['vin'];
        $make = $carData['make'];
        $model = $carData['model'];

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


        $targetPath = $targetPath . basename( $image['name']); 

        if(move_uploaded_file($image['tmp_name'], $targetPath)) 
        {
            echo "The file ".  basename( $image['name']). " has been uploaded<br>". "\n";
            $fileName =  $image["name"];
            $query = "INSERT INTO images (Vin, ImageFile)
                      VALUES ('$vin', '$fileName')
                      ON DUPLICATE KEY UPDATE
                      ImageFile = '$fileName'";
            try
            {
                $mysqli->query($query);
                if ($mysqli->affected_rows == 0)
                {
                    $_SESSION['statusMessage'][] = ['text'  => "Error entering image into the database for the $make $model vehicle with the VIN $vin",
                    'color' => "Red"];
                }
                else
                {
                    $_SESSION['statusMessage'][] = ['text'  => "Successfully entered image into the database for the $make $model vehicle with the VIN $vin",
                    'color' => "Green"];
                    return 1;
                }
            }
            catch (mysqli_sql_exception $error)
            {
                $_SESSION['statusMessage'][] = ['text'  => "Error entering image into the database for the {$trimmedUpdateFields['make']} {$trimmedUpdateFields['model']} vehicle with the VIN $vin",
                'color' => "Red"];
            } //end query try catch
        }//end file upload if
        return 0; //if you get here something went wrong with the file upload
    }//end function
?>

<?php
    //whatever message should be inserted as HTML about the status of inserting/updating records
    $statusMessage = []; 
    $textColor = [];  
     
    //for form submission because I don't want to write it out every time
    $action = htmlspecialchars($_SERVER['PHP_SELF']);

    // handle crud operations
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['create'])) 
        {   
            // Capture the values posted to this php program from the text fields            
            $trimmedInputFields = array_map('trim', $_REQUEST);

            //remove everything after the 12 input fields (create elem + remote hosting adds others)
            array_splice($trimmedInputFields, 12);
            
            $vin = $trimmedInputFields['vin'];
            $vinExistQuery = "SELECT `Vin` FROM `inventory` WHERE `Vin` = '$vin'";
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
                uploadImage($trimmedInputFields, $_FILES['image'], $mysqli);
        
                //Build a SQL Query using the values from above
                $query = "INSERT INTO inventory SET
                Vin = ?,
                Make = ?,  
                Model = ?,
                Year = ?,   
                Asking_price = ?,
                Sale_price = ?,
                Purchase_price = ?,
                Ext_color = ?,
                Trim = ?,
                Int_color = ?,
                Mileage = ?,
                Transmission = ?";

                $preparedQuery = $mysqli->prepare($query);
                $preparedQuery->bind_param( "sssidddsssis",
                                            $trimmedInputFields['vin'], 
                                            $trimmedInputFields['make'],
                                            $trimmedInputFields['model'],
                                            $trimmedInputFields['year'],
                                            $trimmedInputFields['askingPrice'],
                                            $trimmedInputFields['salePrice'],
                                            $trimmedInputFields['purchasePrice'],
                                            $trimmedInputFields['exteriorColor'],
                                            $trimmedInputFields['trim'],
                                            $trimmedInputFields['interiorColor'],
                                            $trimmedInputFields['mileage'],
                                            $trimmedInputFields['transmission'],
                );


                /* Try to insert the updated data into the database */
                if ($preparedQuery->execute())
                {
                    /* Try to insert the new car into the database */
                    if ($mysqli->affected_rows > 0 ) 
                    {                  
                        // will be printed above add new car form
                        $_SESSION['statusMessage'][] = ['text'  => "Successfully added {$trimmedInputFields['make']} {$trimmedInputFields['model']} with the VIN $vin",
                        'color' => "Green"];
                    }
                    else
                    {
                        $_SESSION['statusMessage'][] = ['text'  => "Error adding {$trimmedInputFields['make']} {$trimmedInputFields['model']} with the VIN $vin",
                        'color' => "Red"];
                    }
                }

                // DEBUG: Print the query to the browser so you can see it
                //echo "</br>$query</br>";


            } // end vinExist else
        } // end POST = create if 
        elseif (isset($_POST['update']))
        {
            //echo "YOU ARE UPDATING...";

            //trim input fields and store in new array
            $trimmedUpdateFields = array_map('trim', $_REQUEST);
            $vin = $trimmedUpdateFields['vin'];
            $updatedImageFlag = 0; //used so that correct status message displays when user changes image and nothing else

            // only try to upload and update image if an image was selected in the edit form
            if (isset($_FILES['displayedImage']['size']))
            {
                $updatedImageFlag = uploadImage($trimmedUpdateFields, $_FILES['displayedImage'], $mysqli);
            }

            // Build SQL query for updating values for record with matching VIN number
            // TO DO: Switch all CRUD queries to prepared queries??
            $query = "UPDATE inventory SET 
                        Make = ?,  
                        Model = ?,
                        Year = ?,   
                        Asking_price = ?,
                        Sale_price = ?,
                        Purchase_price = ?,
                        Ext_color = ?,
                        Trim = ?,
                        Int_color = ?,
                        Mileage = ?,
                        Transmission = ?
                        WHERE Vin = ?";

            $preparedQuery = $mysqli->prepare($query);
            $preparedQuery->bind_param( "ssidddsssiss",
                                        $trimmedUpdateFields['make'],
                                        $trimmedUpdateFields['model'],
                                        $trimmedUpdateFields['year'],
                                        $trimmedUpdateFields['askingPrice'],
                                        $trimmedUpdateFields['salePrice'],
                                        $trimmedUpdateFields['purchasePrice'],
                                        $trimmedUpdateFields['exteriorColor'],
                                        $trimmedUpdateFields['trim'],
                                        $trimmedUpdateFields['interiorColor'],
                                        $trimmedUpdateFields['mileage'],
                                        $trimmedUpdateFields['transmission'],
                                        $trimmedUpdateFields['vin'] // for WHERE clause
            );
            // Print the query to the browser so you can see it
            //echo ($query. "<br>");
        
            /* Try to insert the updated data into the database */
            if ($preparedQuery->execute())
            {
                if ($mysqli->affected_rows > 0 || $updatedImageFlag == 1)
                {
                    $_SESSION['statusMessage'][] = ['text'  => "{$trimmedUpdateFields['make']} {$trimmedUpdateFields['model']} with the VIN $vin has been successfully updated",
                                                    'color' => "Green"];
                }
                else
                {
                    $_SESSION['statusMessage'][] = ['text'  => "0 fields were updated for {$trimmedUpdateFields['make']} {$trimmedUpdateFields['model']} with the VIN $vin",
                                                    'color' => "Light Blue"];
                }
            }
            else
            {
                $_SESSION['statusMessage'][] = ['text'  => "Error Updating: {$trimmedUpdateFields['make']} {$trimmedUpdateFields['model']} with the VIN $vin",
                'color' => "Red"];
            }
        } 
        elseif (isset($_POST['delete'])) 
        {
            $vin = $_REQUEST['vin'];
            $query = "DELETE inventory.*, images.*  FROM inventory 
                      LEFT JOIN images ON inventory.Vin = images.Vin
                      WHERE inventory.Vin='$vin'";

            /* Try to query the database */
            $mysqli->query($query);
            if ($mysqli->affected_rows > 0) 
            {
                $_SESSION['statusMessage'][] = ['text'  => "The vehicle with Vin $vin has been deleted.",
                                                'color' => "Blue"];
            }
            else
            {
                $_SESSION['statusMessage'][] = ['text'  => "Sorry, a vehicle with Vin of $vin cannot be found",
                                                'color' => "Red"];
            }
        }// end CRUD if

        //print_r ($_SESSION['isLoggedIn']);

        //handle user login
        if (isset($_POST['login']))
        {
            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];

            $userExistQuery = "SELECT `firstName`, `lastName` FROM `users` 
                               WHERE `username` = '$username'
                               AND `password` = '$password'";

            try 
            {
                $result = $mysqli->query($userExistQuery);
                $rowsReturned = mysqli_num_rows($result);

                if ($rowsReturned)
                {
                    $_SESSION['isLoggedIn'] = true;
                    $userInformationArray = mysqli_fetch_assoc($result);
                    $_SESSION['loginMessage'] = ['text' => "Welcome " . $userInformationArray['firstName'] . " " . $userInformationArray[ 'lastName'] . "!",
                                                   'color' => "#2e74ff"];
                }
                else
                {
                    $_SESSION['loginMessage'] = ['text' => "Incorrect Username or Password",
                                                   'color' => "Red"];
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
            $statusMessage = "You are now logged out";
            $textColor = "Blue";
        }
        else
        {

        } 

        //clear header so that update won't resubmit on page reload
       header("Location: " . $_SERVER['PHP_SELF']);

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
        <h1>Lucky Sandfish's Used Cars</h1>
        <h2>Our cars are real and do run, we promise!</h2>
    </header>

<main>
    <section >
        <?php
            if (isset($_SESSION['loginMessage']))
            {
                echo <<<GREETING

                                <h3 style="color: {$_SESSION['loginMessage']['color']};">{$_SESSION['loginMessage']['text']}</h3>
                        GREETING;
            }
            if (isset($_SESSION['isLoggedIn']))
            {
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
    </section>
    
    <?php
      if (!isset($_SESSION['isLoggedIn']))
      {
        echo <<<LOGIN_FORM
                <form action="$action" method="POST" name="login">
                    <label for="username">Username:</label>
                    <input id="username" name="username" type="text" placeholder="Enter your Username" required>
                    <label for="password">Password:</label>
                    <input id="password" name="password" type="password" placeholder="Enter your Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            LOGIN_FORM;
      }
    ?>

    <section>
        <?php
            if (isset($_SESSION['isLoggedIn']))
            {

        ?>
            <h4>Add A Car:</h4>
            <form action="<?php echo $action?>" method="POST" name="create" enctype="multipart/form-data">
                <label for="vin">
                    VIN
                    <input id="vin" name="vin" type="text" placeholder="VIN Number"  autocomplete="off" required>
                </label>

                <label for="make">
                    Make
                    <input id="make" name="make" type="text" placeholder="Car Make" autocomplete="off" required>
                </label>
                
                <label for="model">
                    Model
                    <input id="model" name="model" type="text" placeholder="Car Model" autocomplete="off" required>
                </label>
                
                <label for="year">
                    Year
                    <input id="year" name="year" type="text" pattern="^(19|20)\d{2}$" placeholder="Year" autocomplete="off" required
                        oninvalid="this.setCustomValidity('Please enter a valid year between 1900 and 2099')"
                        oninput="this.setCustomValidity('')">
                </label>

                <label for="transmission">
                    Transmission
                    <input id="transmission" name="transmission" type="text" placeholder="Transmission" autocomplete="off" required>
                </label>

                <label for="mileage">
                    Mileage
                    <input id="mileage" name="mileage" type="text" pattern="^\d+$" placeholder="Mileage" autocomplete="off" required
                        oninvalid="this.setCustomValidity('Please enter a positive whole number.')"
                        oninput="this.setCustomValidity('')">
                </label>
                
                <label for="interiorColor">
                    Interior Color
                    <input id="interiorColor" name="interiorColor" type="text" placeholder="Interior Color" autocomplete="off" required>
                </label>
                                
                <label for="exteriorColor">
                    Exterior Color
                    <input id="exteriorColor" name="exteriorColor" type="text" placeholder="Exterior Color" autocomplete="off" required>
                </label>

                <label for="trim">
                    Trim
                    <input id="trim" name="trim" type="text" placeholder="Trim" autocomplete="off" required>
                </label>

                <label for="askingPrice">
                    Asking Price
                    <input id="askingPrice" name="askingPrice" type="text" pattern="^\d*(\.\d{0,2})?$" autocomplete="off" placeholder="$$$" required
                        oninvalid="this.setCustomValidity('Please enter a valid price.\nDo not include a currency sign\nDo not include more than 2 decimals')"
                        oninput="this.setCustomValidity('')">
                </label>

                <label for="purchasePrice">
                    Purchase Price
                    <input id="purchasePrice" name="purchasePrice" type="text" pattern="^\d*(\.\d{0,2})?$" autocomplete="off" placeholder="$$$" required
                        oninvalid="this.setCustomValidity('Please enter a valid price.\nDo not include a currency sign\nDo not include more than 2 decimals')"
                        oninput="this.setCustomValidity('')">
                </label>

                <label for="purchaseDate">
                    Purchase Date
                    <input id="purchaseDate" name="purchaseDate" type="date" autocomplete="off" required>
                </label>

                <fieldset name="imageUpload">
                    <legend>Image (Optional)</legend>
                    <label for="image">Upload</label>
                    <input type="file" name="image" id="image">
                    <button type="button" name="clearFile">Cancel</button>
                </fieldset>
                
                <fieldset name="formControl">
                    <input name="create" type="submit" value="Add Car">
                    <input type="reset" value="Clear">
                </fieldset>
            </form>
            <?php  } //end of login status check if ?>
    </section>   
    
    <section name="statusMessages">
            <?php

                if (isset($_SESSION['statusMessage']))
                {
                    echo "<h3>Status Messages</h3>";
                    foreach ($_SESSION['statusMessage'] as $message)
                    {
                        echo "<span style=\"color: {$message['color']};\">{$message['text']}</span>";
                    }
                }
            ?>
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
            <th>Year</th>
            <th>Asking Price</th>
            <th>Sale Price</th>
            <th>Purchase Price</th>
            <th>Exterior Color</th>
            <th>Trim</th>
            <th>Interior Color</th>
            <th>Mileage</th>
            <th>Transmission</th>
            <th>Purchase Date</th>
            <th>Sale Date</th>
            <?php
            if (isset($_SESSION['isLoggedIn']))
            {
                echo '<th class="actionButtons">Action</th>';
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
    
    //if sale price has been entered add a $ to start of string
    if (preg_match('/^\d+\.?\d+?$/',  $resultArray['Sale_price']))
    {
        $resultArray['Sale_price'] = '$' . $resultArray['Sale_price'];
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
                    <input disabled name="vin" value="$resultArray[Vin]">
                </td>
                <td>
                    <input disabled name="make" type="text" value="$resultArray[Make]">
                </td>
                <td> 
                    <input disabled name="model" type="text" value="$resultArray[Model]">
                </td>
                <td> 
                    <input disabled name="year" type="text" value="$resultArray[Year]">
                </td>
                <td> 
                    <input disabled name="askingPrice" type="text" value="\$$resultArray[Asking_price]">
                </td>
                <td> 
                    <input disabled name="salePrice" type="text" value="$resultArray[Sale_price]" placeholder="Unsold">
                </td>
                <td> 
                    <input disabled name="purchasePrice" type="text" value="\$$resultArray[Purchase_price]">
                </td>
                <td> 
                    <input disabled name="exteriorColor" type="text" value="$resultArray[Ext_color]">
                </td>
                <td> 
                    <input disabled name="trim" type="text" value="$resultArray[Trim]">
                </td>
                <td> 
                    <input disabled name="interiorColor" type="text" value="$resultArray[Int_color]">
                </td>
                <td> 
                    <input disabled name="mileage" type="text" value="$resultArray[Mileage]">
                </td>
                <td> 
                    <input disabled name="transmission" type="text" value="$resultArray[Transmission]">
                </td>
                <td> 
                    <input disabled name="purchaseDate" type="text" value="$resultArray[Purchase_date]">
                </td>
                <td> 
                    <input disabled name="saleDate" type="text" value="$resultArray[Sale_date]">
                </td>

    HTML;
    if (isset($_SESSION['isLoggedIn']))
    {
        $carForm .= <<<ACTION_BUTTONS
                    <td class="actionButtons">
                        <button name="toggleEdit" tooltip="Edit" type="button"><img tooltip="Edit Car Data" src="images/editIcon.svg" alt="Edit Car Information"></button>
                        <button name="cancelEdit" tooltip="Cancel Edit" type="reset"><img src="images/cancelIcon.svg" alt="Cancel Edit"></button>


                        <button formaction="?action=update&vin=$resultArray[Vin]" name="update" tooltip="Submit" type="submit"><img src="images/checkmarkIcon.svg" alt="Update Info"></button>

                        <button formaction="?action=delete&vin=$resultArray[Vin]" name="delete" tooltip="Delete Car" type="submit"><img src="images/deleteIcon.svg" alt="Delete Car"></button>
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

            document.querySelector("button[tooltip]").show();


    </script>
        <!--Required validation script-->
        <script src="https://lint.page/kit/880bd5.js" crossorigin="anonymous"></script>


</body>
</html>