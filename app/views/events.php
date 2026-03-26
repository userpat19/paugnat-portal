<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - PAUGNAT 2027</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-dark text-white">

    <nav class="navbar fixed-top  fw-bold text-dark bg-ustp-gold">
        <div class="container">
            <a href="../home.php" class="navbar-brand">PAUGNAT</a>
            <div class="gap-5 d-flex">
                <a href="../home.php" class="nav-link ">Home</a>
                <a href="about.php" class="nav-link ">About</a>
                <a href="events.php" class="nav-link ">Events</a>
                <a href="leaderboards.php" class="nav-link ">Leaderboards</a>
                <a href="contact.php" class="nav-link ">Contact</a>
                <?php if ($isAdmin): ?>
                    <span class="badge bg-dark text-warning align-self-center">Admin Mode</span>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5 min-vh-100">
        <h2 class="text-center fw-bold mb-4" style="color: #FFD700;">CHAMPIONSHIP EVENTS</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-secondary text-white border-0 shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Basketball</h5>
                        <p class="card-text text-light">Location: Gym 1</p>
                        <span class="badge bg-danger">LIVE</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-secondary text-white border-0 shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">E-Sports</h5>
                        <p class="card-text text-light">Location: ICT Lab</p>
                        <span class="badge bg-warning text-dark">Upcoming</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-secondary text-white border-0 shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Mass Dance</h5>
                        <p class="card-text text-light">Location: Main Stage</p>
                        <span class="badge bg-info text-dark">Registration Open</span>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($isAdmin): ?>
        <div class="mt-4 p-4 rounded-3 border border-warning bg-dark bg-opacity-50">
            <h4 class="text-warning fw-bold">Admin Controls</h4>
            <p class="text-light">You are viewing Events as admin. Use the dashboard to update events and points directly.</p>
            <a href="../admin/dashboard.php" class="btn btn-warning btn-modern me-2">Open Admin Dashboard</a>
            <a href="../admin/logout.php" class="btn btn-outline-light btn-modern">Logout</a>
        </div>
        <?php endif; ?>

        <div class="text-center mt-5">
            <a href="../home.php" class="btn btn-outline-warning">Back to Home</a>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>