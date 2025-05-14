<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resident_records");

$data = null;
$message = "";

// Check reference code
if (isset($_POST['code'])) {
    $code = trim($_POST['code']);

    $stmt = $conn->prepare("SELECT * FROM approved_residents WHERE reference_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION['edit_id'] = $data['id'];
    } else {
        $message = "Invalid or unapproved reference code.";
    }
}

// Update resident
if (isset($_POST['update']) && isset($_SESSION['edit_id'])) {
    $id = $_SESSION['edit_id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $address = trim($_POST['address']);
    $birthday = trim($_POST['birthday']);
    $gender = $_POST['gender'];
    $barangay = trim($_POST['barangay']);

    $stmt = $conn->prepare("UPDATE approved_residents SET firstname = ?, lastname = ?, address = ?, birthday = ?, gender = ?, barangay = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $firstname, $lastname, $address, $birthday, $gender, $barangay, $id);
    
    if ($stmt->execute()) {
        $message = "Resident details updated successfully!";
        $stmt = $conn->prepare("SELECT * FROM approved_residents WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
    } else {
        $message = "Failed to update resident details.";
    }
    $stmt->close();
}

// Delete resident
if (isset($_POST['delete']) && isset($_SESSION['edit_id'])) {
    $id = $_SESSION['edit_id'];
    $stmt = $conn->prepare("DELETE FROM approved_residents WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Resident record deleted successfully.";
        $data = null;
        unset($_SESSION['edit_id']);
    } else {
        $message = "Failed to delete resident record.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Reference Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #ffffff;
            color: #333333;
        }
        h2 {
            color: #0056b3;
        }
        input[type="text"],
        input[type="date"],
        select {
            padding: 10px;
            width: 250px;
            border: 1px solid #007BFF;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            opacity: 0.9;
        }
        .btn-blue { background-color: #007BFF; }
        .btn-red { background-color: #dc3545; }
        .btn-green { background-color: #28a745; }
        .message {
            color: red;
            margin-top: 10px;
        }
        table {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .container {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enter Reference Code to View Resident Info</h2>
    <form method="POST">
        <label>Reference Code:</label>
        <input type="text" name="code" required>
        <button type="submit" class="btn-blue">Check</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($data): ?>
        <!-- Add New Resident -->
        <a href="register.php?from=check_reference.php">
            <button type="button" class="btn-green">Add New Resident</button>
        </a>

        <h3>Resident Details</h3>
        <table>
            <tr>
                <th>Full Name</th>
                <th>Address</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Barangay</th>
                <th>Reference Code</th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($data['firstname'] . ' ' . $data['lastname']) ?></td>
                <td><?= htmlspecialchars($data['address']) ?></td>
                <td><?= htmlspecialchars($data['birthday']) ?></td>
                <td><?= htmlspecialchars($data['gender']) ?></td>
                <td><?= htmlspecialchars($data['barangay']) ?></td>
                <td><?= htmlspecialchars($data['reference_code']) ?></td>
            </tr>
        </table>

        <h3>Edit Resident Details</h3>
        <form method="POST">
            <input type="hidden" name="code" value="<?= htmlspecialchars($data['reference_code']) ?>">

            <label>First Name:</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($data['firstname']) ?>" required><br>

            <label>Last Name:</label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($data['lastname']) ?>" required><br>

            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($data['address']) ?>" required><br>

            <label>Birthday:</label>
            <input type="date" name="birthday" value="<?= htmlspecialchars($data['birthday']) ?>" required><br>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?= $data['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $data['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            </select><br>

            <label>Barangay:</label>
            <input type="text" name="barangay" value="<?= htmlspecialchars($data['barangay']) ?>" required><br><br>

            <button type="submit" name="update" class="btn-blue">Update</button>
            <button type="submit" name="delete" class="btn-red" onclick="return confirm('Are you sure you want to delete this resident?');">Delete</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>