<?php 
    include '../dbScripts/dbConnect.php';
    session_start();

    // PHP Function Zone
    function uploadImage($stampData, $image, $mysqli)
    {
        $stampId = $stampData[0];
        $country = $stampData[1]['country'];
        $scottNumber = $stampData[1]['scottNumber'];

        //infinityFree doesn't like getcwd()
        if ($_SERVER['HTTP_HOST'] === 'localhost')
        {
            $currentFolder =  getcwd();
            $targetPath = $currentFolder . "../../uploads/";
        }
        else
        {
            $targetPath = "../uploads/";
        }


        $targetPath = $targetPath . basename( $image['name']); 

        if(move_uploaded_file($image['tmp_name'], $targetPath)) 
        {
            //echo "The file ".  basename( $image['name']). " has been uploaded<br>". "\n";
            $fileName =  $image["name"];

            $query = "INSERT INTO stamp_images (stamp_id, file_name)
                        VALUES ('$stampId', '$fileName')
                        ON DUPLICATE KEY UPDATE
                        file_name = '$fileName'";
            try
            {
                $mysqli->query($query);
                if ($mysqli->affected_rows == 0)
                {
                    $_SESSION['statusMessage'][] = ['text'  => "Error entering image into the database for $country $scottNumber ",
                    'color' => "Red"];
                }
                else
                {
                    $_SESSION['statusMessage'][] = ['text'  => "Successfully entered image into the database for $country $scottNumber",
                    'color' => "Green"];
                    return 1;
                }
            }
            catch (mysqli_sql_exception $error)
            {
                $_SESSION['statusMessage'][] = ['text'  => "Error entering image into the database for $country $scottNumber",
                'color' => "Red"];
            } //end query try catch
        }//end file upload if
        return 0; //if you get here something went wrong with the file upload
    }//end function
    

    
    /* print_r (isset($_POST['update']));
    echo "<BR>";
    print_r ($_GET); */

    // handle crud operations
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['create'])) 
        {               
            // Capture the values posted to this php program from the text fields            
            $trimmedInputFields = array_map('trim', $_REQUEST);
            unset($trimmedInputFields['create']);
            $trimmedInputFields = array_map(function($field) {return (empty($field)) ? null : $field;}, $trimmedInputFields);

            //Build a SQL Query using the values from above
            $query = "INSERT INTO stamp SET
            user_id = ?,
            country = ?,
            scott_number = ?,  
            emission = ?,
            issue_year = ?,   
            perforation = ?,
            collection_status = ?,
            quantity = ?,
            notes = ?,
            series = ?,
            format = ?";

            $preparedQuery = $mysqli->prepare($query);
            $preparedQuery->bind_param( "isssississs",
                                        $_SESSION['userId'],
                                        $trimmedInputFields['country'], 
                                        $trimmedInputFields['scottNumber'],
                                        $trimmedInputFields['emission'],
                                        $trimmedInputFields['year'],
                                        $trimmedInputFields['perforation'],
                                        $trimmedInputFields['stampStatus'],
                                        $trimmedInputFields['quantity'],
                                        $trimmedInputFields['notes'],
                                        $trimmedInputFields['series'],
                                        $trimmedInputFields['format']
            );

            /* Try to insert the updated data into the database */
            if ($preparedQuery->execute())
            {
                /* Try to insert the new car into the database */
                if ($mysqli->affected_rows > 0 ) 
                {                  
                    // will be printed above add new car form
                    $_SESSION['statusMessage'][] = ['text'  => "Successfully added {$trimmedInputFields['country']} {$trimmedInputFields['scottNumber']} to the collection",
                    'color' => "Green"];
                }
                else
                {
                    $_SESSION['statusMessage'][] = ['text'  => "Error adding {$trimmedInputFields['country']} {$trimmedInputFields['scottNumber']} to the collection",
                    'color' => "Red"];
                }
            }

            // must be after stamp data is inserted into table so that the id for the new row can be accessed
            // TO DO: chance of multiple queries being submitted very close together and wrong insert_id being returned??
                        // shouldn't be since each session would be running a different instance of the php file meaning they will
                        // each have their own mysqli object
            uploadImage([$mysqli->insert_id, $trimmedInputFields], $_FILES['image'], $mysqli);
            
        } // End of Create
        elseif (isset($_POST['update']))
        {

            //trim input fields and store in new array
            $trimmedUpdateFields = array_map('trim', $_REQUEST);
            $stampId = $trimmedUpdateFields['stampId'];
            $updatedImageFlag = 0; //used so that correct status message displays when user changes image and nothing else

            // only try to upload and update image if an image was selected in the edit form
            if (isset($_FILES['displayedImage']['size']))
            {
                $updatedImageFlag = uploadImage([$stampId, $trimmedUpdateFields], $_FILES['displayedImage'], $mysqli);
            }

            // Build SQL query for updating values for record with matching stamp id number
            // TO DO: Switch all CRUD queries to prepared queries??
            $query = "UPDATE stamp SET 
                        country = ?,
                        scott_number = ?,  
                        emission = ?,
                        issue_year = ?,   
                        perforation = ?,
                        collection_status = ?,
                        quantity = ?,
                        notes = ?,
                        series = ?,
                        format = ?
                        WHERE stamp.id = ?";

            $preparedQuery = $mysqli->prepare($query);
            $preparedQuery->bind_param( "sssississsi",
                                        $trimmedUpdateFields['country'], 
                                        $trimmedUpdateFields['scottNumber'],
                                        $trimmedUpdateFields['emission'],
                                        $trimmedUpdateFields['issueYear'],
                                        $trimmedUpdateFields['perforation'],
                                        $trimmedUpdateFields['status'],
                                        $trimmedUpdateFields['quantity'],
                                        $trimmedUpdateFields['notes'],
                                        $trimmedUpdateFields['series'],
                                        $trimmedUpdateFields['format'],
                                        $stampId // for WHERE clause
            );

            /* Try to insert the updated data into the database */
            if ($preparedQuery->execute())
            {
                print_r($mysqli->get_warnings());
                if ($mysqli->affected_rows > 0 || $updatedImageFlag == 1)
                {
                    $_SESSION['statusMessage'][] = ['text'  => "{$trimmedUpdateFields['country']} {$trimmedUpdateFields['scottNumber']} has been successfully updated",
                                                    'color' => "Green"];
                }
                else
                {
                    $_SESSION['statusMessage'][] = ['text'  => "0 fields were updated for {$trimmedUpdateFields['country']} {$trimmedUpdateFields['scottNumber']}",
                                                    'color' => "Blue"];
                }
            }
            else
            {
                $_SESSION['statusMessage'][] = ['text'  => "Error Updating: {$trimmedUpdateFields['country']} {$trimmedUpdateFields['scottNumber']}",
                'color' => "Red"];
            }
        
        } //End of Update
        elseif (isset($_POST['delete'])) 
        {
            $stampId = $_REQUEST['stampId'];
            $query = "DELETE stamp.*, stamp_images.*  FROM stamp 
                      LEFT JOIN stamp_images ON stamp.id = stamp_images.stamp_id
                      WHERE stamp.id ='$stampId'";

            /* Try to query the database */
            $mysqli->query($query);
            if ($mysqli->affected_rows > 0) 
            {
                $_SESSION['statusMessage'][] = ['text'  => "Stamp has been deleted.",
                                                'color' => "Blue"];
            }
            else
            {
                $_SESSION['statusMessage'][] = ['text'  => "Sorry, this stamp cannot be found",
                                                'color' => "Red"];
            }
        }// end of Delete
    } // End of Crud if

    //reload collection page
    header("Location: ../index.php?p=collection");
?>