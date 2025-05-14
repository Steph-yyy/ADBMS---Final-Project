<?php
$conn = new mysqli("localhost", "root", "", "resident_records");

if (!isset($_GET['ref'])) {
    die("Missing reference code.");
}

$ref = $_GET['ref'];
$errors = [];

$stmt = $conn->prepare("SELECT * FROM approved_residents WHERE reference_code = ?");
$stmt->bind_param("s", $ref);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Record not found or not yet approved.");
}
$resident = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $address = trim($_POST['address']);
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $barangay = $_POST['barangay'];

    if ($firstname === '') $errors[] = 'First name is required';
    if ($lastname === '') $errors[] = 'Last name is required';
    if ($address === '') $errors[] = 'Address is required';

    if (empty($errors)) {
        $update = $conn->prepare("UPDATE approved_residents SET firstname=?, lastname=?, birthday=?, address=?, gender=?, barangay=? WHERE reference_code=?");
        $update->bind_param("sssssss", $firstname, $lastname, $birthday, $address, $gender, $barangay, $ref);
        $update->execute();

        echo "<p>Record updated successfully.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-left: 10px solid #17a2b8;
            padding: 20px;
            margin: 0 auto;
            width: 60%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #17a2b8;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-update {
            background-color: #17a2b8;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }
        .btn-update:hover {
            background-color: #138496;
        }
        .btn-cancel, .btn-delete {
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Your Information</h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label>First Name</label>
        <input type="text" name="firstname" value="<?= htmlspecialchars($_SESSION['firstname']) ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?= htmlspecialchars($_SESSION['lastname']) ?>" required>

        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($_SESSION['address']) ?>" required>

        <label>Birthday</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($_SESSION['birthday']) ?>" required>

        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?= ($_SESSION['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= ($_SESSION['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= ($_SESSION['gender'] === 'Other') ? 'selected' : '' ?>>Other</option>
        </select>

        <label>Barangay</label>
        <input type="text" name="barangay" value="<?= htmlspecialchars($_SESSION['barangay']) ?>" required>

        <div class="button-group">
            <button type="submit" name="update" class="btn-update">Update Information</button>
            <a href="confirmation.php" class="btn-cancel">Cancel</a>
            <button type="submit" name="delete" class="btn-delete" onclick="return confirmDelete();">Delete Data</button>
        </div>
    </form>
</div>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete your information?");
}
function confirmUpdate() {
    return confirm("Do you want to save these changes?");
}
</script>

</body>
</html>
