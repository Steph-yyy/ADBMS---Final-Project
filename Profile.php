<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_Login.php");
    exit();
}

include 'db.php'; // ✅ Make sure this path is correct and the file exists

$user_id = $_SESSION['user_id'];

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $birthday  = $_POST['birthday'];
    $gender    = $_POST['gender'];
    $address   = $_POST['address'];
    $barangay  = $_POST['barangay'];

    $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, birthday=?, gender=?, address=?, barangay=? WHERE id=?");
    $stmt->bind_param("ssssssi", $firstname, $lastname, $birthday, $gender, $address, $barangay, $user_id);
    $stmt->execute();
    $stmt->close();

    $success = "Profile updated successfully.";
}

// Fetch user profile
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .profile-container {
            background: #fff;
            padding: 25px;
            max-width: 600px;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #007bff; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px;
            margin-top: 20px;
            width: 100%;
            border: none;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
        .success { color: green; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>My Profile</h2>
    <?php if (isset($success)): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>First Name</label>
        <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>

        <label>Birthday</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" required>

        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
        </select>

        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

        <label>Barangay</label>
        <input type="text" name="barangay" value="<?= htmlspecialchars($user['barangay']) ?>" required>

        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>

</body>
</html>
