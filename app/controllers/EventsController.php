<?php

require_once __DIR__ . '/../models/Database.php';

class EventsController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAdmin = isset($_SESSION["admin_id"]);

        $db = Database::getInstance()->getConnection();


        // Fetch events
        $result = $db->query("SELECT * FROM events ORDER BY eventDate ASC");

        $events = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                // Fetch related images (camelCase table)
                $imgResult = $db->query(
                    "SELECT imagePath FROM eventImages WHERE eventId = " . intval($row['id'])
                );

                $row['images'] = [];

                if ($imgResult) {
                    while ($img = $imgResult->fetch_assoc()) {
                        $row['images'][] = $img['imagePath'];
                    }
                }

                // fallback for legacy single image
                if (empty($row['images']) && !empty($row['imagePath'])) {
                    $row['images'][] = $row['imagePath'];
                }

                $events[] = $row;
            }
        }

        include __DIR__ . '/../views/events.php';
    }
}