<?php
// edit_household.php
session_start();
$mysqli = new mysqli("localhost", "root", "", "resident_records");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid household ID.");
}

// Handle update
if (isset($_POST['update'])) {
    $address = $_POST['address'];
    $head = $_POST['head'];
    $members = intval($_POST['members']);

    $stmt = $mysqli->prepare("UPDATE households SET address = ?, head = ?, members = ? WHERE id = ?");
    $stmt->bind_param("ssii", $address, $head, $members, $id);
    $stmt->execute();

    echo "<script>window.location.href='dashboard.php?page=view_household&update=success';</script>";
    exit();
}

// Handle delete
if (isset($_POST['delete'])) {
    $stmt = $mysqli->prepare("DELETE FROM households WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>alert('Household deleted successfully.'); window.location.href='dashboard.php?page=view_household&delete=success';</script>";
    exit();
}

// Fetch data//manage residents
$stmt = $mysqli->prepare("SELECT * FROM households WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$household = $result->fetch_assoc();

if (!$household) {
    die("Household not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Household</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-left: 10px solid #ffc107;
            padding: 20px;
            margin: 0 auto;
            width: 60%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #ffc107;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="text"], input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-update {
            background-color: #ffc107;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-update:hover {
            background-color: #e0a800;
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
    <h2>Edit Household</h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($household['address']) ?>" required>

        <label>Head of Household</label>
        <input type="text" name="head" value="<?= htmlspecialchars($household['head']) ?>" required>

        <label>Number of Members</label>
        <input type="number" name="members" value="<?= (int)$household['members'] ?>" required>

        <div class="button-group">
            <button type="submit" name="update" class="btn-update">Update Household</button>
            <a href="dashboard.php?page=view_household" class="btn-cancel">Cancel</a>
            <button type="submit" name="delete" class="btn-delete" onclick="return confirmDelete();">Delete Household</button>
        </div>
    </form>
</div>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this household?");
}
function confirmUpdate() {
    return confirm("Do you want to save the changes?");
}
</script>

</body>
</html>
