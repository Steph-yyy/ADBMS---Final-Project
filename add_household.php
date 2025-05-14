<?php
$mysqli = new mysqli("localhost", "root", "", "resident_records");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['add'])) {
    $address = $_POST['address'];
    $head = $_POST['head'];
    $members = $_POST['members'];

    $stmt = $mysqli->prepare("INSERT INTO households (address, head, members) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $address, $head, $members);
    $stmt->execute();

    echo "<script>
        alert('Household added successfully.');
        window.location.href = 'dashboard.php?page=view_household';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Household</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            border-left: 10px solid #28a745;
            padding: 20px;
            margin: 0 auto;
            width: 60%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #28a745;
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
            font-size: 16px;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-add:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Household</h2>
    <form method="POST">
        <label>Address</label>
        <input type="text" name="address" required>

        <label>Head of Household</label>
        <input type="text" name="head" required>

        <label>Number of Members</label>
        <input type="number" name="members" min="1" required>

        <button type="submit" name="add" class="btn-add">Add Household</button>
    </form>
</div>

</body>
</html>
