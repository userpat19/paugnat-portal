<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUGNAT 2027 - University of Science and Technology of Southern Philippines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">

    <nav class="navbar fixed-top fw-bold text-dark bg-ustp-gold ">
        <div class="container">
            <a href="home.php" class="navbar-brand">PAUGNAT</a>
            <div class="gap-5 d-flex">
                <a href="home.php" class="nav-link">Home</a>
                <a href="pages/about.php" class="nav-link">About</a>
                <a href="pages/events.php" class="nav-link">Events</a>
                <a href="pages/leaderboards.php" class="nav-link">Leaderboards</a>

            </div>
        </div>
    </nav>

    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="text-center mb-5 pt-5 mt-5">
            <h1 class="display-1 fw-bold text-ustp-gold mb-3">PAUGNAT</h1>
            <h2 class="h3 fw-light text-light mb-4">2027</h2>
            <p class="lead text-light opacity-75 mb-5" style="max-width: 600px;">
                The University of Science and Technology of Southern Philippines Annual Games and Tournament
            </p>
        </div>

        <div class="row g-4 w-100" style="max-width: 800px;">
            <div class="col-md-6">
                <div class="card bg-secondary text-white border-0 shadow-lg h-100">
                    <div class="card-body text-center p-4">
                        <h3 class="card-title fw-bold mb-3">🏆 Events</h3>
                        <p class="card-text text-light mb-4">
                            Discover all the exciting championship events happening this year
                        </p>
                        <a href="pages/events.php" class="btn btn-warning fw-bold">View Events</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-secondary text-white border-0 shadow-lg h-100">
                    <div class="card-body text-center p-4">
                        <h3 class="card-title fw-bold mb-3">📊 Leaderboards</h3>
                        <p class="card-text text-light mb-4">
                            Check out the current standings and college rankings
                        </p>
                        <a href="pages/leaderboards.php" class="btn btn-warning fw-bold">View Rankings</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 w-100 mb-5" style="max-width: 600px; ">
            <div class="card shadow-lg bg-ustp-gold text-dark border-0 rounded-4">
                <div class="card-body text-center p-4 p-md-5">
                    <h4 class="card-title fw-bold mb-3">
                        🏅 Welcome Trailblazers!
                    </h4>
                    <p class="card-text mb-4 text-dark opacity-75">
                        Get ready for the most thrilling and competitive PAUGNAT yet!
                        Join thousands of students in celebrating sportsmanship, teamwork, and excellence
                        across multiple exciting events.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="pages/about.php" class="btn btn-dark fw-bold">Learn More</a>
                        <a href="pages/contact.php" class="btn btn-outline-dark fw-bold">Get in Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-black bg-opacity-75 text-light py-4 mt-auto">
        <div class="container text-center">

            <p class="mt-2 mb-0 small">&copy; 2027 PAUGNAT | USTP</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>