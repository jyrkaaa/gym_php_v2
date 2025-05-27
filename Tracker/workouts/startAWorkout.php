<?php
if (isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Workout</title>
        <!-- Include Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
        <link rel="manifest" href="./../../files/site.webmanifest">
        <style>
            .btn-outline-light {
                margin-right: 1rem;
                margin-left: 1rem;
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
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Create a New Workout</h1>
                <form method="POST">
                    <!-- Input for workout name -->
                    <div class="mb-3">
                        <label for="workout_name" class="form-label">Workout Name:</label>
                        <input type="text" class="form-control" id="workout_name" name="workout_name"
                               placeholder="Enter workout name" required>
                    </div>

                    <!-- Input for workout date -->
                    <div class="mb-3">
                        <label for="workout_date" class="form-label">Workout Date:</label>
                        <input type="date" class="form-control" id="workout_date" name="workout_date" required>
                        <button type="button" class="btn btn-warning" id="fill_today">Today</button>

                    </div>
                    <!-- Checkbox section for exercises -->
                    <div class="mb-3">
                        <label class="form-label">Select Exercises:</label>
                        <div class="row">
                            <?php if (isset($exercises)): ?>
                                <?php foreach ($exercises as $index => $exercise): ?>
                                    <div class="col-md-4"> <!-- Adjust column width using Bootstrap grid classes -->
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                   id="exercise_<?= $exercise['id'] ?>" name="exercises[]"
                                                   value="<?= $exercise['id'] ?>">
                                            <label class="form-check-label" for="exercise_<?= $exercise['id'] ?>">
                                                <?= htmlspecialchars($exercise['name']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div class="text-center">
                        <button type="submit" name="startWorkout" class="btn btn-dark">Create Workout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // JavaScript to set today's date in the date input field
        document.getElementById('fill_today').addEventListener('click', function () {
            const today = new Date().toISOString().split('T')[0]; // Get today's date in 'YYYY-MM-DD' format
            document.getElementById('workout_date').value = today; // Set it as the value of the date input
        });
    </script>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else header('Location: /./../Tracker');
