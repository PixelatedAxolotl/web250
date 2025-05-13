<?php 
include ('../dbScripts/dbConnect.php');
session_start();

$action = $_GET['action'] ?? '';
echo "<br>THIS IS THE AUTH HANDLER SPEAKING<br>";
print_r($_GET);
$_SESSION['MadeItToTheHandler'] = 1;

switch ($action) {
    case 'login':
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userExistQuery = "SELECT `id`, `firstName`, `lastName` FROM `users` 
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
                $temp = print_r($userInformationArray);
                $_SESSION['userId'] = $userInformationArray['id'];
                $_SESSION['loginMessage'] = ['text' => "Welcome {$userInformationArray['firstName']} {$userInformationArray[ 'lastName']}!",
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
            
        header("Location: ../index.php");
        break;

    case 'register':
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';

        $checkQuery = "SELECT username FROM users WHERE username = '$username'";
        $result = $mysqli->query($checkQuery);

        if ($result->num_rows > 0) 
        {
            $_SESSION['loginMessage'] = ['text' => 'Username already taken.', 'color' => 'Red'];
            header("Location: ../index.php?p=login&register=1");
            break;
        } 
        else 
        {
            $insertQuery = "INSERT INTO users (username, password, firstName, lastName)
                            VALUES ('$username', '$password', '$firstName', '$lastName')";
            if ($mysqli->query($insertQuery)) 
            {
                $_SESSION['loginMessage'] = ['text' => 'Account created. Please log in.', 'color' => '#2e74ff'];
            } 
            else 
            {
                $_SESSION['loginMessage'] = ['text' => 'Registration failed. Please try again.', 'color' => 'Red'];
            }
        }
        header("Location: ../index.php?p=login");
        break;

    case 'logout':
        echo "logging out";
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        break;

    default:
        http_response_code(400);
        echo "Invalid action.";
}

?>