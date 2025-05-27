<?php
if (isset($_SESSION['user_id']))  {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Workout Details</title>
        <!-- Include Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Include Select2 CSS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
        <link rel="manifest" href="./../../files/site.webmanifest">
        <style>
            :root {
                --grey-700: #555555; /* Global definition */
            }
            .btn {
                margin: 5px;
            }
            .list-group-item ul {
                margin-top: 10px;
            }
            .btn-outline-light {
                margin-right: 1rem;
                margin-left: 1rem;
            }
            .info-button {
                position: relative; /* For positioning the tooltip */
                background-color: transparent; /* Transparent button */
                border: none; /* No border */
                cursor: pointer;
                padding: 0;
            }

            /* SVG icon styling */
            .info-button svg {
                width: 24px;
                height: 24px;
                stroke: var(--grey-700, #555); /* Default color with fallback */
            }

            /* Tooltip styling */
            .tooltip {
                position: absolute;
                bottom: 125%; /* Position tooltip above the icon */
                left: 50%;
                transform: translateX(-50%);
                background-color: #333;
                color: #fff;
                text-align: center;
                padding: 8px;
                border-radius: 4px;
                font-size: 14px;
                visibility: hidden; /* Hidden by default */
                opacity: 0; /* Transparent by default */
                transition: opacity 0.3s ease; /* Smooth fade-in/out */
                white-space: nowrap; /* Prevent text wrapping */
            }

            /* Show the tooltip when button is hovered */
            .info-button:hover .tooltip {
                visibility: visible;
                opacity: 1;
            }

            /* Optional: Add a small arrow to the tooltip */
            .tooltip::after {
                content: '';
                position: absolute;
                top: 100%; /* Position arrow below the tooltip */
                left: 50%;
                transform: translateX(-50%);
                border-width: 6px;
                border-style: solid;
                border-color: #333 transparent transparent transparent;
            }
            /* Make disabled options bold */
            .category-option:disabled {
                font-weight: bold;
                color: #000; /* Adjust color if needed */
            }

            /* Optional: If Select2 is interfering, ensure bold options remain visible */
            #exercise_id option:disabled {
                font-weight: bold;
                color: #000; /* You can change this color to make the bold text stand out */
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
                <h1 class="card-title text-center mb-4"><?= htmlspecialchars($workoutDetails['workout_name'] ?? 'Error') ?></h1>
                <h3><strong>Date:</strong> <?= htmlspecialchars($workoutDetails['workout_date'] ?? 'Error') ?></h3>

                <h3 class="mt-4">Current Exercises</h3>
                <?php if (!empty($exercises)): ?>
                    <form method="post">
                        <input type="hidden" name="workout_id" value="<?= htmlspecialchars($workoutId ?? 'Error') ?>">
                        <div class="row g-3">
                            <?php foreach ($exercises as $exercise): ?>
                                <div class="col-md-6"> <!-- Adjust column width as needed -->
                                    <div class="card border-dark mb-3">
                                        <div class="card-header bg-dark text-white">
                                            <strong>Exercise Name:</strong> <?= htmlspecialchars($exercise['exercise_name']) ?>
                                        </div>
                                        <div class="card-body">
                                            <?php if (!empty($exercise['sets'])): ?>
                                                <ul class="list-group">
                                                    <?php foreach ($exercise['sets'] as $rep): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <strong>Weight:</strong> <?= htmlspecialchars($rep['weight']) ?> kg,
                                                                <strong>Reps:</strong> <?= htmlspecialchars($rep['reps']) ?>
                                                            </div>
                                                            <div>
                                                                <!--Info logo-->
                                                                <button class="info-button" type="submit" value="<?= htmlspecialchars($exercise['exercise_id']) ?>" name="repDisc">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info">
                                                                        <circle cx="12" cy="12" r="10"></circle>
                                                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                                                    </svg>
                                                                    <!-- Tooltip -->
                                                                    <div class="tooltip">See Rep Info</div>
                                                                </button>
                                                                <button type="submit" class="btn btn-danger btn-sm" value="<?= htmlspecialchars($rep['rep_id']) ?>" name="delRep">Delete</button>
                                                                <button type="submit" class="btn btn-primary btn-sm" value="<?= htmlspecialchars($exercise['exer_in_workout_id']) . ',' . htmlspecialchars($rep['reps']) . ',' . htmlspecialchars($rep['weight']) ?>" name="copyRep">Duplicate</button>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p class="text-warning">No reps recorded for this exercise.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between">
                                            <button type="submit" name="addRep" value="<?= htmlspecialchars($exercise['exer_in_workout_id']) ?>" class="btn btn-primary btn-sm">Add Rep</button>
                                            <button type="submit" name="delExFromWorkout" value="<?= htmlspecialchars($exercise['exer_in_workout_id']) ?>" class="btn btn-danger btn-sm">Delete Exercise</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-warning">No exercises in this workout yet.</p>
                <?php endif; ?>

                <h3 class="mt-4">Add an Exercise</h3>
                <form method="POST" class="mt-3">
                    <input type="hidden" name="workout_id" value="<?= htmlspecialchars($workoutId ?? 'Error') ?>">

                    <div class="mb-3">
                        <label for="exercise_id" class="form-label">Select Exercise:</label>
                        <select class="form-select" name="exercise_id" id="exercise_id" required style="width:100%;">
                            <option value="" disabled selected>Choose an exercise</option>
                            <?php
                            if ($groupedExercises) {
                                foreach ($groupedExercises as $categoryName => $exercises) {
                                    // Create an option for the category to act as a label
                                    echo '<option disabled class="category-option">' . htmlspecialchars($categoryName) . '</option>';

                                    foreach ($exercises as $exercise) {
                                        // Add exercise option with both category and exercise name searchable
                                        echo '<option value="' . htmlspecialchars($exercise['exercise_id']) . '" data-category="' . htmlspecialchars($categoryName) . '" data-name="' . htmlspecialchars($exercise['exercise_name']) . '">' . htmlspecialchars($exercise['exercise_name']) . '</option>';
                                    }
                                }
                            } else {
                                echo '<option value="" disabled>No exercises available</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="add_exercise_to_workout" class="btn btn-dark">Add Exercise</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery -->
    <script>
        $(document).ready(function() {
            $('#exercise_id').select2({
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    let term = params.term.toLowerCase();
                    let text = data.text.toLowerCase();
                    let category = $(data.element).data('category')?.toLowerCase() || "";

                    if (text.includes(term) || category.includes(term)) {
                        return data;
                    }
                    return null;
                }
            });
        });
    </script>
    </body>
    </html>

    <?php
}  else header('Location: /./../Tracker');