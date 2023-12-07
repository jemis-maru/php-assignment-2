<?php
session_start();

if (isset($_SESSION['user_name'])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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
    <h2>Login</h2>

    <div id="output"></div>

    <form id="loginForm" onsubmit="submitForm(event)">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</div>

<?php include './components/footer.php'; ?>

<script>
  function submitForm(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById("loginForm"));

    $.ajax({
      type: "POST",
      url: "./api/login.php",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        const responseData = JSON.parse(response);
        $("#output").html(`<div class="alert alert-success">${responseData.message}</div>`);
        localStorage.setItem('token', responseData.token);
        window.location.href = 'index.php';
        clearForm();
      },
      error: function(_, _2, err) {
        $("#output").html('<div class="alert alert-danger">Error: ' + err + '</div>');
      }
    });
  }

  function clearForm() {
    document.getElementById("loginForm").reset();
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
