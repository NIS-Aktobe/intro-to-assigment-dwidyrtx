<?php
include 'auth.php';

if (isset($_GET['logout'])) {
    logoutUser();
    header("Location: login.php");
    exit;
}

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (task, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $task, $_SESSION['user_id']);
        $stmt->execute();
    }
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
}


$stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$todos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>My Todo List</h1>
        <div class="user-panel">
            Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
            <a href="?logout=1">Logout</a>
        </div>
    </header>
    
    <main class="container">
        <section class="todo-form">
            <form method="POST">
                <input type="text" name="task" placeholder="Enter new task" required>
                <button type="submit">Add Task</button>
            </form>
        </section>

        <section class="todo-list">
            <h2>Your Tasks</h2>
            <ul>
                <?php foreach ($todos as $todo): ?>
                <li>
                    <?= htmlspecialchars($todo['task']) ?>
                    <a href="?delete=<?= $todo['id'] ?>" class="delete-btn">×</a>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Todo List</p>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>