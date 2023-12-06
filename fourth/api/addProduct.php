<?php

session_start();

function isAuthenticated() {
	$headers = getallheaders();
	if (isset($_SESSION['token']) && isset($headers['Authorization'])) {
		return ($_SESSION['token'] === $headers['Authorization']);
	}
	return false;
}

try {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!isAuthenticated()) {
			throw new Exception("Unauthorized access");
		}

		$productName = $_POST['productName'];
		$productPrice = $_POST['productPrice'];
		$productImage = $_FILES['productImage'];
		$productDescription = $_POST['productDescription'];
		$productStatus = $_POST['productStatus'];

		$productData = array(
			'productName' => $productName,
			'productPrice' => $productPrice,
			'productImage' => time() . '-' . $productImage['name'],
			'productDescription' => $productDescription,
			'productStatus' => $productStatus
		);

		$uploadDirectory = '../uploads/';
		if (!file_exists($uploadDirectory)) {
			mkdir($uploadDirectory, 0777, true);
		}

		move_uploaded_file($productImage['tmp_name'], $uploadDirectory . $productData['productImage']);

		$dataFolder = '../data';
		if (!file_exists($dataFolder)) {
			mkdir($dataFolder, 0777, true);
		}
		$filename = $dataFolder . '/products.json';

		if (!file_exists($filename)) {
			$_write = fopen($filename, 'w');
			fwrite($_write, '[]');
			fclose($_write);
		}

		$_read = fopen($filename, 'r');
		$existingData = json_decode(fread($_read, filesize($filename)), true);
		fclose($_read);

		$existingData[] = $productData;

		$_write = fopen($filename, 'w');
		fwrite($_write, json_encode($existingData, JSON_PRETTY_PRINT));
		fclose($_write);
		http_response_code(201);
		echo json_encode(
			array(
				"message" => "Product added successfully",
				"status" => 201,
				"productData" => $productData
			)
		);
	} else {
		http_response_code(400);
		throw new Exception("Invalid request!");
	}
} catch (Exception $e) {
	$statusCode = isAuthenticated() ? 500 : 401;
	http_response_code($statusCode);
	echo "Error: " . $e->getMessage();
}
