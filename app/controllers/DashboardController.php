<?php

require_once __DIR__ . '/../models/Colleges.php';

class DashboardController {
    public function index() {
        session_start();

        if (!isset($_SESSION["admin_id"])) {
            header("Location: ../index.php");
            exit();
        }

        include __DIR__ . '/../views/dashboard.php';
    }
}

?>