<?php
if (isset($_SESSION['user_id'])) {

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GymBuddy</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="./../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../files/favicon-16x16.png">
        <link rel="manifest" href="./../files/site.webmanifest">
        <style>
            .box {
                min-height: 400px; /* Adjusted for smaller screens */
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
                background-color: #f8f9fa;
                cursor: pointer;
                transition: transform 0.2s;
                font-size: 1.5rem; /* Slightly smaller for responsiveness */
                font-weight: 500;
                color: #000;
            }

            .box:hover {
                transform: scale(1.05);
                background-color: #e9ecef;
            }

            .h4 {
                color: white;
                margin-left: 2rem;
            }

            .btn-outline-light {
                margin-right: 1rem;
                margin-left: 1rem;

            }

            @media (max-width: 576px) {
                .box {
                    min-height: 200px; /* Adjusted for smaller screens */
                    font-size: 1.2rem; /* Further reduced for small screens */
                    padding: 15px;
                }
            }
        </style>
    </head>
    <body class="bg-light">
    <nav class="navbar bg-dark border-bottom border-body py-3" data-bs-theme="dark">
        <div class="container-fluid">
            <!-- Dropdown on the Left -->
            <div class="d-flex justify-content-start">
                <li class="nav-item dropdown">
                    <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        Menu
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <form method="GET">
                                <button type="submit" class="dropdown-item" name="workouts_list">Workouts</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET">
                                <button type="submit" class="dropdown-item" name="exercises_list">Exercises</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET">
                            <button type="submit" class="dropdown-item" name="statistics">Statistics</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET">
                            <button type="submit" class="dropdown-item" name="continueWorkout">Resume Workout</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET">
                            <button type="submit" class="dropdown-item" name="guide">Guide</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET">
                            <button type="submit" class="dropdown-item" name="newUserWeight">Add Weight</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </div>

            <!-- Existing "Go Back" Button on the Right -->
            <div class="d-flex justify-content-end">
                <form method="POST" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-outline-light">Sign Out</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="container my-4">
        <form method="get">
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-dark btn-lg mb-2" name="continueWorkout">Resume an active Workout
                </button>
            </div>
            <div class="row g-3">
                <!-- Exercises Box -->
                <div class="col-12 col-md-4">
                    <button type="submit" name="exercises_list" class="box w-100">Exercises</button>
                </div>
                <!-- Workouts Box -->
                <div class="col-12 col-md-4">
                    <button type="submit" class="box w-100" name="workouts_list">Workouts</button>
                </div>
                <!-- Statistics Box -->
                <div class="col-12 col-md-4">
                    <button type="submit" name="statistics" class="box w-100">Statistics</button>
                </div>
            </div>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else {
    header('Location: /./Tracker');
}
