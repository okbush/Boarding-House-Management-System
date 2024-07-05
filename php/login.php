<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munoz Boarding House Login</title>
    <link rel="stylesheet" href="styles/login-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Mallanna&display=swap" rel="stylesheet">

</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <img src="icons/logo.png" alt="Munoz Boarding House Logo" class="logo">
            </div>
            <h2>Welcome back!</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log In</button>
                <a href="#" class="forgot-password">Forgot password?</a>
            </form>
        </div>
    </div>
</body>
</html>
