<?php
require_once __DIR__ . '/../db_config.php';
const hostname = DB_HOST;
//localhost
const username = DB_USER;
const password = DB_PASS;
const dbname = DB_NAME;
function connectDB()
{
    // Establish Connection
    $conn = new mysqli(hostname, username, password, dbname);

// Check connection
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
    return $conn;

}

function insertUser(string $name, string $username, string $password): bool
{
    try {
        $conn = connectDB();
        $stmt = $conn->prepare("INSERT INTO USER (NAME, USERNAME, PASSWORD) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $password);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        return 1;
    } catch (Exception $e) {
        echo "Error adding user: " . $e->getMessage();
        return 0;
    }
}

function controlUser(string $username, string $password): array|int
{
    if (!isset($username) or !isset($password)) {
        return -1;
    }
    try {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT ID, NAME, USERNAME, PASSWORD FROM USER WHERE USERNAME = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $name, $found_username, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            return [$id, $found_username, $name];
        }
        return -1;
    } catch (Exception $e) {
        echo "Error finding user: " . $e->getMessage();
    }
    return -1;
}

function give_exercises_by_cat(): array|bool
{
    try {
        $db = connectDB();
        $sql = "SELECT 
                    e.ID AS exercise_id,
                    e.NAME AS exercise_name,
                    e.DISC AS exercise_description,
                    c.NIMETUS AS category_name
                FROM 
                    EXERCISE e
                INNER JOIN 
                    SPLIT_KATEG c ON e.SPLIT_KATEG_ID = c.ID
                ORDER BY 
                    c.ID, e.NAME;"; // Sort by category name and exercise name
        $result = $db->query($sql);
        $exercises = [];
        while ($row = $result->fetch_assoc()) {
            $exercises[$row['category_name']][] = $row; // Group by category
        }
        if (count($exercises) < 1) {
            return false;
        }
        return $exercises; // Return associative array grouped by category
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getCats(): array|int
{
    $db = connectDB();
    $sql = "SELECT NIMETUS, ID FROM SPLIT_KATEG";
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addExercise($name, $disc, $cat): bool
{
    try {
        $db = connectDB();
        $sql = 'INSERT INTO EXERCISE (NAME, DISC, SPLIT_KATEG_ID) VALUES (?, ?, ?)';
        $stmt = $db->prepare($sql);

        $stmt->bind_param("ssi", $name, $disc, $cat);
        if ($stmt->execute()) {
            return true; // Redirect back to the exercises page
        } else {
            echo "Error: " . $stmt->error;
            return false;
        }
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteExercise(int $id): bool
{
    try {
        $db = connectDB();
        $sql = "DELETE FROM EXERCISE WHERE ID = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        ($stmt->execute());
        if ($stmt->affected_rows > 0) {
            return true;
        } else return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getWorkoutById(int $workoutId): array|bool
{
    try {
        // Connect to the database
        $conn = connectDB();

        // SQL query to fetch workout and related exercises for a specific workout ID
        $sql = "
            SELECT
                u.ID AS user_id,
                w.ID AS workout_id, 
                w.NAME AS workout_name, 
                w.DATE AS workout_date, 
                eiw.ID AS exer_in_workout_id,
                eiw.EXERCISE_ID AS exercise_id,
                e.NAME AS exercise_name,
                r.REPS AS reps, 
                r.WHEIGHT AS rep_weight,
                r.ID as rep_id
            FROM 
                WORKOUT w
            LEFT JOIN 
                EXER_IN_WORKOUT eiw ON w.ID = eiw.WORKOUT_ID
            LEFT JOIN 
                REP_IN_EXERC r ON eiw.ID = r.EXER_IN_WORKOUT_ID
            LEFT JOIN 
                EXERCISE e ON eiw.EXERCISE_ID = e.ID
            LEFT JOIN 
                USER u ON u.ID = w.USER_ID
            WHERE 
                w.ID = ?
            ORDER BY 
                eiw.ID DESC;
        ";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $workoutId);
        $stmt->execute();

        // Bind the result to variables
        $stmt->bind_result(
            $user_id, $workout_id, $workout_name, $workout_date,
            $exer_in_workout_id, $exercise_id, $exercise_name,
            $reps, $rep_weight, $rep_id,
        );

        // Initialize the workout data structure
        $workouts = [
            'workout_details' => [],
            'exercises' => []
        ];

        // Temporary array to group reps under the same exercise
        $exercises = [];

        while ($stmt->fetch()) {
            // Set workout details only once
            if (empty($workouts['workout_details'])) {
                $workouts['workout_details'] = [
                    'workout_id' => $workout_id,
                    'workout_name' => $workout_name,
                    'workout_date' => $workout_date,
                    'user_id' => $user_id,
                ];
            }
            // Skip invalid exercise data
            if ($exer_in_workout_id === null || $exercise_id === null || $exercise_name === null) {
                continue;
            }

            // Group reps under their respective exercises
            if (!isset($exercises[$exer_in_workout_id])) {
                $exercises[$exer_in_workout_id] = [
                    'exer_in_workout_id' => $exer_in_workout_id,
                    'exercise_id' => $exercise_id,
                    'exercise_name' => $exercise_name,
                    'sets' => []  // Initialize an empty reps array
                ];
            }

            // Add the rep details if they exist
            if ($reps !== null && $rep_weight !== null) {
                $exercises[$exer_in_workout_id]['sets'][] = [
                    'reps' => $reps,
                    'weight' => $rep_weight,
                    'rep_id' => $rep_id
                ];
            }
        }


        // Combine exercises into the workout structure
        $workouts['exercises'] = !empty($exercises) ? array_values($exercises) : [];

        // Check if there is any valid workout data
        if (empty($workouts['workout_details'])) {
            return false;  // No workout found for the provided ID
        }

        return $workouts;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;  // Return false on error
    }
}


function addExerciseToWorkout($workoutId, $exerciseId): bool
{
    try {
        // Connect to the database
        $conn = connectDB();

        // SQL query to add an exercise to a workout
        $sql = "INSERT INTO EXER_IN_WORKOUT (WORKOUT_ID, EXERCISE_ID) VALUES (?, ?)";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $workoutId, $exerciseId);  // 'ii' for two integers
        if ($stmt->execute()) {
            return true; // Success
        } else {
            echo "Error: " . $stmt->error;
            return false; // Error occurred
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getAllWorkoutsByUser($userId): array|bool
{
    try {
        // Connect to the database
        $conn = connectDB();

        // SQL query to fetch workouts for the user
        $sql = "
            SELECT 
                ID AS workout_id, 
                NAME AS workout_name, 
                DATE AS workout_date
            FROM 
                WORKOUT
            WHERE 
                USER_ID = ?
            ORDER BY 
                DATE DESC;
        ";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);  // 'i' for integer
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch all rows into an array
        $workouts = $result->fetch_all(MYSQLI_ASSOC);

        if (count($workouts) < 1) {
            return false; // No workouts found
        }

        return $workouts; // Return array of workouts

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false; // Return false on error
    }
}

function makeFirstWorkout($name, $day, $id): array|bool
{
    try {
        $db = connectDB();
        $sql = "INSERT INTO WORKOUT (NAME, DATE, USER_ID) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $name, $day, $id);
        if ($stmt->execute()) {
            return [$db->insert_id, $day]; // Success
        } else {
            echo "Error: " . $stmt->error;
            return false; // Error occurred
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function allExercisesOptions(): array|bool
{
    try {
        $db = connectDB();
        $sql = "SELECT 
                ID AS exercise_id,
                NAME AS exercise_name
                FROM EXERCISE";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        if (count($exercises) < 1) {
            return false;
        }
        return $exercises;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }

}


function addRep($exer_id, $weight, $reps): array|bool
{
    try {
        $db = connectDB();
        $sql = "INSERT INTO REP_IN_EXERC (WHEIGHT, REPS, EXER_IN_WORKOUT_ID) VALUES (?, ?, ?);";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("dii", $weight, $reps, $exer_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function findWorkoutByExerInWorkout($exer_in_workout_id): int|false
{
    try {
        $db = connectDB();
        $sql = "SELECT WORKOUT_ID FROM EXER_IN_WORKOUT WHERE ID = ?;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $exer_in_workout_id);

        $stmt->execute();
        $stmt->bind_result($workoutId);
        // Fetch the result
        if ($stmt->fetch()) {
            return $workoutId;  // Return the fetched WORKOUT_ID
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteRep($rep_id): bool
{
    try {
        $db = connectDB();
        $sql = "DELETE FROM REP_IN_EXERC WHERE ID = ?;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $rep_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteExFromWorkout($exer_id): bool
{
    try {
        $db = connectDB();
        $sql = "DELETE FROM EXER_IN_WORKOUT WHERE ID = ?;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $exer_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteWorkout($workout_id): bool
{
    try {
        $db = connectDB();
        $sql = "DELETE FROM WORKOUT WHERE ID = ?;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $workout_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function addWeight($user_id, $date, $weight, $disc): bool
{
    try {
        $db = connectDB();
        $sql = "INSERT INTO USER_WEIGHT (DATE, USER_ID, WEIGHT, DISC) VALUES(?, ?, ?, ?);";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sids", $date, $user_id, $weight, $disc);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getRepInfo($rep_id)
{
    try {
        $db = connectDB();
        $sql = "SELECT DESCRIPTION FROM REP_IN_EXERC WHERE ID = ?;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $rep_id);
        $stmt->execute();
        $stmt->bind_result($disc);
        if ($stmt->fetch()) {
            return $disc;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function changeRepInfo($rep_id, $disc): string|bool
{
    try {
        $db = connectDB();
        $sql = "UPDATE REP_IN_EXERC SET DESCRIPTION = ? WHERE ID = ?;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $disc, $rep_id);
        if ($stmt->execute()) {
            return $disc;
        }
        return false;
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getUserWeight($user_id): array|false|null
{
    try {
        $db = connectDB();
        $sql = "SELECT
                    WEIGHT as weight,
                    DATE as date
                FROM USER_WEIGHT WHERE USER_ID = ? ORDER BY DATE;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $stmt->bind_result($weight, $date);
        $rawWeights = [];
        $rawDates = [];

        while ($stmt->fetch()) {
            $rawDates[] = $date;
            $rawWeights[] = $weight;
        }

        if (empty($rawDates)) {
            return ['weights' => '', 'dates' => ''];
        }

        list($interpolatedDates, $interpolatedWeights) = interpolateMissingDays($rawDates, $rawWeights);

        return [
            'weights' => implode(",", $interpolatedWeights),
            'dates' => implode(",", $interpolatedDates)
        ];
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function interpolateMissingDays(array $dates, array $weights) : array
{
    $interpolatedDates = [];
    $interpolatedWeights = [];

    $dateObjects = array_map(fn($d) => new DateTime($d), $dates);
    $indexedWeights = array_combine(array_map(fn($d) => $d->format('Y-m-d'), $dateObjects), $weights);

    $start = $dateObjects[0];
    $end = end($dateObjects);
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start, $interval, (clone $end)->add($interval));

    $prevDate = null;
    $prevWeight = null;

    foreach ($period as $date) {
        $dateStr = $date->format('Y-m-d');
        if (isset($indexedWeights[$dateStr])) {
            $weight = $indexedWeights[$dateStr];
        } else {
            // Find next known weight
            $nextDate = null;
            $nextWeight = null;
            foreach ($indexedWeights as $d => $w) {
                if ($d > $dateStr) {
                    $nextDate = new DateTime($d);
                    $nextWeight = $w;
                    break;
                }
            }

            if ($prevDate && $nextDate) {
                $totalDays = $prevDate->diff($nextDate)->days;
                $currentDays = $prevDate->diff($date)->days;
                $weight = $prevWeight + ($nextWeight - $prevWeight) * ($currentDays / $totalDays);
            } else {
                $weight = $prevWeight ?? $nextWeight ?? 0;
            }
        }

        $interpolatedDates[] = $dateStr;
        $interpolatedWeights[] = round($weight, 1);

        if (isset($indexedWeights[$dateStr])) {
            $prevDate = clone $date;
            $prevWeight = $weight;
        }
    }

    return [$interpolatedDates, $interpolatedWeights];
}


function getBiggestGrowthExercise($user_id): array|false
{
    try {
        $db = connectDB();

        // Query to find the heaviest weight per exercise in the last 7 days
        $sqlLast7Days = "
            SELECT 
                e.ID AS exercise_id,
                e.NAME AS exercise_name,
                MAX(r.WHEIGHT) AS max_weight_last_7_days
            FROM WORKOUT w
            JOIN EXER_IN_WORKOUT eiw ON w.ID = eiw.WORKOUT_ID
            JOIN EXERCISE e ON eiw.EXERCISE_ID = e.ID
            JOIN REP_IN_EXERC r ON eiw.ID = r.EXER_IN_WORKOUT_ID
            WHERE w.USER_ID = ? AND w.DATE >= NOW() - INTERVAL 7 DAY
            GROUP BY e.ID, e.NAME
        ";

        // Query to find the heaviest weight per exercise before the last 7 days
        $sqlBefore7Days = "
            SELECT 
                e.ID AS exercise_id,
                MAX(r.WHEIGHT) AS max_weight_before_7_days,
                w.DATE AS date
            FROM WORKOUT w
            JOIN EXER_IN_WORKOUT eiw ON w.ID = eiw.WORKOUT_ID
            JOIN EXERCISE e ON eiw.EXERCISE_ID = e.ID
            JOIN REP_IN_EXERC r ON eiw.ID = r.EXER_IN_WORKOUT_ID
            WHERE w.USER_ID = ? AND w.DATE < NOW() - INTERVAL 7 DAY
            GROUP BY e.ID
        ";

        // Fetch the heaviest weights in the last 7 days
        $stmt = $db->prepare($sqlLast7Days);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $resultLast7Days = $stmt->get_result();

        $weightsLast7Days = [];
        while ($row = $resultLast7Days->fetch_assoc()) {
            $weightsLast7Days[$row['exercise_id']] = [
                'exercise_name' => $row['exercise_name'],
                'max_weight_last_7_days' => $row['max_weight_last_7_days']
            ];
        }

        // Fetch the heaviest weights before the last 7 days
        $stmt = $db->prepare($sqlBefore7Days);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $resultBefore7Days = $stmt->get_result();

        $weightsBefore7Days = [];
        while ($row = $resultBefore7Days->fetch_assoc()) {
            $weightsBefore7Days[$row['exercise_id']] = [
                'weight' => $row['max_weight_before_7_days'],
                'date' => $row['date']];
        }

        // Determine the exercise with the biggest growth
        $biggestGrowthExercise = null;
        $maxGrowth = 0;
        // Skip exercises that were not performed (weightBefore == 0)
        foreach ($weightsLast7Days as $exercise_id => $data) {
            $weightBefore = $weightsBefore7Days[$exercise_id]['weight'] ?? 0;
            if ($weightBefore == 0) {
                continue;
            }
            $weightNow = $data['max_weight_last_7_days'];
            $growth = $weightNow - $weightBefore;

            if ($growth > $maxGrowth) {
                $maxGrowth = $growth;
                $biggestGrowthExercise = [
                    'exercise_name' => $data['exercise_name'],
                    'growth' => $growth,
                    'max_weight_last_7_days' => $weightNow,
                    'max_weight_before_7_days' => $weightBefore,
                    'date_when_last' => substr($weightsBefore7Days[$exercise_id]['date'], 0, -9)
                ];
            }
        }

        return $biggestGrowthExercise ?: false;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getExerciseDetails($user_id, $exercise_id) : array | bool
{
    try {
        // Connect to the database
        $db = connectDB();

        // SQL query to fetch workouts, exercises, and all associated reps
        $sql = "SELECT 
                w.NAME AS workout_name,
                w.DATE AS workout_date,
                eiw.ID AS exer_in_workout_id,
                r.REPS AS reps,
                r.WHEIGHT AS weight,
                r.DESCRIPTION AS description,
                e.NAME as exercise_name
            FROM 
                WORKOUT w
            JOIN 
                EXER_IN_WORKOUT eiw ON w.ID = eiw.WORKOUT_ID
            JOIN 
                REP_IN_EXERC r ON eiw.ID = r.EXER_IN_WORKOUT_ID
            JOIN
                EXERCISE e on eiw.EXERCISE_ID = e.ID
            WHERE 
                w.USER_ID = ? AND eiw.EXERCISE_ID = ?
            ORDER BY 
                w.DATE DESC ;
        ";

        // Prepare and execute the query
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $user_id, $exercise_id);
        $stmt->execute();

        // Bind the result to variables
        $stmt->bind_result(
            $workout_name, $workout_date,
            $exer_in_workout_id, $reps, $weight, $description, $exercise_name
        );

        // Initialize result structure
        $exercises = [];

        while ($stmt->fetch()) {
            // Check if the workout already exists in the result
            if (!isset($exercises[$exer_in_workout_id])) {
                $exercises[$exer_in_workout_id] = [
                    'exercise_name' => $exercise_name,
                    'workout_name' => $workout_name,
                    'date' => substr($workout_date, 0, -9),
                    'sets' => []
                ];
            }
            if ($reps !== null && $weight !== null) {
                $exercises[$exer_in_workout_id]['sets'][] = [
                    'reps' => $reps,
                    'weight' => $weight,
                    'description' => $description ?? 'No description'
                ];
            }
        }
        return $exercises;

    } catch
    (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;  // Return false on error
    }
}
function getNameFromList($list) : string | bool {
    if (isset($list)) {
        foreach ($list as $exercise) {
            return $exercise['exercise_name'];
        }
    } return false;
}
function extractGraphDataStats($list) : array | bool
{
    if (isset($list)) {
        $weights = [];
        $dates = [];
       foreach ($list as $exercise) {
           $dates[] = $exercise['date'];
           $max_weight = 0;
           foreach ($exercise['sets'] as $set) {
               if ($set['weight'] > $max_weight) {
                   $max_weight = $set['weight'];
               }
           }
           $weights[] = $max_weight;
       }
       $stringDates = "";
       $stringWeights = "";
        for ($i = count($dates) - 1; $i >= 0; $i--) {
            $stringDates .= $dates[$i] . ",";
            $stringWeights .= $weights[$i] . ",";
       }
       return [substr($stringDates, 0, -1), substr($stringWeights, 0, -1)];
    } return false;
}
function getBestRep($exer_in_workout_id, $user_id) : array | bool
{
    try {
        $db = connectDB();

        // First query to get the exercise name and ID
        $presql = "SELECT eiw.EXERCISE_ID, e.NAME  FROM EXER_IN_WORKOUT eiw
                    LEFT JOIN
                        EXERCISE e ON eiw.EXERCISE_ID = e.ID
                    WHERE eiw.ID = ?";
        $stmt = $db->prepare($presql);
        $stmt->bind_param("i", $exer_in_workout_id);
        $stmt->execute();
        $stmt->bind_result($exer_id, $exer_name);
        $stmt->fetch();
        $stmt->close();

        // Check if the exercise was found
        if (!$exer_name) {
            return false; // If no exercise is found, return false
        }
        // Second query to get the best rep for the given user and exercise
        $sql = "SELECT r.WHEIGHT, r.REPS
                FROM REP_IN_EXERC r
                JOIN EXER_IN_WORKOUT eiw ON r.EXER_IN_WORKOUT_ID = eiw.ID
                JOIN WORKOUT w ON eiw.WORKOUT_ID = w.ID
                WHERE eiw.EXERCISE_ID = ?
                AND w.USER_ID = ?
                ORDER BY r.WHEIGHT DESC
                LIMIT 1;";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $exer_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($weight, $reps);
        $stmt->fetch();
        $stmt->close();

        // Check if a rep was found and return the results
        if ($reps !== null && $weight !== null) {
            return [$reps, $weight, $exer_name];
        } else {
            return [0, 0, $exer_name]; // Return false if no rep found
        }

    } catch (Exception $e) {
        // Proper exception handling
        echo "Error: " . $e->getMessage();
        return false;
    }
}
function checkboxArray() : array | bool
{
    try {
        $db = connectDB();
        $sql = "SELECT NAME, ID FROM EXERCISE;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($exercise_name, $exercise_id);
        $result = [];
        while ($stmt->fetch()) {
            $result[] = [
                'id' => $exercise_id,
                'name' => $exercise_name];
            }
            if (count($result) > 0) {
                return $result;
            }
            return false;
        } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}