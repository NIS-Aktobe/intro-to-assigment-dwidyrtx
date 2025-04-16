<?php
include 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
    header("Location: login.php");
    exit;
}

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Add task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (task, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $task, $_SESSION['user_id']);
        $stmt->execute();
    }
}

// Delete task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
}

// Complete task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete'])) {
    $id = $_POST['complete'];
    $stmt = $conn->prepare("UPDATE todos SET status = 'completed' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
}

// Get tasks
$stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY status ASC, created_at DESC");
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
    <title>Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>My Todo List</h1>
        <form method="POST" class="logout-form">
            <button type="submit" name="logout">Logout</button>
        </form>
        <div class="user-info">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</div>
    </header>

    <main class="container">
        <div class="todo-card">
            <form method="POST" class="task-form">
                <input type="text" name="task" placeholder="Enter new task" required>
                <button type="submit">Add Task</button>
            </form>

            <div class="task-list">
                <?php foreach ($todos as $todo): ?>
                <div class="task-item <?= $todo['status'] === 'completed' ? 'completed' : '' ?>">
                    <span><?= htmlspecialchars($todo['task']) ?></span>
                    <div class="task-actions">
                        <form method="POST">
                            <input type="hidden" name="complete" value="<?= $todo['id'] ?>">
                            <button type="submit" class="complete-btn">
                                <?= $todo['status'] === 'completed' ? '✓ Completed' : 'Mark Done' ?>
                            </button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="delete" value="<?= $todo['id'] ?>">
                            <button type="submit" class="delete-btn">×</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Todo App</p>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
