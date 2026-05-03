<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/models/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid event ID']);
    exit;
}

$db = Database::getInstance()->getConnection();

// Delete associated images first
$db->query("DELETE FROM eventImages WHERE eventId = $id");

// Delete the event
$stmt = $db->prepare('DELETE FROM events WHERE id = ?');
$stmt->bind_param('i', $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success, 'message' => $success ? 'Event deleted.' : 'Failed to delete event.']);
?>
