<!DOCTYPE html>
<html>
<head>
  <title>Resident Records System</title>
  <style>
    body {
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }

    .top-blue-box {
      background-color: #0000FF;
      color: white;
      padding: 15px 30px;
      text-align: center;
      font-size: 30px;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .container {
      text-align: center;
      padding: 60px 20px;
    }

    .card-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 40px;
    }

    .card {
      background-color: #ffffff;
      padding: 30px;
      width: 300px;
      text-decoration: none;
      border: 2px solid #0000FF;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
      color: #0000FF;
      font-weight: bold;
      font-size: 20px;
    }

    .card:hover {
      background-color: #0000FF;
      color: white;
    }
  </style>
</head>
<body>

  <div class="top-blue-box"> 
    Welcome to the Resident Records System
  </div>

  <div class="container">
    <div class="card-container">
      <a href="Register.php" class="card">
        <h2>Register</h2>
      </a>
      <a href="AdminLogin.php" class="card">
        <h2>Admin Login</h2>
      </a>
    </div>
  </div>

</body>
</html>

