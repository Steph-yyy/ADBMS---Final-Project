<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli('localhost', 'root', '', 'resident_records');
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$dependent = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("
        UPDATE manage_dependents
        SET resident_id=?, first_name=?, last_name=?, relationship=?, dob=?, gender=?, status=?
        WHERE id=?
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "issssssi",
        $_POST['resident_id'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['relationship'],
        $_POST['dob'],
        $_POST['gender'],
        $_POST['status'],
        $id
    );

    if (!$stmt->execute()) {
        die("Update failed: " . $stmt->error);
    }

    $stmt->close();

    // ✅ Redirect to manage_dependents through dashboard
    header("Location: dashboard.php?page=manage_residents&updated=1");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM manage_dependents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dependent = $result->fetch_assoc();
    $stmt->close();

    if (!$dependent) {
        die("Dependent not found.");
    }
} else {
    die("No ID provided.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Dependent</title>
    <style>
        body {
            background: #eef2f5;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .box {
            background: #fff;
            border-left: 10px solid #00f;
            padding: 20px;
            max-width: 900px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #00f;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 4px;
        }
        input, select {
            padding: 8px;
            width: 100%;
            max-width: 300px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #00f;
            color: white;
            padding: 10px 16px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cancel-btn {
            background-color: #aaa;
            color: white;
            padding: 10px 16px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Dependent</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($dependent['id'], ENT_QUOTES, 'UTF-8'); ?>">

        <div class="form-group">
            <label>Resident ID:</label>
            <input type="number" name="resident_id" value="<?php echo htmlspecialchars($dependent['resident_id']); ?>" required>
        </div>

        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($dependent['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($dependent['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Relationship:</label>
            <input type="text" name="relationship" value="<?php echo htmlspecialchars($dependent['relationship']); ?>" required>
        </div>

        <div class="form-group">
            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo htmlspecialchars($dependent['dob']); ?>" required>
        </div>

        <div class="form-group">
            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?= $dependent['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $dependent['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $dependent['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status:</label>
            <select name="status" required>
                <option value="active" <?= $dependent['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $dependent['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Update</button>
        <a href="dashboard.php?page=manage_residents" class="cancel-btn">Cancel</a>
    </form>
</div>

</body>
</html>
