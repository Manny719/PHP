<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Connect to the database
$conn = new mysqli("localhost", "root", "", "judgingDB");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin – Average Scores</title>
</head>
<body>
  <h2>Performance Averages</h2>

  <?php
  // Once you’ve verified the debug output, delete or comment out the entire DEBUG BLOCK above,
  // then this section will run your original average‐query:

  $sql_avg    = "SELECT performance_id, AVG(score) AS avg_score
                 FROM scores
                 GROUP BY performance_id";
  $result_avg = $conn->query($sql_avg);
  if ($result_avg === false) {
      die("SQL error: " . $conn->error);
  }

  if ($result_avg->num_rows > 0) {
      while ($row = $result_avg->fetch_assoc()) {
          echo "Performance ID: "
             . htmlentities($row['performance_id'])
             . " | Average Score: "
             . round($row['avg_score'], 2)
             . "<br>";
      }
  } else {
      echo "No scores submitted yet.";
  }

  $conn->close();
  ?>
</body>
</html>
