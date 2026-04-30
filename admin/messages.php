<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include '../backend/db.php';

$result = $conn->query('SELECT id, name, email, message, status, DATE_FORMAT(createdAt, "%Y-%m-%d %H:%i") as createdAt FROM messages ORDER BY createdAt DESC');
$messages = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUGNAT Admin Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1>Messages Inbox</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>.</p>

        <?php if (empty($messages)): ?>
            <div class="alert alert-info">No messages yet.</div>
        <?php else: ?>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr class="<?php echo $msg['status'] === 'new' ? 'table-warning' : ''; ?>">
                            <td><?php echo $msg['id']; ?></td>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                            <td><?php echo ucfirst($msg['status']); ?></td>
                            <td><?php echo $msg['createdAt']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>