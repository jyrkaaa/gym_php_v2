<?php

require_once 'methods.php';


session_start();

// Define the session timeout duration (in seconds)
$session_timeout = 10800; // 30 minutes

//--Check if the last activity timestamp is set
if (isset($_SESSION['last_activity'])) {
    $time_elapsed = time() - $_SESSION['last_activity'];
    if ($time_elapsed > $session_timeout) {
        // Session timed out
        session_unset();
        session_destroy();
        session_start();
        header('Location: /./Tracker');
    }
}
// Update the last activity timestamp
$_SESSION['last_activity'] = time();

//Handle User Registration
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['go_reg'])) {
    include "register.php";
}
//--Exercises
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['exercises_list'])) {
    $exercises = give_exercises_by_cat();
    include "exercises/index.php";
}
//--Current Workout
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['setActive'])) {
    if ($_SESSION['current_workout'] === $_GET['setActive']) {
        $_SESSION['current_workout'] = NULL; // Resets
    } else {
        $_SESSION['current_workout'] = $_GET['setActive'];
    } $workouts = getAllWorkoutsByUser($_SESSION['user_id']);
    include "workouts/index.php";
}
//--Statistics
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['statistics'])) {
    $groupedExercises = give_exercises_by_cat();
    $user_id = $_SESSION['user_id'];
    $data = getUserWeight($user_id);
    $dates = $data['dates'];
    $weights = $data['weights'];
    $exerData = getBiggestGrowthExercise($user_id);
    $calenderInfo = getAllWorkoutsByUser($user_id);
    include "statistics/index.php";
}
//--Workouts
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['workouts_list'])) {
    $workouts = getAllWorkoutsByUser($_SESSION['user_id']);
    include "workouts/index.php";
}
//--Continue Workout
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['continueWorkout'])) {
    $user_id = $_SESSION['user_id'];
    if (isset($_SESSION['current_workout'])) {
        $workoutId = $_SESSION['current_workout'];
        $workoutData = getWorkoutById($workoutId);

        if ($workoutData) {
            $groupedExercises = give_exercises_by_cat();
            $workoutDetails = $workoutData['workout_details'];
            $exercises = $workoutData['exercises'];
            include "workouts/workoutMaker.php";

        } else include "main.php";
    } else {
        $workouts = getAllWorkoutsByUser($_SESSION['user_id']);
        include "workouts/index.php";
    }
}
//--Start workout
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startWorkout'])) {
    $workoutName = $_POST['workout_name'];
    $workoutDate = $_POST['workout_date'];
    $user_id = $_SESSION['user_id'];
    $exercises_chosen = $_POST['exercises'] ?? [];
    $workout = makeFirstWorkout($workoutName, $workoutDate, $user_id);
    if ($workout) { // success
        $workoutId = $workout[0];
        foreach ($exercises_chosen as $exercise) {
            addExerciseToWorkout($workoutId, $exercise);
        }
        $workoutData = getWorkoutById($workoutId);
        if ($workoutData) {
            $groupedExercises = give_exercises_by_cat();
            $workoutDetails = $workoutData['workout_details'];
            $exercises = $workoutData['exercises'];
            include "workouts/workoutMaker.php";

        } else include "main.php";

    } else {
        echo "Error making workout";
        include "main.php";
    }
}
//--Exercise stats
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['exerciseStats'])) {
    $exer_id = $_GET['exercise_id'];
    $user_id = $_SESSION['user_id'];
    $exercises = getExerciseDetails($user_id, $exer_id);
    $exer_name = getNameFromList($exercises);
    $graphData = extractGraphDataStats($exercises);
    include "statistics/exerciseStats.php";
}
//--Rep Editor
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRep'])) {
    $exer_in_workout = $_POST['addRep'];
    $personalBest = getBestRep($exer_in_workout, $_SESSION['user_id']) ?? [0, 0, 'Exercise'];
    include 'workouts/makeRep.php';
} //--Delete Rep
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delRep'])) {
    $rep_id = $_POST['delRep'];
    $workoutId = $_POST['workout_id'];
    if (!deleteRep($rep_id)) {
        echo 'Error deleting rep.';
    }
    $workoutData = getWorkoutById($workoutId);

    if ($workoutData) {
        $groupedExercises = give_exercises_by_cat();
        $workoutDetails = $workoutData['workout_details'];
        $exercises = $workoutData['exercises'];
        include "workouts/workoutMaker.php";
    } else {
        echo 'Error finding data.';
        include "main.php";
    }
}
//--Rep maker
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['makeRep'])) {
    $exer_id = $_POST['exer_in_workout'];
    $weight = $_POST['weight'];
    $reps = $_POST['reps'];
    $workoutId = findWorkoutByExerInWorkout($exer_id);
    if (!addRep($exer_id, $weight, $reps)) {
        echo 'Error adding rep';
    }
    $workoutData = getWorkoutById($workoutId);

    if ($workoutData) {
        $groupedExercises = give_exercises_by_cat();
        $workoutDetails = $workoutData['workout_details'];
        $exercises = $workoutData['exercises'];
        include "workouts/workoutMaker.php";
    } else {
        echo 'Error finding data.';
        include "main.php";
    }
} //--Rep Duplicate
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['copyRep'])) {
    $data = $_POST['copyRep']; // 1: id, 2: reps, 3: weight
    $data = preg_split('/,/', $data);
    $exer_id = $data[0];
    $weight = $data[2];
    $reps = $data[1];
    $workoutId = findWorkoutByExerInWorkout($exer_id);
    if (!addRep($exer_id, $weight, $reps)) {
        echo 'Error duplicating rep';
    }
    $workoutData = getWorkoutById($workoutId);

    if ($workoutData) {
        $groupedExercises = give_exercises_by_cat();
        $workoutDetails = $workoutData['workout_details'];
        $exercises = $workoutData['exercises'];
        include "workouts/workoutMaker.php";
    } else {
        echo 'Error finding data.';
        include "main.php";
    }
} //--Edit Workout
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['editWorkout'])) {
    $workoutId = $_GET['editWorkout'];
    $workoutData = getWorkoutById($workoutId);
    $user_id = $_SESSION['user_id'];

    if ($workoutData) {
        $workoutDetails = $workoutData['workout_details'];
        if ($workoutDetails['user_id'] !== $user_id) {
            include "main.php";
        } else {
            $groupedExercises = give_exercises_by_cat();
            $exercises = $workoutData['exercises'];
            include "workouts/workoutMaker.php";
        }
    } else include "main.php";

} //--Delete Workout
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['deleteWorkout'])) {
    $workoutId = $_GET['deleteWorkout'];
    deleteWorkout($workoutId);
    $workouts = getAllWorkoutsByUser($_SESSION['user_id']);
    include "workouts/index.php";
} //--Edit/make Workout
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['makeWorkout'])) {
    $id = $_SESSION['user_id'];
    $exercises = checkboxArray();
    include 'workouts/startAWorkout.php';

} //--Add an exercise to a workout
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_exercise_to_workout'])) {
    $workout_id = $_POST['workout_id'];
    $exercise_id = $_POST['exercise_id'];
    $result = addExerciseToWorkout($workout_id, $exercise_id);
    if (!$result) {
        echo $exercise_id . "  :" . $exercise_id;
    }
    $id = $_SESSION['user_id'];
    $day = date('Y-m-d');
    $workoutData = getWorkoutById($workout_id);
    if ($workoutData) {
        $groupedExercises = give_exercises_by_cat();  //works
        $workoutId = $workoutData['workout_details']['workout_id'];
        $workoutDetails = $workoutData['workout_details'];
        $exercises = $workoutData['exercises'];
    }
    include "workouts/workoutMaker.php";

} //--Delete Exercise from workout
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delExFromWorkout'])) {
    $exer_id = $_POST['delExFromWorkout'];
    $workout_id = $_POST['workout_id'];
    if (!deleteExFromWorkout($exer_id)) {
        echo $exer_id . "  :" . $workout_id;
    }
    $workoutData = getWorkoutById($workout_id);
    if ($workoutData) {
        $groupedExercises = give_exercises_by_cat();
        $workoutId = $workoutData['workout_details']['workout_id'];
        $workoutDetails = $workoutData['workout_details'];
        $exercises = $workoutData['exercises'];
        include "workouts/workoutMaker.php";

    } else include "main.php";
} //--LOGOUT
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    session_start();
    include "login.php";

} //--USER REGISTERING
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $auth_key = $_POST['authKey'];
    $auth_pk = "master312";
    if ($auth_pk !== $auth_key) {
        echo 'Not authorized';
        include 'register.php';
    } else {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $error = insertUser($name, $username, $password);
        if ($error == 0) {
            include 'register.php';
        } else {
            include 'login.php';
        }
    }

} //--Making an exercise
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['make_exercise'])) {
    $categories = getCats();
    include 'exercises/add_exercise.php';

//--Deleteing exercise
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['del_ex'])) {
    if ($_SESSION['user_id'] !== 11) {
        echo 'Not authorized';
        $exercises = give_exercises_by_cat();
        include 'index.php';
    } else {
        $id = $_POST['del_ex'];
        $error = deleteExercise($id);
        $exercises = give_exercises_by_cat();
        include 'exercises/index.php';
    }
//--Add exercise
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_exercise'])) {
    $categ_id = $_POST['category_id'];
    $exercise = $_POST['name'];
    $description = $_POST['description'] ?? null;

    $success = addExercise($exercise, $description, $categ_id);
    if (!$success) {
        include 'exercises/add_exercise.php';
    } else {
        $exercises = give_exercises_by_cat();
        include 'exercises/index.php';
    }
} //--See Info Abt Rep
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repDisc'])) {
    $exer_id = $_POST['repDisc'];
    $user_id = $_SESSION['user_id'];
    $exercises = getExerciseDetails($user_id, $exer_id);
    $exer_name = getNameFromList($exercises);
    $graphData = extractGraphDataStats($exercises);
    include "statistics/exerciseStats.php";

} //--See Info Abt Rep
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editRepDisc'])) {
    $rep_id = $_POST['rep_id'];
    $disc = $_POST['newText'];
    $displayText = "Error Comitting new description.";
    $newDisc = changeRepInfo($rep_id, $disc);
    if ($newDisc) {
        $displayText = $newDisc;
    }
    include 'workouts/repDisc.php';
} //--Adding Users Weight
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUserWeight'])) {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['time'];
    $weight = $_POST['weight'];
    $disc = $_POST['description'];
    if (addWeight($user_id, $date, $weight, $disc)) {
        include 'main.php';
    } else {
        $error = true;
        include 'addUserWeight.php';
    }
} //--USER LOGIN
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = controlUser($username, $password);
    if ($result !== -1) {
        $_SESSION['user_id'] = $result[0];
        $_SESSION['username'] = $result[1];
        $_SESSION['user'] = $result[2];
        $workouts = getAllWorkoutsByUser($result[0]);
        $todayDate = date("Y-m-d");
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        foreach ($workouts as $workout) {
            if (substr($workout['workout_date'], 0, 10) === $todayDate) {
                $_SESSION['current_workout'] = $workout['workout_id'];
                break;
            } if (substr($workout['workout_date'], 0, 10) === $yesterday) {
                $_SESSION['current_workout'] = $workout['workout_id'];
                break;
            }
        }

        include 'main.php';
    } else include 'login.php';
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['newUserWeight'])) {
    include 'addUserWeight.php';
}
//--Guide
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['guide'])) {
    include 'guide.php';
}
//--Redirect to main if logged in
else if (isset($_SESSION['user_id'])) {
    include "main.php";
} //--fail or default
else include 'login.php';


