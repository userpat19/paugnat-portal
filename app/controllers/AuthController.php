<?php

require_once __DIR__ . '/../models/Admins.php';

class AuthController {
    public function login() {
        session_start();
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (empty($username) || empty($password)) {
                $error = "Please fill in all fields";
            } else {
                $adminModel = new Admins();
                $admin = $adminModel->authenticate($username, $password);
                if ($admin) {
                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];
                    header("Location: admin/dashboard.php");
                    exit();
                } else {
                    $error = "Invalid credentials";
                }
            }
        }

        include __DIR__ . '/../views/login.php';
    }
}

?>