<?php
include 'auth.php';
redirectIfLoggedIn();

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error = "Both fields are required!";
    } elseif (!loginUser($username, $password)) {
        $error = "Invalid username or password!";
    } else {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    
    <main class="container">
        <section class="auth-form">
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="forms-div">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </div>
            </form>
            
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </section>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Todo List</p>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>