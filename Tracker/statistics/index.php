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
        <!-- FullCalendar CSS -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">

        <!-- Include jQuery (required by FullCalendar) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!--Select Func-->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <!-- FullCalendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

        <link rel="apple-touch-icon" sizes="180x180" href="./../../files/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./../../files/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./../../files/favicon-16x16.png">
        <link rel="manifest" href="./../../files/site.webmanifest">
        <style>
            .container {
                margin-top: 30px;
            }

            .chart-container {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-bottom: 20px;
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

            .card {
                margin-top: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .arrow-up {
                font-size: 24px;
            }

            .percentage {
                font-size: 18px;
                font-weight: bold;
            }

            .btn-outline-light {
                margin-left: 10px;
            }

            .old-weight {
                text-decoration: line-through;
                color: #888;
                font-size: 18px;
            }

            .newWeightDisplay {
                font-size: 28px;
                font-weight: bold;
                color: #333;
            }

            .submitButton {
                margin: auto auto 10px;
            }

            .fc-event-time {
                display: none;
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

    <div class="container bg-white">
        <div class="card">
            <form class="flex-column gap-3 d-flex" method="get">
                <h3 class="card-header bg-dark text-white">See your exercises stats</h3>
                <select class="form-select" name="exercise_id" id="exercise_id" required>
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
                <button type="submit" name="exerciseStats" class="btn btn-dark col-md-4 submitButton">View Exercise
                    Stats
                </button>
            </form>
        </div>
        <h3 class="text-center">Body weight Progress Over Time</h3>
        <div class="justify-content-center align-items-start">
            <!-- Graph Section -->
            <div class="chart-container">
                <canvas id="chartCanvas" style="height: 300px; width: 100%;"></canvas>
            </div>
            <!-- Exercise Card Section -->
            <div class="stats-container">
                <h5>Best exercise the past 7 days</h5>
                <div class="card-body">
                    <h4 class="card-title text-center" id="exerciseNameDisplay"></h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted old-weight" id="oldWeightDisplay"></span>
                        <div class="text-end">
                        <span class="d-block">
                            <span class="arrow-up">&#x2191;</span>
                            <span class="newWeightDisplay" id="percentageDisplay"></span>
                        </span>
                            <span class="percentage" id="newWeightDisplay"></span>
                            <span class="d-block text-muted small" id="lastWeightDate"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FullCalendar Section -->
        <div class="calendar-container">
            <h3 class="text-center">Your Workouts</h3>
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Hidden inputs for exercise progress -->
    <input type="hidden" id="weightBefore"
           value="<?= htmlspecialchars($exerData["max_weight_before_7_days"]) ?? ""; ?>">
    <input type="hidden" id="weightAfter" value="<?= htmlspecialchars($exerData["max_weight_last_7_days"]) ?? 0; ?>">
    <input type="hidden" id="exerciseName" value="<?= htmlspecialchars($exerData["exercise_name"]) ?? 0; ?>">
    <input type="hidden" id="exerciseDate" value="<?= htmlspecialchars($exerData["date_when_last"]) ?? 0; ?>">

    <!-- Hidden inputs for graph data -->
    <input type="hidden" id="labelWeights" value="<?= htmlspecialchars($weights) ?? ""; ?>">
    <input type="hidden" id="labelDates"
           value="<?= htmlspecialchars($dates) ?? ""; ?>">
    <!-- Bootstrap JS and Popper.js (required for dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get the PHP workout data and pass it into the JS
            const calendarInfo = <?php echo json_encode($calenderInfo); ?>; // Get all workouts details

            // Prepare FullCalendar events using the workout date, name, and ID
            const events = calendarInfo.map(function (workout) {
                return {
                    title: workout.workout_name,  // Workout name as event title
                    start: workout.workout_date,  // Workout date as the start date
                    id: workout.workout_id,       // Workout ID for any future reference
                    color: '#4CAF50'              // Optional: style events as per your need
                };
            });

            // Initialize FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',  // Default view is month
                events: events,               // Set the event data
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                eventClick: function (info) {
                    // Create and submit the form dynamically
                    var form = $('<form method="GET"></form>');

                    // Add hidden input fields for workout ID
                    form.append('<input type="hidden" name="editWorkout" value="' + info.event.id + '" />');

                    // Append the form to the body and submit it
                    $('body').append(form);
                    form.submit();
                }
            });

            // Render the calendar
            calendar.render();
        });
        document.addEventListener("DOMContentLoaded", () => {
                const dates = document.getElementById('labelDates').value.split(',');
                const weights = document.getElementById('labelWeights').value.split(',').map(Number);

                function initChart() {
                    const ctx = document.getElementById("chartCanvas").getContext("2d");
                    return new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: dates,
                            datasets: [
                                {
                                    label: "Weight (kg)",
                                    data: weights,
                                    borderColor: "rgb(75, 192, 192)",
                                    tension: 0.1,
                                    fill: false
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    title: {
                                        display: true,
                                        text: 'Weight (kg)',
                                    },
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date',
                                    },
                                },
                            },
                        }
                    });
                }

                function updateCard() {
                    const exerciseName = document.getElementById('exerciseName').value;
                    const weightBefore = parseFloat(document.getElementById('weightBefore').value);
                    const weightAfter = parseFloat(document.getElementById('weightAfter').value);
                    const lastDate = document.getElementById('exerciseDate').value


                    document.getElementById('exerciseNameDisplay').textContent = exerciseName;
                    document.getElementById('oldWeightDisplay').textContent = `${weightBefore} kg`;
                    document.getElementById('newWeightDisplay').textContent = `${weightAfter} kg`;
                    document.getElementById('lastWeightDate').textContent = lastDate;
                    const percentageDifference = calculatePercentage(weightBefore, weightAfter);
                    const percentageDisplay = document.getElementById('percentageDisplay');
                    const arrowUp = document.querySelector('.arrow-up');

                    percentageDisplay.textContent = `${percentageDifference > 0 ? '' : ''}${percentageDifference}%`;
                    if (percentageDifference > 0) {
                        percentageDisplay.style.color = '#28a745'; // Green
                        arrowUp.style.color = '#28a745';
                        arrowUp.textContent = '↑';
                    } else {
                        percentageDisplay.style.color = '#dc3545'; // Red
                        arrowUp.style.color = '#dc3545';
                        arrowUp.textContent = '↓';
                    }
                }

                function calculatePercentage(oldWeight, newWeight) {
                    const difference = newWeight - oldWeight;
                    const percentage = (difference / oldWeight) * 100;
                    return percentage.toFixed(1);
                }

                initChart();
                updateCard();
            }
        )
    </script>
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
<?php } else header('Location: /./Tracker');