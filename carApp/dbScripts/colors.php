<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "colors_db";
 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        $color_name = $_POST['color_name'];
        $sql = "INSERT INTO colors (color_name) VALUES ('$color_name')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $color_name = $_POST['color_name'];
        $sql = "UPDATE colors SET color_name='$color_name' WHERE id=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM colors WHERE id=$id";
        $conn->query($sql);
    }
}
 
// Fetch all colors
$sql = "SELECT * FROM colors";
$result = $conn->query($sql);
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Colors CRUD</title>
</head>
<body>
    <h1>Colors CRUD</h1>
 
    <h2>Colors List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Color Name</th>
            <th>Actions</th>
        </tr>
        <tr>
            <td>
 
                <form method="post" action="">
                <input type="hidden" name="id" id="id">
                New Color: 
            </td>
            <td>
                <input type="text" name="color_name" id="color_name" required>
            </td>
            <td>
                <button type="submit" name="create">Create</button>
                <button type="submit" name="update">Update</button>
                <button type="submit" name="delete">Delete</button>
            </td>
        </tr>
            
    </form>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['color_name']; ?></td>
            <td>
                <button onclick="editColor(<?php echo $row['id']; ?>, '<?php echo $row['color_name']; ?>')">Edit</button>
                <button onclick="deleteColor(<?php echo $row['id']; ?>)">Delete</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
 
    <script>
        function editColor(id, color_name) {
            document.getElementById('id').value = id;
            document.getElementById('color_name').value = color_name;
        }
 
        function deleteColor(id) {
            if (confirm('Are you sure you want to delete this color?')) {
                document.getElementById('id').value = id;
                document.getElementsByName('delete')[0].click();
            }
        }
    </script>
</body>
</html>
 
<?php
$conn->close();
?>
 