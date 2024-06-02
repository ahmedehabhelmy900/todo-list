<?php
if (isset($_COOKIE["username"])) {
  include "database.php";
  $checkAccount = $db->query("SELECT * FROM users WHERE id = '" . $_COOKIE["id"] . "' && username = '" . $_COOKIE["username"] . "' && password = '" . $_COOKIE["password"] . "'");
  $resultCheck = $checkAccount->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($resultCheck)) {
    session_start();
    $_SESSION["id"] = $_COOKIE["id"];
    $_SESSION["username"] = $_COOKIE["username"];
    $_SESSION["password"] = $_COOKIE["password"];
    header("Location:tasks.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>To Do List | Login page</title>
  <link rel="stylesheet" href="./css/login&register.css">
</head>

<body>
  <div class="login">
    <div>
      <form method="post">
        <input type="text" placeholder="username" name="username" required>
        <input type="password" placeholder="password" name="passwordLog" required>
        <input type="submit" value="login" name="login">
      </form>
      <?php
      if (isset($_POST["login"])) {
        include "database.php";
        $username = $_POST["username"];
        $password = hash("sha256", $_POST["passwordLog"]);
        $dbQueury = $db->query("SELECT * FROM users WHERE username = '$username' && password = '$password'");
        $result = $dbQueury->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          session_start();
          $_SESSION["id"] = $result[0]["id"];
          $_SESSION["username"] = $result[0]["username"];
          $_SESSION["password"] = $result[0]["password"];
          setcookie("id", $result[0]["id"]);
          setcookie("username", $result[0]["username"]);
          setcookie("password", $result[0]["password"]);
          header("Location:tasks.php");
          exit();
        } else {
          echo "<h4 class='error'>Wrong Password Or Email</h4>";
        }
      }
      ?>
      <a href="register.php">Don't have account?</a>
    </div>
  </div>
</body>

</html>