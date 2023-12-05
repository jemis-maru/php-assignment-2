<?php
session_start();

if (isset($_SESSION['user_name'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $username = $_GET['username'];
  $password = $_GET['password'];

  if($username && $password) {
    $_SESSION['registered_user_name'] = $username;
    $_SESSION['registered_password'] = $password;
    header("Location: login.php");
    exit();
  }
}
?>