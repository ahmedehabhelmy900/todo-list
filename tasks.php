<?php

session_start();
if (!$_SESSION["id"]) {
  header("Location:/");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>To Do List | Tasks page</title>
  <link rel="stylesheet" href="./css/tasks.css">
</head>

<body>
  <div class="tasksPage">
    <div class="tasks_add">
      <form method="get" class="addTask">
        <input type="text" placeholder="New Task" name="task" required>
        <input type="submit" value="Add" name="newTask">
      </form>
      <?php
      // bring data from db
      include "database.php";
      $fetchTasks = $db->query("SELECT * FROM tasks WHERE user_id = " . $_SESSION["id"] . " ORDER BY done_box")->fetchAll(PDO::FETCH_ASSOC);
      echo "<div class='tasks'>";
      foreach ($fetchTasks as $task) {
        echo "<div id='" . $task["id"] . "' class='task'>
        <div class='editDate'>
        <form method='get' class='editTask'>
          <input name='nameEdited' type='text' value='" . $task["name_of_task"] . "' required/>
          <input type='number' value='" . $task["id"] . "' hidden name='editId'/>
          <input type='submit' value='Save' name='editTask' hidden/>
        </form>
        <h5>" . date("Y-m-j", strtotime($task["date_modify"])) . "</h5>
        </div>
        <form method='get' class='check_box'>
          <input type='checkbox' " . ($task["done_box"] == 0 ? "" : "checked") . " name='checkBox'/>
          <input type='number' name='checkId' value='" . $task["id"] . "' hidden/>
          <input type='submit' name='check' hidden/>
        </form>
        <form method='get' class='deleteForm'>
          <input type='number' name='deleteId' value='" . $task["id"] . "' hidden/>
          <input type='submit' name='delete' value='delete'/>
        </form>
        </div>";
      }
      echo "</div>";
      // check acces to id
      function checkId($db, $task_id, $user_id, $username, $password)
      {
        $res = $db->query("SELECT * FROM tasks INNER JOIN users ON tasks.user_id = users.id WHERE tasks.id = $task_id")->fetchAll(PDO::FETCH_ASSOC);
        if ($res[0]["user_id"] == $user_id && $res[0]["username"] == $username && $res[0]["password"] == $password) {
          return true;
        } else {
          header("Location: tasks.php");
          exit();
        }
      }
      // check box
      if (isset($_GET["check"])) {
        if (checkId($db, $_GET["checkId"], $_SESSION["id"], $_SESSION["username"], $_SESSION["password"])) {
          if (isset($_GET["checkBox"])) {
            $db->exec("UPDATE tasks SET done_box = '1' WHERE id = " . $_GET["checkId"] . "");
          } else {
            $db->exec("UPDATE tasks SET done_box = '0' WHERE id = " . $_GET["checkId"] . "");
          }
          header("Location: tasks.php");
          exit();
        }
      }
      // edit task
      if (isset($_GET["editTask"])) {
        if (checkId($db, $_GET["editId"], $_SESSION["id"], $_SESSION["username"], $_SESSION["password"])) {
          $db->exec("UPDATE tasks SET name_of_task = '" . $_GET["nameEdited"] . "' WHERE id = " . $_GET["editId"] . "");
          header("Location: tasks.php");
          exit();
        }
      }
      // delete task
      if (isset($_GET["delete"])) {
        if (checkId($db, $_GET["deleteId"], $_SESSION["id"], $_SESSION["username"], $_SESSION["password"])) {
          $db->exec("DELETE FROM tasks WHERE id = " . $_GET["deleteId"] . "");
          header("Location: tasks.php");
          exit();
        }
      }
      // add new task to database
      if (isset($_GET["newTask"])) {
        $task = $_GET["task"];
        $db->exec("INSERT INTO tasks (user_id, name_of_task) VALUES ('" . $_SESSION["id"] . "', '$task')");
        header("Location: tasks.php");
        exit();
      }
      // logout
      if (isset($_GET["logout"])) {
        session_unset();
        setcookie("id", "", time() - 10);
        setcookie("username", "", time() - 10);
        setcookie("password", "", time() - 10);
        header("Location: /");
        exit();
      }
      echo "</div>";
      ?>
      <form method="get" class="logout">
        <input type="submit" value="logout" name="logout" />
      </form>
    </div>
    <script src="main.js"></script>
</body>

</html>