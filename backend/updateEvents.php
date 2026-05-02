<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');
require_once __DIR__ . '/../app/models/Database.php';

$db = Database::getInstance()->getConnection();


$db->query("CREATE TABLE IF NOT EXISTS eventImages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eventId INT NOT NULL,
    imagePath VARCHAR(255) NOT NULL
)");

try { @$db->query("ALTER TABLE events ADD COLUMN imagePath VARCHAR(255) DEFAULT NULL"); } catch (Exception $e) {}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id         = isset($_POST['id']) ? intval($_POST['id']) : 0;
$event_name = isset($_POST['eventName']) ? trim($_POST['eventName']) : '';
$event_date = isset($_POST['eventDate']) ? trim($_POST['eventDate']) : '';

if ($event_name === '' || $event_date === '') {
    echo json_encode(['success' => false, 'message' => 'Please provide event name and date.']);
    exit;
}

// Save or update the event
if ($id > 0) {

    $stmt = $db->prepare('UPDATE events SET eventName = ?, eventDate = ? WHERE id = ?');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
        exit;
    }

    $stmt->bind_param('ssi', $event_name, $event_date, $id);
    $stmt->execute();
    $success = $stmt->affected_rows >= 0;
    $stmt->close();
    $message = 'Event updated successfully.';
} else {

    $stmt = $db->prepare('INSERT INTO events (eventName, eventDate) VALUES (?, ?)');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
        exit;
    }

    $stmt->bind_param('ss', $event_name, $event_date);
    $success = $stmt->execute();
    $id = $db->insert_id;
    $stmt->close();
    $message = 'Event created successfully.';
}

if (!$success) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
    exit;
}

// Handle multiple image uploads
$uploadDir = __DIR__ . '/../images/events/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// PHP reads name="event_image[]" under key 'event_image[]'
$filesKey = isset($_FILES['event_image']) ? 'event_image' : 'event_image[]';

if (!empty($_FILES[$filesKey]['name'][0])) {
    $files = $_FILES[$filesKey];
    $count = count($files['name']);

    // Delete old images for this event before saving new ones
    $del = $db->prepare('DELETE FROM eventImages WHERE eventId = ?');
    $del->bind_param('i', $id);
    $del->execute();
    $del->close();

    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) continue;

        $newFileName = time() . '_' . rand(100, 999) . '_' . $i . '.' . $ext;
        if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $newFileName)) {
            $imgPath = 'images/events/' . $newFileName;
            $stmt = $db->prepare('INSERT INTO eventImages (eventId, imagePath) VALUES (?, ?)');
            $stmt->bind_param('is', $id, $imgPath);
            $stmt->execute();
            $stmt->close();
        }
    }
}

echo json_encode(['success' => $success, 'message' => $message]);
?>
