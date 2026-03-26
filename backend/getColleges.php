<?php
header("Content-Type: application/json");
require_once '../app/models/Colleges.php';

$collegesModel = new Colleges();
$colleges = $collegesModel->getAll();

echo json_encode($colleges);
?>