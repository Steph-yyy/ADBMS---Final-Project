<?php
session_start();
$error_message = "";
//table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "resident_records");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Fetch admin table
    $sql = "SELECT * FROM adminlogin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: Dashboard.php"); // direct kay dashboard
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body {
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }

    .login-header {
      text-align: center;
      margin-top: 40px;
    }

    .login-header h1 {
      color: #0000FF;
      font-size: 45px;
      font-weight: bold;
    }

    .login-body {
      width: 350px;
      margin: 40px auto;
      background-color: rgba(0, 0, 0, 0.05);
      padding: 30px;
      border: 2px solid #0000FF;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .login-body input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .login-body button {
      width: 100%;
      padding: 10px;
      margin-top: 15px;
      background-color: #0000FF;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .login-body button:hover {
      background-color: #0033cc;
    }

    .error-message {
      color: red;
      font-size: 14px;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="login-header">
  <h1>Resident Records Inventory Management</h1>
</div>

<div class="login-body">
  <form method="POST">
    <div>
      <label>Username</label><br>
      <input type="text" name="username" required><br>
    </div>    

    <div>
      <br><label>Password</label><br>
      <input type="password" name="password" required><br>
    </div> 

    <div>
      <br><button type="submit">LOGIN</button>
    </div> 

    <?php
    if (!empty($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }
    ?>
  </form>
</div>

</body>
</html>
