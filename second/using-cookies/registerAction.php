<?php
if (isset($_COOKIE['user_name'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $username = $_GET['username'];
  $password = $_GET['password'];

  if($username && $password) {
    setcookie('registered_user_name', $username, time() + (1000000), "/");
    setcookie('registered_password', $password, time() + (1000000), "/");
    header("Location: login.php");
    exit();
  }
}
?>