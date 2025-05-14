<?php
$mysqli = new mysqli("localhost", "root", "", "resident_records");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get all households
$result = $mysqli->query("SELECT * FROM households");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Households</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            padding: 20px;
        }

        .blue-box {
            background-color: #ffffff;
            border-left: 10px solid #0000FF;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #0000FF;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            margin-bottom: 15px;
            display: inline-block;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #0000FF;
            color: white;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .btn-view {
            background-color: #17a2b8;
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="blue-box">
    <h2>Households Information</h2>

    <a href="add_household.php" class="add-btn">+ Add Household</a>

    <table>
        <tr>
            <th>Household ID</th>
            <th>Address</th>
            <th>Head of Household</th>
            <th>Members</th>
            <th></th>
        </tr>

        <?php while ($household = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $household['id'] ?></td>
            <td><?= htmlspecialchars($household['address']) ?></td>
            <td><?= htmlspecialchars($household['head']) ?></td>
            <td><?= $household['members'] ?></td>
            <td>

                <a href="edit_household.php?id=<?= $household['id'] ?>" class="btn btn-edit">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
