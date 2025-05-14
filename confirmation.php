<?php
session_start();

// Check session
if (!isset($_SESSION['firstname'])) {
    header("Location: register.php");
    exit();
}

$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$address = $_SESSION['address'];
$birthday = $_SESSION['birthday'];
$gender = $_SESSION['gender'];
$barangay = $_SESSION['barangay'];
$reference_code = $_SESSION['reference_code'] ?? 'N/A';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eef2f5; padding: 20px; }
        .container { background: #fff; border-left: 10px solid #007bff; padding: 30px 25px; margin: auto;
                     width: 60%; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; position: relative; }
        h2 { text-align: center; color: #007bff; margin-bottom: 30px; }
        .info { font-size: 18px; margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #ccc; }
        .top-left-btn { position: absolute; top: 20px; left: 20px; }
        .btn { padding: 10px 18px; border: none; border-radius: 5px; font-weight: bold; text-decoration: none;
               display: flex; align-items: center; justify-content: center; cursor: pointer; min-width: 140px; text-align: center; }
        .btn-green { background-color: #28a745; color: white; } .btn-green:hover { background-color: #218838; }
        .btn-gray { background-color: #6c757d; color: white; } .btn-gray:hover { background-color: #5a6268; }
        .btn-blue { background-color: #007bff; color: white; } .btn-blue:hover { background-color: #0069d9; }
        .button-group { display: flex; justify-content: space-between; margin-top: 30px; flex-wrap: wrap; }
        .left-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .right-button { margin-top: 10px; }
        .reference-note { font-size: 14px; color: #555; margin-top: 5px; }
        @media (max-width: 768px) {
            .container { width: 95%; padding: 20px; }
            .button-group { flex-direction: column; gap: 10px; align-items: center; }
            .left-buttons { flex-direction: column; width: 100%; align-items: center; }
            .right-button { width: 100%; display: flex; justify-content: center; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-left-btn">
        <a href="pending.php" class="btn btn-green">See Pending</a>
    </div>

    <h2>Registration Confirmation</h2>

    <p class="info"><b>First Name:</b> <?= htmlspecialchars($firstname); ?></p>
    <p class="info"><b>Last Name:</b> <?= htmlspecialchars($lastname); ?></p>
    <p class="info"><b>Address:</b> <?= htmlspecialchars($address); ?></p>
    <p class="info"><b>Birthday:</b> <?= htmlspecialchars($birthday); ?></p>
    <p class="info"><b>Gender:</b> <?= htmlspecialchars($gender); ?></p>
    <p class="info"><b>Barangay:</b> <?= htmlspecialchars($barangay); ?></p>
    <p class="info"><b>Reference Code:</b> <?= htmlspecialchars($reference_code); ?></p>
    <p class="reference-note">⚠️ Keep your reference code safe. You will need it to edit your record after approval.</p>

    <div class="button-group">
        <div class="left-buttons">
            <a href="option.php" class="btn btn-gray">Back to Options</a>
        </div>
        <div class="right-button">
            <a href="register.php" class="btn btn-blue">Register Another</a>
        </div>
    </div>
</div>

</body>
</html>
