<?php
if (isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exercises</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
        <link rel="manifest" href="./../../files/site.webmanifest">
        <style>
            .category-title {
                margin-top: 30px;
                border-bottom: 2px solid #ccc;
                padding-bottom: 5px;
            }
            .card {
                margin-bottom: 20px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .card:hover {
                transform: scale(1.05);
                box-shadow: 0 8px rgba(0, 0, 0, 0.2);
            }

            .btn-outline-light {
                margin-left: 10px;
            }
        </style>
        <script>
            // JavaScript for dynamic search
            function filterExercises() {
                const query = document.getElementById('searchInput').value.toLowerCase();
                const categories = document.querySelectorAll('.category');
                categories.forEach(category => {
                    const categoryTitle = category.querySelector('.category-title').textContent.toLowerCase();
                    const exercises = category.querySelectorAll('.exercise-card');
                    let categoryVisible = false;

                    exercises.forEach(exercise => {
                        const exerciseName = exercise.querySelector('.card-title').textContent.toLowerCase();
                        if (categoryTitle.includes(query) || exerciseName.includes(query)) {
                            exercise.style.display = 'block';
                            categoryVisible = true;
                        } else {
                            exercise.style.display = 'none';
                        }
                    });

                    category.style.display = categoryVisible ? 'block' : 'none';
                });
            }
        </script>
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
                <form method="GET" class="d-inline">
                    <button type="submit" name="" class="btn btn-outline-light">Go Back</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>All Exercises</h1>

        <!-- Add New Exercise Button -->
        <form method="GET" class="mb-4">
            <button type="submit" name="make_exercise" class="btn btn-success">Add New Exercise</button>

        <!-- Search Bar -->
        <div class="mb-4">
            <input
                    type="text"
                    id="searchInput"
                    onkeyup="filterExercises()"
                    class="form-control"
                    placeholder="Search by category or exercise name"
            >
        </div>

        <!-- Display grouped exercises -->
        <?php if (!empty($exercises)) : ?>
            <?php foreach ($exercises as $category_name => $exerciseList): ?>
                <div class="category">
                    <h2 class="category-title"><?= htmlspecialchars($category_name) ?></h2>
                    <div class="row">
                        <?php foreach ($exerciseList as $exercise): ?>
                            <div class="col-md-4 exercise-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($exercise['exercise_name']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($exercise['exercise_description']) ?: 'No description provided' ?></p>
                                        <button type="submit" name="del_ex" value="<?= $exercise['exercise_id'] ?>" class="btn btn-danger">Delete Exercise</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No exercises available</p>
        <?php endif; ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else header('Location: /./Tracker');