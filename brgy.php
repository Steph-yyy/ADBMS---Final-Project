<?php
// brgy.php

// 1) Start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Database connection
$conn = new mysqli('localhost','root','','resident_records');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3) Handle Add Barangay
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO brgy (name) VALUES (?)");
    $stmt->bind_param("s", $_POST['barangay_name']);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=brgy");
    exit;
}

// 4) Handle Delete Barangay
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM brgy WHERE id = $id");
    header("Location: dashboard.php?page=brgy");
    exit;
}

// 5) Prepare to Edit
$editing = false;
$brgy = ['id'=>0,'name'=>''];
if (isset($_GET['edit_id'])) {
    $editing = true;
    $id = (int)$_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM brgy WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$res) {
        die("Barangay not found.");
    }
    $brgy = $res;
}

// 6) Handle Update Barangay
if (isset($_POST['edit'])) {
    $stmt = $conn->prepare("UPDATE brgy SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['barangay_name'], $_POST['barangay_id']);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=brgy");
    exit;
}

// 7) Fetch all barangays
$result = $conn->query("SELECT * FROM brgy ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Barangays</title>
 <style>
    body {
        font-family: Arial, sans-serif;
        background: #eef2f5;
        padding: 20px;
    }
    .container {
        background: #fff;
        border-left: 8px solid #007bff;
        padding: 20px;
        border-radius: 8px;
        max-width: 700px;
        margin: auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #007bff;
        margin-bottom: 20px;
    }
    .form-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-bottom: 20px;
    }
    .form-inline input[type="text"] {
        flex: 1 1 200px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .btn {
        padding: 8px 14px;
        border: none;
        border-radius: 4px;
        color: #fff;
        cursor: pointer;
        transition: opacity .2s;
        font-size: 14px;
        text-decoration: none;
        text-align: center;
    }
    .btn:hover { opacity: .85; }
    .add { background: #007bff; }
    .edit { background: #28a745; }
    .delete { background: #dc3545; }
    .cancel { background: #6c757d; }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background: #007bff;
        color: #fff;
    }
    tbody tr:hover {
        background: #f1f1f1;
    }
    @media (max-width: 600px) {
        .form-inline { flex-direction: column; align-items: stretch; }
    }
    </style>
</head>
<body>

<div class="container">
  <h2>Manage Barangays</h2>

  <!-- ADD / EDIT FORM -->
  <form method="POST" style="margin-bottom:20px;">
    <?php if ($editing): ?>
      <input type="hidden" name="barangay_id" value="<?= $brgy['id'] ?>">
    <?php endif; ?>

    <input type="text" name="barangay_name" placeholder="Enter barangay name"
           value="<?= htmlspecialchars($brgy['name']) ?>" required>
    <?php if ($editing): ?>
      <button type="submit" name="edit" class="btn edit">Update</button>
      <a href="dashboard.php?page=brgy" class="btn delete">Cancel</a>
    <?php else: ?>
      <button type="submit" name="add" class="btn add">Add</button>
    <?php endif; ?>
  </form>

  <!-- LIST TABLE -->
  <table>
    <tr>
      <th>Barangay Name</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>
          <a href="dashboard.php?page=brgy&edit_id=<?= $row['id'] ?>" class="btn edit">Edit</a>
          <a href="dashboard.php?page=brgy&delete_id=<?= $row['id'] ?>"
             onclick="return confirm('Delete this barangay?')"
             class="btn delete">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
