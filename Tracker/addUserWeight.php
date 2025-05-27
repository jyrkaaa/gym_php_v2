<?php
if (isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Weight Tracker</title>
        <!-- Include Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-outline-light {
            margin-left: 10px;
        }
    </style>
    </head>
    <body class="bg-light">
    <nav class="navbar bg-dark border-bottom border-body py-3" data-bs-theme="dark">
        <div class="container-fluid">
            <!-- Dropdown on the Left -->
            <div class="d-flex justify-content-start">
                <li class="nav-item dropdown">
                    <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <form method="GET"">
                            <button type="submit" class="dropdown-item" name="statistics">Statistics</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET"">
                            <button type="submit" class="dropdown-item" name="continueWorkout">Resume Workout</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET"">
                            <button type="submit" class="dropdown-item" name="guide">Guide</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET"">
                            <button type="submit" class="dropdown-item" name="newUserWeight">Add Weight</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </div>

            <!-- Existing "Go Back" Button on the Right -->
            <div class="d-flex justify-content-end">
                <form method="GET" class="d-inline">
                    <button type="submit" name="" class="btn btn-outline-light">Go Back</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <?php if ($error):?>
        <div id="errorBox" class="alert alert-danger" role="alert">
            Error Adding Weight
        </div>
        <?php endif; ?>
        <h1 class="text-center mb-4">Add Your Weight</h1>

        <!-- Form for adding weight, time, and description -->
        <form method="POST">
            <!-- Weight Input (float) -->
            <div class="mb-3">
                <label for="weight" class="form-label">Weight (kg)</label>
                <input type="number" step="0.1" id="weight" name="weight" class="form-control" placeholder="Enter your weight" required>
            </div>

            <!-- Time Picker -->
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="datetime-local" id="time" name="time" class="form-control" required>
            </div>

            <!-- Optional Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea id="description" name="description" class="form-control" placeholder="Overall wellbeing (optional)"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="addUserWeight">Add User Weight</button>
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

    <?php
} else header('Location: /./Tracker');


