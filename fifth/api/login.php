<?php
session_start();

try {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_SESSION['registered_user_name']) && $username === $_SESSION['registered_user_name'] && $password === $_SESSION['registered_password']) {
      $_SESSION['user_name'] = $username;
      $token = generateToken();

      http_response_code(200);

      echo json_encode(
        array(
          "message" => "Login successfully",
          "status" => 200,
          "token" => $token
        )
      );
    } else {
      throw new Exception("Invalid username or password.");
    }
	} else {
		http_response_code(400);
		throw new Exception("Invalid request!");
	}
} catch (Exception $e) {
	http_response_code(500);
	echo "Error: " . $e->getMessage();
}

function generateToken() {
  $token = bin2hex(random_bytes(16));
  $_SESSION['token'] = $token;
  return $token;
}


