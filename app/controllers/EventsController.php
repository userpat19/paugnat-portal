<?php

require_once __DIR__ . '/../models/Database.php';

class EventsController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $isAdmin = isset($_SESSION["admin_id"]);
        
        $db = Database::getInstance()->getConnection();
        $db->query("CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_name VARCHAR(100) NOT NULL,
            event_date DATE NOT NULL
        )");

        $db->query("CREATE TABLE IF NOT EXISTS event_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL
        )");

        try { @$db->query("ALTER TABLE events ADD COLUMN image_path VARCHAR(255) DEFAULT NULL"); } catch (Exception $e) {}

        $result = $db->query("SELECT * FROM events ORDER BY eventDate ASC");
        $events = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Fetch images for this event
                $imgResult = $db->query("SELECT image_path FROM event_images WHERE event_id = " . intval($row['id']));
                $row['images'] = [];
                if ($imgResult) {
                    while ($img = $imgResult->fetch_assoc()) {
                        $row['images'][] = $img['image_path'];
                    }
                }
                // Fallback: include legacy single image_path if no new images
                if (empty($row['images']) && !empty($row['image_path'])) {
                    $row['images'][] = $row['image_path'];
                }
                $events[] = $row;
            }
        }

        include __DIR__ . '/../views/events.php';
    }
}

?>