<?php
header("Content-Type: application/json");
require_once '../app/models/Colleges.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit();
}

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$points = isset($_POST["points"]) ? intval($_POST["points"]) : 0;

if ($id <= 0 || $points == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid input"
    ]);
    exit();
}

$collegesModel = new Colleges();
if ($collegesModel->updatePoints($id, $points)) {
    echo json_encode([
        "success" => true,
        "message" => "Points updated successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Update failed"
    ]);
}
?>