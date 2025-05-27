<?php
if ($_SESSION['user_id']) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Rep Description</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .container {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 20px;
                min-width: 300px;
                max-width: 1000px;
                margin-top: 2rem;
            }
            .editable-text {
                border: 1px solid #ccc;
                padding: 10px;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .editable-text:hover {
                background-color: #f9f9f9;
            }
            .edit-form {
                display: none;
            }
            .edit-input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                margin-bottom: 10px;
            }
            .button-container {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            }
            .submit-btn, .cancel-btn {
                padding: 5px 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .submit-btn {
                background-color: #4CAF50;
                color: white;
            }
            .cancel-btn {
                background-color: #f44336;
                color: white;
            }
            .submitted-text {
                margin-top: 20px;
                color: #4CAF50;
            }
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
    <div class="container">
        <h1>Editable Text Box Demo</h1>
        <div id="displayText" class="editable-text"
             onclick="showEditForm()"><?php echo htmlspecialchars($displayText); ?></div>
        <form id="editForm" class="edit-form" method="POST">
            <input type="hidden" value="<?= htmlspecialchars($rep_id) ?>" name="rep_id">
            <input type="text" id="newText" name="newText" class="edit-input"
                   value="<?php echo htmlspecialchars($displayText); ?>">
            <div class="button-container">
                <button type="button" class="cancel-btn" onclick="hideEditForm()">Cancel</button>
                <button name="editRepDisc" type="submit" class="submit-btn">Submit</button>
            </div>
        </form>
<!--        --><?php //if (isset($_SESSION['submittedText'])): ?>
<!--            <p class="submitted-text">Last submitted-->
<!--                text: --><?php //echo htmlspecialchars($_SESSION['submittedText']); ?><!--</p>-->
<!--        --><?php //endif; ?>
    </div>

    <script>
        function showEditForm() {
            document.getElementById('displayText').style.display = 'none';
            document.getElementById('editForm').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('displayText').style.display = 'block';
            document.getElementById('editForm').style.display = 'none';
        }
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else header('Location: /./../Tracker');