<?php
session_start();
include("backend/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin["password"])) {
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_username"] = $admin["username"];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Admin not found";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUGNAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row w-100 align-items-center">

            <div class="col-md-6 mb-4">
                <h1 class="display-3 fw-bold text-warning">PAUGNAT</h1>
                <p class="lead">Welcome to USTP Paugnat 2027. View events, leaderboards, and updates all in one place.</p>
            </div>

            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h2 class="text-center mb-3">Admin Login</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold">Log In</button>
                    </form>

                    <hr>

                    <a href="home.php" class="btn btn-secondary w-100">Continue as Guest</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>