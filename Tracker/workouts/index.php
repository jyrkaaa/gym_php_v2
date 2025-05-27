<?php
if (isset($_SESSION['user_id'])) {
    $workouts_by_month = [];
    $current_month = date("F Y"); // Get current month and year

    // Group workouts by month
    foreach ($workouts as $workout) {
        $month = date("F Y", strtotime($workout['workout_date'])); // Extract month and year
        $workouts_by_month[$month][] = $workout;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Workouts</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
        <link rel="manifest" href="./../../files/site.webmanifest">
        <style>
            .search-bar {
                margin-bottom: 20px;
            }

            .workout-card {
                margin-bottom: 15px;
            }

            .btn-outline-light {
                margin: 10px;
            }

            .make-workout-btn {
                margin-top: 1rem;
                margin-bottom: 1rem;
            }

            .card {
                margin-bottom: 20px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card:hover, .btn:hover {
                transform: scale(1.05);
            }

            .card:hover, .card-outline-success:hover {
                transform: scale(1.05); /* Optional: Slight zoom effect on hover */
                box-shadow: 0 8px rgba(30, 30, 30, 0.2);
            }

            .card-outline-success {
                border: 3px solid green; /* Set a thicker green border */
                border-radius: 0.5rem; /* Optional: Adjust border radius */
                box-shadow: 0 8px 16px rgba(0, 128, 0, 0.2); /* Optional: Add a soft green shadow */
                transition: transform 0.3s ease, box-shadow 0.3s ease; /* Optional: Hover effect */
            }
            .accordion-button:not(.collapsed) {
                color: white;
                background-color: #333333;
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
                                <button type="submit" class="dropdown-item" name="continueWorkout">Resume Workout
                                </button>
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
                <form method="GET" class="d-inline">
                    <button type="submit" name="" class="btn btn-outline-light">Go Back</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Your Workouts</h1>

        <!-- Make Workout Button -->
        <form method="GET">
            <button type="submit" name="makeWorkout" class="btn btn-success make-workout-btn">Create Workout</button>
        </form>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control"
                   placeholder="Search by workout name or date (e.g., 2025-01-01) ">
        </div>

        <form method="GET" id="workoutsForm">
            <!-- Workouts List -->
            <div class="row" id="workoutsList">
                <?php if ($workouts) : ?>
                    <div class="accordion" id="workoutsAccordion">
                        <?php foreach ($workouts_by_month as $month => $month_workouts): ?>
                            <?php $isCurrentMonth = ($month === $current_month); ?> <!-- Check if it's the current month -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-<?= md5($month) ?>">
                                    <button class="accordion-button <?= $isCurrentMonth ? '' : 'collapsed' ?>"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-<?= md5($month) ?>"
                                            aria-expanded="<?= $isCurrentMonth ? 'true' : 'false' ?>">
                                        <?= htmlspecialchars($month) . "  ( " . count($month_workouts) . " workouts )" ?>
                                    </button>
                                </h2>
                                <div id="collapse-<?= md5($month) ?>"
                                     class="accordion-collapse collapse <?= $isCurrentMonth ? 'show' : '' ?>"
                                     data-bs-parent="#workoutsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <?php foreach ($month_workouts as $workout): ?>
                                                <?php $isActive = (isset($_SESSION['current_workout']) && $_SESSION['current_workout'] == $workout['workout_id']); ?>
                                                <div class="col-12 col-md-4 d-flex">
                                                    <div class="card workout-card flex-fill <?= $isActive ? 'card-outline-success' : '' ?>"
                                                         data-name="<?= htmlspecialchars($workout['workout_name']) ?>"
                                                         data-date="<?= htmlspecialchars($workout['workout_date']) ?>">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?= htmlspecialchars($workout['workout_name']) ?></h5>
                                                            <p class="card-text">
                                                                <strong>Date:</strong> <?= htmlspecialchars($workout['workout_date']) ?>
                                                            </p>
                                                            <button class="btn btn-primary make-workout-btn"
                                                                    name="editWorkout"
                                                                    value="<?= $workout['workout_id'] ?>">
                                                                <strong>Modify</strong></button>
                                                            <button type="submit" name="setActive"
                                                                    value="<?= $workout['workout_id'] ?>"
                                                                    class="btn <?= $isActive ? 'btn-success' : 'btn-outline-success' ?>">
                                                                <?= $isActive ? 'Active' : 'Set Active' ?>
                                                            </button>
                                                            <button type="button"
                                                                    class="btn btn-danger delete-workout-btn"
                                                                    data-id="<?= $workout['workout_id'] ?>"
                                                                    data-name="<?= htmlspecialchars($workout['workout_name']) ?>">
                                                                <strong>Delete</strong>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">No workouts found.</p>
                <?php endif; ?>
            </div>

            <!-- Hidden input for delete action -->
            <input type="hidden" name="deleteWorkout" id="deleteWorkoutInput" value="">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter workouts based on search input
        document.getElementById('searchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const workoutCards = document.querySelectorAll('.workout-card');

            workoutCards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const date = card.getAttribute('data-date').toLowerCase();

                if (name.includes(searchTerm) || date.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add confirmation for delete buttons
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-workout-btn');
            const deleteWorkoutInput = document.getElementById('deleteWorkoutInput');
            const workoutsForm = document.getElementById('workoutsForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const workoutName = this.getAttribute('data-name');
                    const workoutId = this.getAttribute('data-id');

                    // Show confirmation dialog
                    const confirmed = confirm(`Are you sure you want to delete the workout "${workoutName}"?`);
                    if (confirmed) {
                        // Set the hidden input value and submit the form
                        deleteWorkoutInput.value = workoutId;
                        workoutsForm.submit();
                    }
                });
            });
        });
    </script>
    </body>
    </html>
    <?php
} else header('Location: /./../Tracker');
?>
