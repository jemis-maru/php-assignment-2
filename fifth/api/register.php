<?php
session_start();

try {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username && $password) {
      $_SESSION['registered_user_name'] = $username;
      $_SESSION['registered_password'] = $password;

      http_response_code(200);

      echo json_encode(
        array(
          "message" => "Registered successfully",
          "status" => 200
        )
      );
    } else {
      throw new Exception("Username and password required");
    }
	} else {
		http_response_code(400);
		throw new Exception("Invalid request!");
	}
} catch (Exception $e) {
	http_response_code(500);
	echo "Error: " . $e->getMessage();
}
