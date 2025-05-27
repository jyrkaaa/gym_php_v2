<?php
if (isset($_SESSION['user_id'])) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Record Exercise Reps</title>
            <!-- Include Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
            <link rel="manifest" href="./../../files/site.webmanifest">
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .card {
                    margin-top: 50px;
                }

                .btn-outline-light {
                    margin-right: 1rem;
                    margin-left: 1rem;
                }
                .btn-dark {
                    margin-top: 10px;
                }
                .personal-best-container {
                    margin-bottom: 20px;
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
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="card-title text-center">Add A Rep to <?=htmlspecialchars($personalBest[2]) ?? 'Exercise';?></h1>
                    <p class="text-center text-muted">Enter the weight and number of reps for your exercise.</p>
                    <?php if ($personalBest[0] !== 0): { ?>
                    <!-- Display Personal Best -->
                    <div class="personal-best-container text-center">
                        <p><strong>Personal best is <?= htmlspecialchars($personalBest[0]) ?> rep(s) at <?= htmlspecialchars($personalBest[1]) ?> kg</strong></p>
                        <!-- Button to insert personal best values into form -->
                        <button type="button" class="btn btn-warning" id="insertPersonalBestBtn">Use Personal Best</button>
                    </div>
                    <?php }; endif; ?>

                    <form method="POST" class="mt-4">
                        <!-- Hidden Input for Exercise in Workout -->
                        <input type="hidden" name="exer_in_workout" value="<?= htmlspecialchars($exer_in_workout ?? '') ?>">

                        <!-- Weight Input -->
                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (kg):</label>
                            <input type="number" step="0.1" min="0" class="form-control" id="weight" name="weight"
                                   placeholder="Enter weight" required>
                        </div>

                        <!-- Reps Input -->
                        <div class="mb-3">
                            <label for="reps" class="form-label">Number of Reps:</label>
                            <input type="number" min="1" class="form-control" id="reps" name="reps"
                                   placeholder="Enter number of reps" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="makeRep" class="btn btn-dark w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Add event listener to the button to insert personal best values into the fields
            document.getElementById('insertPersonalBestBtn').addEventListener('click', function() {
                document.getElementById('weight').value = <?= $personalBest[1] ?? 0 ?>;
                document.getElementById('reps').value = <?= $personalBest[0] ?? 0 ?>;
            });
        </script>
        <!-- Include Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


        </body>
        </html>
        <?php
    } else header('Location: /./../Tracker');

