<?php
$conn = new mysqli("localhost", "root", "", "resident_records");

// Check for DB connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Deletion logic (triggered by link using ?delete_id=)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    

    $stmt = $conn->prepare("DELETE FROM approved_residents WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: approved_residents.php?msg=deleted");
            exit();
        } else {
            header("Location: approved_residents.php?msg=failed");
            exit();
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approved Residents</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .dashboard_content_container {
            padding: 30px;
        }
        .page-header {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #0000FF;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #0000FF;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            color: white;
            font-weight: bold;
            border-radius: 5px;
        }
        .success { background-color: #28a745; }
        .error { background-color: #dc3545; }
        @media (max-width: 768px) {
            input[type="text"] {
                width: 100%;
            }
            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="dashboard_content_container">
    <div class="page-header">
        <i class="fa fa-users"></i> Approved Residents
    </div>
    <p>Browse and filter approved residents.</p>

    <!-- Display success or error message -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="message <?= $_GET['msg'] === 'deleted' ? 'success' : 'error' ?>">
            <?= $_GET['msg'] === 'deleted' ? 'Resident deleted successfully.' : 'Failed to delete resident.' ?>
        </div>
    <?php endif; ?>

    <div class="search-bar">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by name...">
    </div>

    <table id="residentsTable">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Address</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Barangay</th>
                <th>Reference Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM approved_residents");

            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['birthday']) ?></td>
                <td><?= htmlspecialchars($row['gender']) ?></td>
                <td><?= htmlspecialchars($row['barangay']) ?></td>
                <td style="text-align:right;"><?= htmlspecialchars($row['reference_code']) ?></td>
                <td>
                    <a href="approved_residents.php?delete_id=<?= $row['id'] ?>" class="delete-btn"
                       onclick="return confirm('Are you sure you want to delete this resident?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
// Table search function
function searchTable() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("residentsTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        var td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                var txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}
</script>

</body>
</html>
