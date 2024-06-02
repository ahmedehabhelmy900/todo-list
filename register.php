<?php
if (isset($_COOKIE["username"])) {
  include "database.php";
  $checkAccount = $db->query("SELECT * FROM users WHERE username = '" . $_COOKIE["username"] . "' && password = '" . $_COOKIE["password"] . "'");
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
  <title>To Do List | Register page</title>
  <link rel="stylesheet" href="./css/login&register.css">
</head>

<body>
  <div class="register">
    <div>
      <form method="post">
        <input type="text" name="username" placeholder="username" required />
        <input type="password" name="passwordReg" placeholder="password" required />
        <input type="submit" value="Register" name="register">
      </form>
      <?php
      if (isset($_POST["register"])) {
        if (strlen($_POST["username"]) > 50) {
          echo "<h4 class='error'>username is so tall</h4>";
        } else {
          include "database.php";
          $username = $_POST["username"];
          $password = hash("sha256", $_POST["passwordReg"]);
          $checkIfExist = $db->query("SELECT * FROM users WHERE username = '$username'");
          $resultOfCheck = $checkIfExist->fetchAll(PDO::FETCH_ASSOC);
          if (empty($resultOfCheck)) {
            $db->exec("INSERT INTO users (username, password) VALUES ('$username', '$password')");
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
              echo "<h4 class='error'>something happen</h4>";
            }
          } else {
            echo "<h4 class='error'>the username name is exist. try another username</h4>";
          }
        }
      }
      ?>
      <a href="/">Have an account?</a>
    </div>
  </div>
</body>

</html>