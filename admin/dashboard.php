<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUGNAT Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION["admin_username"]); ?></h1>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3>Update College Points</h3>
                    <form id="pointsForm">
                        <div class="mb-3">
                            <label class="form-label">Select College</label>
                            <select name="id" id="collegeId" class="form-select" required>
                                <option value="">Loading colleges...</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Points</label>
                            <input type="number" name="points" id="points" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Update Points</button>
                    </form>

                    <div id="pointsMessage" class="mt-3"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3>Update Event</h3>
                    <form id="eventForm">
                        <div class="mb-3">
                            <label class="form-label">Event ID</label>
                            <input type="number" name="id" id="eventId" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Name</label>
                            <input type="text" name="event_name" id="eventName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="event_date" id="eventDate" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Event</button>
                    </form>

                    <div id="eventMessage" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <a href="messages.php" class="btn btn-info text-dark">View Messages</a>
            <a href="../pages/events.php" class="btn btn-dark">Public Events</a>
            <a href="../pages/leaderboards.php" class="btn btn-dark">Public Leaderboards</a>
            <a href="../home.php" class="btn btn-secondary">Public Home</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>