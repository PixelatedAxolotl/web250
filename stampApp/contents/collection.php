<?php 
    include 'dbScripts/dbConnect.php';

    // Display cars with images in table
    try 
    {

        $query = $mysqli->prepare("
            SELECT stamp.*, stamp_images.file_name 
            FROM stamp
            LEFT JOIN stamp_images ON stamp_images.stamp_id = stamp.id
            WHERE stamp.user_id = ?
            ORDER BY 
                stamp.country ASC,
                stamp.scott_number ASC
        ");

        $query->bind_param("i", $_SESSION['userId']);
        $query->execute();
        $result = $query->get_result();    
    } 
    catch (mysqli_sql_exception $e) 
    {
        echo "Database error: " . $e->getMessage();
    }
    
?>




<section>
        <?php
            if (isset($_SESSION['isLoggedIn']))
            {

        ?>
        <h4>Add A Stamp:</h4>
        <form action="handlers/crudHandler.php" method="POST" name="create" enctype="multipart/form-data">
            <label for="country">
                Country
                <input id="country" name="country" type="text" placeholder="Country" required value="US">
            </label>

            <label for="scottNumber">
                Scott Number 
                <input id="scottNumber" name="scottNumber" type="text" placeholder="SN" 
                        autocomplete="off" required>
            </label>
            
            <label for="year">
                Year of Issue
                <input id="year" name="year" type="text" pattern="^(18|19|20)\d{2}$" placeholder="Year" autocomplete="off" required
                    oninvalid="this.setCustomValidity('Please enter a valid year between 1800 and 2099')"
                    oninput="this.setCustomValidity('')">
            </label>

            <label for="format">
                Format
                <input id="format" name="format" type="text" placeholder="Format" autocomplete="off" required value="stamp">
            </label>

            <label for="emission">
                Emission
                <input id="emission" name="emission" type="text" placeholder="Emission" autocomplete="off" required value="Commemorative">
            </label>

            <label for="series">
                Series
                <input id="series" name="series" type="text" placeholder="Series" autocomplete="off">
            </label>

            <label for="perforation">
                Perforation
                <input id="perforation" name="perforation" type="text" placeholder="Perfs" autocomplete="off" required>
            </label>

            <fieldset name="stampStatus">
                <legend>Collection Status:</legend>

                <label>
                    <input type="radio" name="stampStatus" value="have" required>
                    Have It
                </label><br>

                <label>
                    <input type="radio" name="stampStatus" value="want">
                    Want It
                </label>
            </fieldset>

            <label for="quantity">
                Current Quantity
                <input id="quantity" name="quantity" type="number" placeholder="1" autocomplete="off" required value="1">
            </label>

            <fieldset name="imageUpload">
                <legend>Image (Optional)</legend>
                <label for="image">Upload</label>
                <input type="file" name="image" id="image">
                <button type="button" name="clearFile">Cancel</button>
            </fieldset>

            <label for="notes">
                Include Any Additional Notes Here
                <textarea id="notes" name="notes" rows="5" cols="80"></textarea>
            </label>
            
            <fieldset name="formControl">
                <input name="create" type="submit" value="Add Stamp">
                <input type="reset" value="Clear">
            </fieldset>
        </form>
        <?php  } //end of login status check if ?>
</section>   
 

<section id="statusMessages">
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

<form method="POST" name="edit" enctype="multipart/form-data">
    <table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Country</th>
            <th>Scott Number</th>
            <th>Emission</th>
            <th>Issue Year</th>
            <th>Series</th>
            <th>Format</th>
            <th>Perforation</th>
            <th>Status</th>
            <th>Quantity</th>
            <th>Notes</th>
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
    $stampImage = "uploads/" . $resultArray['file_name'];
    if (!$resultArray['file_name'])
    {
        //set a default image
        $stampImage = "images/sandfish.jpg";
    }

    $editForm = <<<HTML
            <tr id="$rowNumber">
                <td>
                    <label>
                        <input disabled type="file" name="displayedImage">
                        <img src="$stampImage" alt="image associated with the stamp in this row">

                    </label>
                </td>
                <td>
                    <input disabled name="country" type="text" value="$resultArray[country]">
                </td>
                <td>
                    <input disabled name="scottNumber" type="text" value="$resultArray[scott_number]">
                </td>
                <td> 
                    <input disabled name="emission" type="text" value="$resultArray[emission]">
                </td>
                <td> 
                    <input disabled name="issueYear" type="text" value="$resultArray[issue_year]">
                </td>
                <td>
                    <input disabled name="series" type="text" value="$resultArray[series]">
                </td>
                <td>
                    <input disabled name="format" type="text" value="$resultArray[format]">
                </td>
                <td> 
                    <input disabled name="perforation" type="text" value="$resultArray[perforation]">
                </td>
                <td> 
                    <input disabled name="status" type="text" value="$resultArray[collection_status]">
                </td>
                <td> 
                    <input disabled name="quantity" type="text" value="$resultArray[quantity]">
                </td>
                <td> 
                    <input disabled name="notes" type="text" value="$resultArray[notes]">
                </td>
    HTML;
    if (isset($_SESSION['isLoggedIn']))
    {
        $editForm .= <<<ACTION_BUTTONS
                    <td class="actionButtons">
                        <button name="toggleEdit" type="button"><img src="images/editIcon.svg" alt="Edit Stamp Information"></button>
                        <button name="cancelEdit" type="reset"><img src="images/cancelIcon.svg" alt="Cancel Edit"></button>
                        <button formaction="handlers/crudHandler.php?action=update&stampId=$resultArray[id]" name="update" type="submit"><img src="images/checkmarkIcon.svg" alt="Update"></button>
                        <button formaction="handlers/crudHandler.php?action=delete&stampId=$resultArray[id]" name="delete" type="submit"><img src="images/deleteIcon.svg" alt="Delete"></button>
                    </td>

        ACTION_BUTTONS;
    }

    $editForm .= '</tr>';
    $rowNumber++;
    echo $editForm;
}

echo "</tbody></table></form>";    // END OF FORM + TABLE
?>