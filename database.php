<?php

try {
  $db = new PDO("mysql:host=localhost;dbname=to-do-list", "root", "");
} catch (PDOException $e) {
  $errorLog = fopen("error.log", "a");
  fwrite($errorLog, "\n$e\n#########################");
  fclose($errorLog);
  header("Location:DBerror.php");
  exit();
}
