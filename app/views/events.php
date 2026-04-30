<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - PAUGNAT 2027</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=1.0">
</head>
<body>

    <nav class="navbar navbar-expand-md fixed-top fw-bold navbar-custom shadow-sm">
        <div class="container py-1">
            <a href="../home.php" class="navbar-brand text-dark hover-glow fs-4">PAUGNAT</a>
            <button class="navbar-toggler border-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
                <div class="navbar-nav gap-3 align-items-center mt-3 mt-md-0">
                    <a href="../home.php" class="nav-link text-dark hover-glow">Home</a>
                    <a href="about.php" class="nav-link text-dark hover-glow">About</a>
                    <a href="events.php" class="nav-link text-dark hover-glow">Events</a>
                    <a href="leaderboards.php" class="nav-link text-dark hover-glow">Leaderboards</a>
                    <a href="contact.php" class="nav-link text-dark hover-glow">Contact</a>
                    <?php if ($isAdmin): ?>
                        <span class="badge bg-dark text-warning px-3 py-2 ms-md-2 mt-2 mt-md-0 rounded-pill shadow-sm">Admin Mode</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5 min-vh-100 d-flex flex-column mb-5">
        <h2 class="text-center fw-bold mb-5 mt-4 text-ustp-gold" style="letter-spacing: 2px;">BLAZING EVENTS</h2>

        <div class="row g-4 flex-grow-1 align-content-start">
            <?php if (isset($events) && count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4">
                        <div class="card glass-card text-white p-2 h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <?php if (!empty($event['images'])): ?>
                                    <div class="mb-3 d-flex overflow-hidden rounded-3" style="height: 160px; gap: 2px;">
                                        <?php foreach ($event['images'] as $img): ?>
                                            <img src="../<?php echo htmlspecialchars($img); ?>" alt="Event Image"
                                                 style="flex: 1 1 0; min-width: 0; height: 100%; object-fit: cover;">
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="display-4 mb-3 text-ustp-gold">📅</div>
                                <?php endif; ?>
                                <h5 class="card-title fw-bold fs-4 mb-3"><?php echo htmlspecialchars($event['eventName']); ?></h5>
                                <p class="card-text text-light opacity-75 mb-4 mt-auto">Date: <?php echo htmlspecialchars(date('F j, Y', strtotime($event['eventDate']))); ?></p>
                                <div>
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2 shadow-sm">Scheduled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-white opacity-75 py-5">
                    <p class="fs-4 mb-0">No events scheduled yet. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($isAdmin): ?>
        <div class="mt-5 p-4 rounded-4 border-warning glass-card">
            <h4 class="text-warning fw-bold mb-3 fs-3">Admin Controls</h4>
            <p class="text-light opacity-75 mb-4 fs-5" style="line-height: 1.6;">You are currently viewing Events as an admin. Use your dashboard to update ongoing events, post new events, and assign live points to colleges.</p>
            <div class="d-flex gap-3">
                <a href="../admin/dashboard.php" class="btn btn-warning btn-modern px-4 py-2 fw-bold text-dark">Access Dashboard</a>
                <a href="../admin/logout.php" class="btn btn-outline-light btn-modern px-4 py-2 fw-bold">Logout</a>
            </div>
        </div>
        <?php endif; ?>

        <div class="text-center mt-auto pt-5 mt-5">
            <a href="../home.php" class="btn btn-outline-warning btn-modern px-5 py-2 fw-bold">Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>