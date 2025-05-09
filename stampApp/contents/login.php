<?php

$showRegisterForm = isset($_GET['register']) && $_GET['register'] == '1';

if (isset($_SESSION['loginMessage'])) 
{
    $msg = $_SESSION['loginMessage'];
    echo "<h3 style='color: {$msg['color']};'>{$msg['text']}</h3>";
}

// If the user is not logged in

    if ($showRegisterForm) 
    {
        // --- Registration Form ---
        echo <<<REGISTER
        <h2>Create New Account</h2>
        <form action="handlers/userAuthHandler.php?action=register" method="POST" name="register">
            <label>First Name: <input type="text" name="firstName" required></label>
            <label>Last Name: <input type="text" name="lastName" required></label>
            <label>Username: <input type="text" name="username" required></label>
            <label>Password: <input type="password" name="password" required></label>
            <button type="submit">Register</button>
        </form>
        <form action="index.php?p=login&login=1" method="POST" name="toLoginPage">
            <button type="submit">Back to Login</button>
        </form>
        REGISTER;
    } 
    elseif (!isset($_SESSION['isLoggedIn'])) 
    {

        // Login Form 
        echo <<<LOGIN
            <form action="handlers/userAuthHandler.php?action=login" method="POST" name="login">
                <label>Username: <input type="text" name="username" required></label>
                <label>Password: <input type="password" name="password" required></label>
                <button type="submit">Login</button>

                <a href="index.php?p=login&register=1">
                    <button type="button">New User</button>
                </a>
            </form>

        LOGIN;
    }

if (isset($_GET['action']) && $_GET['action'] === 'logout')
{
    echo "<p>starting logout process.</p>";
    header("Location: " . "handlers/userAuthHandler.php?action=logout");
    exit;
}
?>