<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/mailconfig.php';
require_once __DIR__ . '/../app/models/Database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Input sanitization
$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (
    $name === '' ||
    $email === '' ||
    $message === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields with valid data']);
    exit;
}

// ✅ Use YOUR database class (correct architecture)
$db = Database::getInstance()->getConnection();

// Insert message
$stmt = $db->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database prepare failed']);
    exit;
}

$stmt->bind_param("sss", $name, $email, $message);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to save message']);
    exit;
}

$stmt->close();

// Email sending (unchanged logic)
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = MAIL_USERNAME;
    $mail->Password   = MAIL_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = MAIL_PORT;

    $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_TO);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(false);
    $mail->Subject = 'PAUGNAT Contact: ' . $name;
    $mail->Body    = "Name: $name\nEmail: $email\n\n$message";

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => "Your message has been sent successfully."
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Message could not be sent. Please try again later.'
    ]);
}