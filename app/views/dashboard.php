<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUGNAT Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=1.0">
</head>
<body class="admin-dashboard">

<?php
require_once __DIR__ . '/../models/Database.php';
$conn = Database::getInstance()->getConnection();

$stats = [
    'colleges' => 0,
    'events' => 0,
    'messages' => 0,
    'top_college' => 'No data yet',
    'top_points' => 0
];

$conn->query("CREATE TABLE IF NOT EXISTS colleges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    points INT DEFAULT 0
)");

$conn->query("CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL
)");

try { @$conn->query("ALTER TABLE events ADD COLUMN image_path VARCHAR(255) DEFAULT NULL"); } catch (Exception $e) {}

$conn->query("CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new','read') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$result = $conn->query("SELECT COUNT(*) AS count FROM colleges");
if ($result) {
    $stats['colleges'] = intval($result->fetch_assoc()['count']);
}

$result = $conn->query("SELECT COUNT(*) AS count FROM events");
if ($result) {
    $stats['events'] = intval($result->fetch_assoc()['count']);
}

$result = $conn->query("SELECT COUNT(*) AS count FROM messages");
if ($result) {
    $stats['messages'] = intval($result->fetch_assoc()['count']);
}

$result = $conn->query("SELECT name, points FROM colleges ORDER BY points DESC LIMIT 1");
if ($result && $result->num_rows === 1) {
    $top = $result->fetch_assoc();
    $stats['top_college'] = $top['name'];
    $stats['top_points'] = intval($top['points']);
}
?>

    <div class="container py-5 admin-dashboard-container">
        <div class="card glass-card dashboard-hero border-0 shadow-lg overflow-hidden mb-5">
            <div class="row g-0 align-items-center">
                <div class="col-lg-8 p-5">
                    <small class="text-uppercase text-warning fw-bold hover-glow">Admin Dashboard</small>
                    <h1 class="display-5 fw-bold mb-3 text-ustp-gold" style="letter-spacing: -1px;">Welcome back, <?php echo htmlspecialchars($_SESSION["admin_username"]); ?></h1>
                    <p class="text-secondary mb-4 text-white opacity-75 fs-5">Manage scores, events, and messages from one place with a modern admin interface.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="messages.php" class="btn btn-warning btn-modern px-4 py-2 fw-bold text-dark">Review Messages</a>
                        <a href="logout.php" class="btn btn-outline-light btn-modern px-4 py-2 fw-bold">Logout</a>
                    </div>
                </div>
                <div class="col-lg-4 dashboard-panel text-white p-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">Top College</h3>
                            <p class="mb-0 fs-5 text-ustp-gold"><?php echo htmlspecialchars($stats['top_college']); ?></p>
                        </div>
                        <span class="badge bg-ustp-gold text-dark py-2 px-3 fs-6 rounded-pill shadow-sm"><?php echo intval($stats['top_points']); ?> pts</span>
                    </div>
                    <div class="d-flex gap-3 flex-column">
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-light border-opacity-10 d-flex justify-content-between align-items-center">
                            <p class="mb-0 text-uppercase small opacity-75 fw-bold text-light">Colleges</p>
                            <h4 class="mb-0 fw-bold text-white"><?php echo intval($stats['colleges']); ?></h4>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-light border-opacity-10 d-flex justify-content-between align-items-center">
                            <p class="mb-0 text-uppercase small opacity-75 fw-bold text-light">Events</p>
                            <h4 class="mb-0 fw-bold text-white"><?php echo intval($stats['events']); ?></h4>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-light border-opacity-10 d-flex justify-content-between align-items-center">
                            <p class="mb-0 text-uppercase small opacity-75 fw-bold text-light">Messages</p>
                            <h4 class="mb-0 fw-bold text-white"><?php echo intval($stats['messages']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="card glass-card shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold text-ustp-gold">Update Points</h3>
                            <p class="text-secondary mb-0 opacity-75">Select a college and update its score.</p>
                        </div>
                        <span class="badge bg-warning text-dark py-2 px-3 rounded-pill shadow-sm">Live</span>
                    </div>
                    <form id="pointsForm">
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">College</label>
                            <select name="id" id="collegeId" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Points</label>
                            <input type="number" name="points" id="points" class="form-control bg-dark text-white border-secondary" placeholder="Enter points (+/-)" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 btn-modern fw-bold py-2 mt-auto">Update Points</button>
                    </form>
                    <div id="pointsMessage" class="mt-3"></div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card glass-card shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold text-info">Manage Events</h3>
                            <p class="text-secondary mb-0 opacity-75">Edit an existing event or create a new one.</p>
                        </div>
                        <span class="badge bg-dark border py-2 px-3 rounded-pill shadow-sm">Schedule</span>
                    </div>
                    <form id="eventForm">
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Select Event</label>
                            <select id="eventSelect" class="form-select bg-dark text-white border-secondary">
                                <option value="">Create New Event</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Event Name</label>
                            <input type="text" name="eventName" id="eventName" class="form-control bg-dark text-white border-secondary" placeholder="Event title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Event Date</label>
                            <input type="date" name="eventDate" id="eventDate" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">Event Images (Optional)</label>
                            <input type="file" name="event_image[]" id="eventImage" class="form-control bg-dark text-white border-secondary" accept="image/*" multiple>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info text-dark w-100 btn-modern fw-bold py-2">Save Event</button>
                            <button type="button" id="deleteEventBtn" class="btn btn-danger w-100 btn-modern fw-bold py-2" onclick="deleteEvent()" disabled>Delete Event</button>
                        </div>
                    </form>
                    <div id="eventMessage" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card glass-card shadow-sm p-4 h-100">
                    <h4 class="fw-bold mb-4 text-ustp-gold">College Standings</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover text-light align-middle">
                            <thead>
                                <tr class="text-secondary small text-uppercase border-bottom border-secondary">
                                    <th class="opacity-75">#</th>
                                    <th class="opacity-75">College</th>
                                    <th class="opacity-75 text-end">Points</th>
                                </tr>
                            </thead>
                            <tbody id="collegesTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card glass-card shadow-sm p-4 h-100">
                    <h4 class="fw-bold mb-4 text-info">Upcoming Events</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover text-light align-middle">
                            <thead>
                                <tr class="text-secondary small text-uppercase border-bottom border-secondary">
                                    <th class="opacity-75">ID</th>
                                    <th class="opacity-75">Event</th>
                                    <th class="opacity-75 text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody id="eventsTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 pt-3 d-flex flex-wrap gap-3 justify-content-center border-top border-secondary border-opacity-25">
            <a href="messages.php" class="btn btn-info text-dark btn-modern fw-bold px-4 py-2">✉ Review Messages</a>
            <a href="../pages/events.php" class="btn border btn-modern text-light fw-bold px-4 py-2 hover-glow">View Public Events</a>
            <a href="../pages/leaderboards.php" class="btn border btn-modern text-light fw-bold px-4 py-2 hover-glow">View Leaderboards</a>
            <a href="../home.php" class="btn border-warning text-warning btn-modern fw-bold px-4 py-2 hover-glow">Home Link</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js?v=1.3"></script>
</body>
</html>
