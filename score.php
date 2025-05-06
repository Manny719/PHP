<?php
session_start();
if (!isset($_SESSION['judge'])) {
    header("Location: login.php");
    exit();
}

// on POST, grab rubric inputs, sum them, and write to DB
if (isset($_POST['submit_score'])) {
    $conn = new mysqli("localhost","root","","judgingDB");
    if ($conn->connect_error) {
      die("DB connect failed: ".$conn->connect_error);
    }

    // get's the four rubric scores
    $c1 = floatval($_POST['c1']);    // Articulate requirements
    $c2 = floatval($_POST['c2']);    // Choose correct tools
    $c3 = floatval($_POST['c3']);    // Oral presentation
    $c4 = floatval($_POST['c4']);    // Teamwork
    $total = $c1 + $c2 + $c3 + $c4;  // judge’s total

    // optional: grab comments
    $comments = $conn->real_escape_string($_POST['comments']);

    // look up judge’s numeric ID
    $username = $conn->real_escape_string($_SESSION['judge']);
    $res = $conn->query("SELECT id FROM judges WHERE username='$username'");
if (!$res || $res->num_rows === 0) {
die("Judge not found.");
    }
    $judge_id = $res->fetch_assoc()['id'];

    // store (or update) this judge’s total for that performance
    $perf_id = intval($_POST['performance_id']);
    $sql  = "REPLACE INTO scores
(judge_id, performance_id, score, comments)
VALUES
($judge_id, $perf_id, $total, '$comments')";
if ($conn->query($sql)) {
echo "<p><strong>Your total:</strong> $total<br>
Score saved successfully.</p>";
    } else {
echo "<p>Error saving score: ".$conn->error."</p>";
    }

$conn->close();
}
?>
<!DOCTYPE html>
<html>
<head><title>Submit Rubric Scores</title>
<style>
table { border-collapse: collapse; width: 80%; }
th, td { border: 1px solid #999; padding: .5em; text-align: center; }
th.criteria { text-align: left; }
</style>
</head>
<body>
<h3>Welcome, Judge <?php echo htmlentities($_SESSION['judge']); ?></h3>

<form method="post" action="score.php">
    <!-- associate this with a particular group/performance -->
    Performance ID:
<input type="number" name="performance_id" required><br><br>

<table>
<tr>
<th class="criteria">Criteria</th>
<th>Score (0–15)</th>
</tr>
<tr>
<td class="criteria">Articulate requirements</td>
<td><input type="number" name="c1" min="0" max="15" step="0.1" required></td>
</tr>
<tr>
<td class="criteria">Choose appropriate tools and methods</td>
<td><input type="number" name="c2" min="0" max="15" step="0.1" required></td>
</tr>
<tr>
<td class="criteria">Clear and coherent oral presentation</td>
<td><input type="number" name="c3" min="0" max="15" step="0.1" required></td>
</tr>
<tr>
<td class="criteria">Functioned well as a team</td>
<td><input type="number" name="c4" min="0" max="15" step="0.1" required></td>
</tr>
<tr>
<td class="criteria"><strong>Judge’s comments</strong></td>
<td>
<textarea name="comments" rows="3" cols="30"
placeholder="Optional feedback…"></textarea>
</td>
</tr>
<tr>
<td colspan="2">
<button type="submit" name="submit_score">Submit</button>
</td>
</tr>
</table>
</form>
</body>
</html>
