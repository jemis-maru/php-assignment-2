<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="./css/style.css">
  <script src="./js/script.js"></script>
</head>

<body>

  <?php include './components/header.php'; ?>
  <div class="d-flex">
    <?php include './components/sidebar.php'; ?>

    <div class="container main-container">
      <h2>Add Product</h2>

      <div id="output"></div>

      <form id="productForm" enctype="multipart/form-data" onsubmit="submitForm(event)">
        <div class="mb-3">
          <label for="productName" class="form-label">Product Name:</label>
          <input type="text" class="form-control" id="productName" name="productName" required>
        </div>

        <div class="mb-3">
          <label for="productPrice" class="form-label">Price:</label>
          <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01" required>
        </div>

        <div class="mb-3">
          <label for="productImage" class="form-label">Image:</label>
          <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
        </div>

        <div class="mb-3">
          <label for="productDescription" class="form-label">Description:</label>
          <textarea class="form-control" id="productDescription" name="productDescription" required></textarea>
        </div>

        <div class="mb-3">
          <label for="productStatus" class="form-label">Status:</label>
          <select class="form-select" id="productStatus" name="productStatus" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

      <div class="mt-3" id="tableData"></div>
    </div>
  </div>

  <?php include './components/footer.php'; ?>

  <script>
    function submitForm(event) {
      event.preventDefault();
      const formData = new FormData(document.getElementById("productForm"));

      $.ajax({
        type: "POST",
        url: "./api/addProduct.php",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function (xhr) {
          let token = localStorage.getItem('token');
          xhr.setRequestHeader('Authorization', token);
        },
        success: function(response) {
          const responseData = JSON.parse(response);
          $("#output").html(`<div class="alert alert-success">${responseData.message}</div>`);
          $("#tableData").html(`
                    <h3>Added product:</h3>
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Field</th>
                          <th>Value</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>Product Name</td><td>${responseData.productData.productName}</td></tr>
                        <tr><td>Product Price</td><td>${responseData.productData.productPrice}</td></tr>
                        <tr><td>Product Image</td><td>${responseData.productData.productImage}</td></tr>
                        <tr><td>Product Description</td><td>${responseData.productData.productDescription}</td></tr>
                        <tr><td>Product Status</td><td>${responseData.productData.productStatus}</td></tr>
                      </tbody>
                    </table>
                `);
          clearForm();
        },
        error: function(_, _2, err) {
          $("#output").html('<div class="alert alert-danger">Error: ' + err + '</div>');
        }
      });
    }

    function clearForm() {
      document.getElementById("productForm").reset();
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>