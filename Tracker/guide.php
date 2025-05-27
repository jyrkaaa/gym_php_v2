<?php
if (true) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Workout Guide</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            .tab-buttons button {
                width: 100%;
                text-align: left;
                padding: 10px;
                border: none;
                background: #f8f9fa;
                cursor: pointer;
                transition: background 0.3s;
            }

            .tab-buttons button:hover, .tab-buttons button.active {
                background: #e9ecef;
            }

            .btn-outline-light {
                margin-left: 10px;
            }

            a.h3 {
                text-decoration: none;
                color: inherit;
                transition: color 0.3s ease, text-decoration 0.3s ease;
            }

            a.h3:hover {
                color: blue;
                text-decoration: underline;
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

    <div class="container my-4">
        <div class="row">
            <div class="col-md-3 tab-buttons">
                <button class="active" onclick="showTab('intro')">The Basics</button>
                <button onclick="showTab('addExercise')">How to Add a new Exercise</button>
                <button onclick="showTab('startWorkout')">How to Start a Workout</button>
                <button onclick="showTab('addExerciseToWorkout')">How to Add an Exercise to Workout</button>
                <button onclick="showTab('stats')">The Statistics page</button>
                <button onclick="showTab('bodyW')">How to insert and check your weight progress.</button>
            </div>
            <div class="col-md-9">
                <div id="intro" class="tab-content active">
                    <h3>How to Use the Website</h3>
                    <p>This page is made to track workouts and analyse past performances. This guide should answer any
                        lingering questions.</p>
                    <br>
                    <p>This is the main page. Here are the Options of "Exercises", "Workouts" and "Statistics".</p>
                    <img src="./../files/intro1.png" class="img-fluid" alt="Create Workout">
                    <ul>
                        <li>"Exercises" tab is for browsing exercises by category or name</li>
                        <li>"Workouts" tab is for tracking and searching for workouts.</li>
                        <li>"Statistics" tab is for seeing the information that is collected, for example body weight
                            over time or exercise progression.
                        </li>
                    </ul>
                    <br><br>
                    <h3>Exercises</h3>
                    <img src="./../files/intro2.png" class="img-fluid" alt="Create Workout">
                    <br><br>
                    <p>Here you can search for exercises by name, for example "bench", or by category, like "legs", or
                        "push".</p>
                    <br>
                    <p>This is useful if you are training a specific type of body part but are running out of ideas of
                        what to do.</p>
                    <br>
                    <a class="h3" onclick="showTab('addExercise')">How to Add a new Exercise</a>
                    <p>The page is as good as you make it, some exercises are already inserted by other users, but there
                        are thousands of ways to execute exercises. If anything's missing, you can just add it.</p>

                </div>
                <div id="addExercise" class="tab-content">
                    <h3>How to Add an Exercise to Workout</h3>
                    <p>When on the "Exercises" tab, do the following:</p>
                    <p>Click on 'Add Exercise'. The Green one.</p>
                    <img src="./../files/intro2.png" class="img-fluid" alt="Add Exercise">
                    <br><br><br>
                    <p>Add the necessary information, keep in mind that at times the list is cleansed of not-exercise posts..</p>
                    <img src="./../files/ex1.png" class="img-fluid" alt="Add Exercise">
                    <br>
                    <p>If everything works then the exercise should be in the list.</p>
                </div>

                <div id="addExerciseToWorkout" class="tab-content">
                    <h3>How to Add an Exercise to a Workout</h3>
                    <p>You have started a workout and want to fill your workout with the exercises you commited? That easy, just search for them and add them.</p>
                </div>

                <div id="startWorkout" class="tab-content">
                    <h3>Here is most of the information on Workouts.</h3>
                    <br>
                    <h4>How to Start a Workout:</h4>
                    <p>By choosing the "Create Workout" button on "Workouts", you get this: </p>
                    <img src="./../files/wo1.png" class="img-fluid" alt="Add Exercise">
                    <br>
                    <p>Here you fill in the data, by choosing the calendar icon on "Workout Date", you can choose the date from the calendar.</p>
                    <br>
                    <img src="./../files/wo2.png" class="img-fluid" alt="Add Exercise">
                    <br>
                    <br>
                    <p>If you already know what exercises you want to be in the workout, you can pick them from the section down below. Check the ones you want and they are automatically included when the workout is created.</p>
                </div>
                <div id="bodyW" class="tab-content">
                    <h3>How to Insert and Check your Weight Progress. </h3>
                    <p>The Page Includes the function to track your body weight, as accurately as you insert it. The more often an weight is inserted, the better the data analysis is. The graph does not take into account the time between inserted weights, so the graphs line is not visually 100% representative of your progress, but the data pointed to each entry is correct.</p>
                    <h4>Where to Add weight Entries</h4>
                    <p>On the Main Page You Can See a button "Add Weight"</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');

            document.querySelectorAll('.tab-buttons button').forEach(button => {
                button.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else {
    header('Location: /./Tracker');
    exit;
}
?>

