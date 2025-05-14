<?php
// pending_residents.php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "resident_records";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['resident_id'], $_POST['action'])) {
    $resident_id = (int)$_POST['resident_id'];

    // Get the reference code from the pending resident
    $get_reference_code = $conn->prepare("SELECT reference_code FROM pending_residents WHERE id = ?");
    $get_reference_code->bind_param("i", $resident_id);
    $get_reference_code->execute();
    $reference_result = $get_reference_code->get_result();
    $reference_row = $reference_result->fetch_assoc();
    $reference_code = $reference_row['reference_code']; // Store reference code for later use
    $get_reference_code->close();

    if ($_POST['action'] === 'approve') {
      
        // Get resident details to approve
        $get = $conn->prepare("SELECT firstname, lastname, birthday, address, gender, barangay
                               FROM pending_residents WHERE id = ?");
        $get->bind_param("i", $resident_id);
        $get->execute();
        $res = $get->get_result()->fetch_assoc();
        $get->close();

        if ($res) {
            $ins = $conn->prepare("INSERT INTO approved_residents 
                (firstname, lastname, birthday, address, gender, barangay, reference_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ins->bind_param("sssssss", $res['firstname'], $res['lastname'], $res['birthday'],
                             $res['address'], $res['gender'], $res['barangay'], $reference_code);
            $ins->execute();
            $ins->close();
        }

        // Delete from pending residents after approval
        $conn->query("DELETE FROM pending_residents WHERE id = $resident_id");

        // Redirect to approved residents page
        header("Location: approved_residents.php");
        exit();
    } elseif ($_POST['action'] === 'reject') {
        // Delete from pending residents if rejected
        $conn->query("DELETE FROM pending_residents WHERE id = $resident_id");
    }

    // Optional: Check if the reference code is valid and approved
    $stmt = $conn->prepare("SELECT * FROM pending_residents WHERE reference_code = ? AND status = 'approved'");
    $stmt->bind_param("s", $reference_code);
    $stmt->execute();
    $result = $stmt->get_result();
    // You can handle the result here if needed, but it's unclear if you're using this anywhere.
    $stmt->close();
}

$result = $conn->query("SELECT * FROM pending_residents ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Residents</title>
    <style>
      body { background:#eef2f5; font-family:Arial,sans-serif; margin:0; padding:20px; }
      .box { background:#fff; border-left:10px solid #00f; padding:20px; margin:20px auto;
             max-width:800px; box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:10px; }
      h2 { text-align:center; color:#00f; }
      table { width:100%; border-collapse:collapse; margin-top:20px; }
      th,td { padding:12px; border:1px solid #ccc; text-align:center; }
      th { background:#00f; color:#fff; }
      .btn { padding:6px 12px; border:none; border-radius:5px; font-weight:bold; cursor:pointer; }
      .approve { background:green; color:#fff; }
      .reject { background:red; color:#fff; }
      .btn:hover { opacity:0.9; }
    </style>
</head>
<body>

<div class="box">
  <h2>Pending Resident Registrations</h2>
  <table>
    <tr>
      <th>Full Name</th>
      <th>Birthday</th>
      <th>Address</th>
      <th>Gender</th>
      <th>Barangay</th>
      <th>Actions</th>
    </tr>

    <?php if ($result->num_rows): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
          <td><?= htmlspecialchars($row['birthday']) ?></td>
          <td><?= htmlspecialchars($row['address']) ?></td>
          <td><?= htmlspecialchars($row['gender']) ?></td>
          <td><?= htmlspecialchars($row['barangay']) ?></td>
          <td>
            <form method="post" style="display:inline">
              <input type="hidden" name="resident_id" value="<?= $row['id'] ?>">
              <button name="action" value="approve" class="btn approve">Approve</button>
            </form>
            <form method="post" style="display:inline">
              <input type="hidden" name="resident_id" value="<?= $row['id'] ?>">
              <button name="action" value="reject" class="btn reject">Reject</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6">No pending residents.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>
