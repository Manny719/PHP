<?php session_start(); ?>
<!DOCTYPE html>
<html>
<body>
<form method="post" action="login.php">
  Username: <input type="text" name="username"><br>
  Password: <input type="password" name="password"><br>
  <input type="submit" name="login" value="Login">
</form>

<?php
if (isset($_POST['login'])) {
    $conn = new mysqli("localhost", "root", "", "judgingDB");
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM judges WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['judge'] = $username;
        header("Location: score.php");
    } else {
        echo "Login failed.";
    }
}
?>
</body>
</html>