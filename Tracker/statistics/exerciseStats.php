<?php
if (isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exercise Progress Tracker</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
        <link rel="manifest" href="./../../files/site.webmanifest">
        <style>
            .exercise-card {
                margin-bottom: 20px;
            }

            .chart-container {
                position: relative;
                height: 400px;
                margin-bottom: 30px;
                max-width: 800px;
            }

            .search-bar {
                max-width: 500px;
                margin: 20px auto;
            }

            .set-item {
                background-color: #f8f9fa;
                padding: 10px;
                border-radius: 5px;
            }

            .set-title {
                color: #007bff;
                margin-bottom: 5px;
            }

            .set-divider {
                border-top: 1px dashed #dee2e6;
            }

            .exercises-container {
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }

            .btn-outline-light {
                margin-left: 10px;
            }
            .justify-content-center {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }
            .chart-container,
            .stats-container {
                width: 800px; /* Fixed size for chart */
                max-width: 800px;
                box-sizing: border-box;
            }
            .stats-container {
                max-width: 280px;
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            .stats-container, .card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .stats-container .percentage {
                font-size: 24px;
                font-weight: bold;
                color: #333;
            }
            .stats-container .text {
                font-size: 16px;
                color: #333;
            }

            .stats-container .arrow {
                display: inline-block;
                margin-top: 5px;
                font-size: 20px;
            }

            .stats-container .arrow.up {
                color: green;
            }

            .stats-container .arrow.down {
                color: red;
            }
            .card:hover, .stats-container:hover
            {
                transform: scale(1.05);
                box-shadow: 0 8px rgba(0, 0, 0, 0.2);
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
    <div class="container-fluid mt-4">
        <h1 class="text-center mb-4">Stats For <?= htmlspecialchars($exer_name) ?? "The Exercise" ?></h1>
        <!-- Graph Section -->
        <div class="justify-content-center align-items-start">
            <div class="chart-container">
                <canvas id="exerciseChart"></canvas>
            </div>
            <!-- Stats Box -->
            <div class="stats-container">
                <h5>Weight Change</h5>
                <div class="percentage">0%</div>
                <div class="arrow">&#8595;</div>
                <div class="text">0</div>
            </div>
        </div>
        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by date (e.g., 2025-01-01)">
        </div>

        <!-- Exercises Section -->
        <div class="exercises-container">
            <?php if (!empty($exercises)) : ?>
                <div class="row" id="exerciseList">
                    <?php foreach ($exercises as $exercise) : ?>
                        <div class="col-md-4 exercise-card mb-4" data-date="<?= htmlspecialchars($exercise['date']) ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title card-header bg-dark text-white"><?= htmlspecialchars($exercise['workout_name']) ?></h5>
                                    <p class="card-text">
                                        <strong>Date:</strong> <?= htmlspecialchars($exercise['date']) ?></p>
                                    <div class="sets-container">
                                        <?php foreach ($exercise['sets'] as $index => $set) : ?>
                                            <div class="set-item mb-2">
                                                <h6 class="set-title">Set <?= $index + 1 ?></h6>
                                                <p class="card-text mb-1">
                                                    <strong>Weight:</strong> <?= htmlspecialchars($set['weight']) ?></p>
                                                <p class="card-text mb-1">
                                                    <strong>Reps:</strong> <?= htmlspecialchars($set['reps']) ?></p>
                                                <?php if (!empty($set['description'])) : ?>
                                                    <p class="card-text mb-1">
                                                        <strong>Description:</strong> <?= htmlspecialchars($set['description']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($index < count($exercise['sets']) - 1) : ?>
                                                <hr class="set-divider my-2">
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-center">No exercises available</p>
            <?php endif; ?>
        </div>
    </div>
    <input type="hidden" value="<?= htmlspecialchars($graphData[0]) ?? "" ?>" id="graphDate">
    <input type="hidden" value="<?= htmlspecialchars($graphData[1]) ?? 0 ?>" id="graphWeight">
    <script>
        // Sample data for the graph
        // Sample data for the graph
        const dates = document.getElementById('graphDate').value.split(",");
        const weights = document.getElementById('graphWeight').value.split(",").map(Number);

        // Function to calculate percentage difference
        function calculatePercentageDifference(weights) {
            if (weights.length < 2) return {percentage: 0, direction: "neutral"};

            const latestWeight = weights[weights.length - 1];
            const previousWeight = weights[weights.length - 2];
            const difference = latestWeight - previousWeight;
            const percentage = ((difference / previousWeight) * 100).toFixed(2);

            return {
                percentage: Math.abs(percentage),
                direction: difference > 0 ? "up" : difference < 0 ? "down" : "neutral",
                difference: difference
            };
        }

        // Update Stats Box
        function updateStatsBox() {
            const {percentage, direction, difference} = calculatePercentageDifference(weights);
            const percentageElement = document.querySelector('.stats-container .percentage');
            const arrowElement = document.querySelector('.stats-container .arrow');
            const diff = document.querySelector('.stats-container .text');

            diff.textContent = `${difference ?? 0}kg`
            percentageElement.textContent = `${percentage}%`;
            arrowElement.innerHTML = direction === "up" ? "&#8593;" : direction === "down" ? "&#8595;" : "&#8594;";
            arrowElement.className = `arrow ${direction}`;
        }

        // Initialize Chart.js
        const ctx = document.getElementById('exerciseChart').getContext('2d');
        const exerciseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Weight Over Time',
                    data: weights,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Weight (kg)'
                        }
                    }
                }
            }
        });

        // Update the stats box initially
        updateStatsBox();

        // Search Filter for Exercises
        document.getElementById('searchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const exerciseCards = document.querySelectorAll('.exercise-card');

            exerciseCards.forEach(card => {
                const date = card.getAttribute('data-date').toLowerCase();
                if (date.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
    </body>
    </html>
<?php } else header('Location: /./Tracker');

