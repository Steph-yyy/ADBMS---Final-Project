<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli('localhost', 'root', '', 'resident_records');
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Initialize editing variables
$editing = false;
$res = [];

// Handle Add Dependent
if (isset($_POST['add_dependent'])) {
    $stmt = $conn->prepare("
        INSERT INTO manage_dependents
        (resident_id, first_name, last_name, relationship, dob, gender, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if ($stmt) {
        $stmt->bind_param(
            "issssss",
            $_POST['resident_id'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['relationship'],
            $_POST['dob'],
            $_POST['gender'],
            $_POST['status']
        );
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?page=manage_residents");
    exit;
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM manage_dependents WHERE id = $id");
    header("Location: dashboard.php?page=manage_residents");
    exit;
}

// Handle Edit
if (isset($_GET['edit_id'])) {
    $editing = true;
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM manage_dependents WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle Update
if (isset($_POST['update_dependent'])) {
    $stmt = $conn->prepare("
        UPDATE manage_dependents
        SET resident_id=?, first_name=?, last_name=?, relationship=?, dob=?, gender=?, status=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "issssssi",
        $_POST['resident_id'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['relationship'],
        $_POST['dob'],
        $_POST['gender'],
        $_POST['status'],
        $_POST['id']
    );
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=manage_residents&update=1");
    exit;
}

$rows = $conn->query("SELECT * FROM manage_dependents ORDER BY id DESC");
?>

<!-- No <html> or <head> tags needed -->
<style>
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
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ccc;
        text-align: center;
    }
    th {
        background: #00f;
        color: #fff;
    }
    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        margin: 2px;
        font-size: 14px;
    }
    .btn-edit {
        background-color: #3498db;
    }
    .btn-danger {
        background-color: #e74c3c;
    }
    .submit-btn {
        background-color: #00f;
        color: white;
        padding: 10px 16px;
        margin-top: 10px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    input, select {
        padding: 8px;
        width: 100%;
        max-width: 300px;
        margin: 5px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
</style>

<div class="box">
    <h2><?= $editing ? 'Edit Dependent' : 'Add New Dependent' ?></h2>
    <form method="post">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($res['id']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Resident ID:</label>
            <input type="number" name="resident_id" value="<?= $editing ? htmlspecialchars($res['resident_id']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= $editing ? htmlspecialchars($res['first_name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= $editing ? htmlspecialchars($res['last_name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Relationship:</label>
            <input type="text" name="relationship" value="<?= $editing ? htmlspecialchars($res['relationship']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?= $editing ? htmlspecialchars($res['dob']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Gender:</label>
            <select name="gender" required>
                <?php foreach (['Male','Female','Other'] as $g): ?>
                    <option value="<?= $g ?>" <?= ($editing && $res['gender'] === $g) ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Status:</label>
            <select name="status" required>
                <?php foreach (['active','inactive'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($editing && $res['status'] === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="<?= $editing ? 'update_dependent' : 'add_dependent' ?>" class="btn submit-btn">
            <?= $editing ? 'Update Dependent' : 'Add Dependent' ?>
        </button>

        <?php if ($editing): ?>
            <a href="dashboard.php?page=manage_residents" class="btn btn-danger">Cancel</a>
        <?php endif; ?>
    </form>

    <h3>Existing Dependents</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Resident ID</th>
            <th>Full Name</th>
            <th>Relationship</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($d = $rows->fetch_assoc()): ?>
            <tr>
                <td><?= $d['id'] ?></td>
                <td><?= htmlspecialchars($d['resident_id']) ?></td>
                <td><?= htmlspecialchars($d['first_name'] . ' ' . $d['last_name']) ?></td>
                <td><?= htmlspecialchars($d['relationship']) ?></td>
                <td><?= htmlspecialchars($d['dob']) ?></td>
                <td><?= htmlspecialchars($d['gender']) ?></td>
                <td><?= htmlspecialchars($d['status']) ?></td>
                <td>
                    <a href="dashboard.php?page=manage_residents&edit_id=<?= $d['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="dashboard.php?page=manage_residents&delete_id=<?= $d['id'] ?>" onclick="return confirm('Delete this dependent?')" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
