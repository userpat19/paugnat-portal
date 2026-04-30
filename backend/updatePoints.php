<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../app/models/Colleges.php';

try {
    $id = intval($_POST["id"] ?? 0);
    $points = intval($_POST["points"] ?? 0);

    if ($id <= 0 || $points === 0) {
        echo json_encode(["success"=>false,"message"=>"Invalid input"]);
        exit();
    }

    $model = new Colleges();
    $ok = $model->updatePoints($id, $points);

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Points updated" : "Update failed"
    ]);

} catch (Throwable $e) {
    echo json_encode(["success"=>false,"message"=>$e->getMessage()]);
}