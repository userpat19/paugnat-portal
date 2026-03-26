<?php

class EventsController {
    public function index() {
        session_start();
        $isAdmin = isset($_SESSION["admin_id"]);
        include __DIR__ . '/../views/events.php';
    }
}

?>