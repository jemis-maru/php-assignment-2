<?php

session_start();

require '../aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

function isAuthenticated()
{
	$headers = getallheaders();
	if (isset($_SESSION['token']) && isset($headers['Authorization'])) {
		return ($_SESSION['token'] === $headers['Authorization']);
	}
	return false;
}

// please add key and secret
$credentials = [
	'key' => 'add-key-here',
	'secret' => 'add-secret-here',
];

$s3 = new S3Client([
	'version' => 'latest',
	'region' => 'ap-south-1',
	'credentials' => $credentials,
]);

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

		$bucket = 'jemis-laravel-training';
		$key = 'images/' . basename($productImage['name']);

		if(is_uploaded_file(($productImage['tmp_name']))) {
			try {
				$aws_response = $s3->putObject([
					'Bucket' => $bucket,
					'Key' => $key,
					'SourceFile' => $productImage['tmp_name']
				]);
				$productData['bucketURL'] = $aws_response['ObjectURL'];
			} catch (S3Exception $e) {
				echo "Error uploading image: " . $e->getMessage();
			}
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
		fwrite($_write, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		fclose($_write);
		http_response_code(201);
		echo json_encode(
			array(
				"message" => "Product added successfully",
				"status" => 201,
				"productData" => $productData
			), JSON_UNESCAPED_SLASHES
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
