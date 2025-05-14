<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "resident_records");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate reference code
function generateReferenceCode($length = 8) {
    return strtoupper(bin2hex(random_bytes($length / 2)));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $address = trim($_POST['address']);
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $barangay = $_POST['barangay'];

    // Validation
    if ($firstname === '') $errors[] = 'First name is required';
    if ($lastname === '') $errors[] = 'Last name is required';
    if ($address === '') $errors[] = 'Address is required';

    if (empty($errors)) {
        $reference_code = generateReferenceCode();

        // Insert directly into pending_residents
        $stmt = $conn->prepare("INSERT INTO pending_residents (firstname, lastname, birthday, address, gender, barangay, reference_code)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstname, $lastname, $birthday, $address, $gender, $barangay, $reference_code);

        if ($stmt->execute()) {
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['address'] = $address;
            $_SESSION['birthday'] = $birthday;
            $_SESSION['gender'] = $gender;
            $_SESSION['barangay'] = $barangay;
            $_SESSION['reference_code'] = $reference_code;

            // Redirect to a confirmation page (pending approval)
            header("Location: confirmation.php");
            exit;
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resident Registration</title>
    <style>
        body { background:#eef2f5; font-family:Arial,sans-serif; padding:20px; }
        .box { background:#fff; border-left:10px solid #00f; padding:30px; margin:30px auto;
               max-width:600px; box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:10px; }
        h1 { text-align:center; color:#00f; }
        label { display:block; margin-top:15px; font-weight:bold; }
        input,select { width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px; }
        .gender-group { display:flex; gap:15px; margin-top:5px; }
        .gender-group label { font-weight:normal; }
        button { margin-top:20px; width:100%; padding:10px; background:#00f; color:#fff;
                 border:none; border-radius:5px; font-size:16px; font-weight:bold; cursor:pointer; }
        button:hover { background:#0033cc; }
        .errors { background:#fdd; padding:10px; border:1px solid #f00; border-radius:5px; }
    </style>
</head>
<body>
<div class="box">
    <h1>Resident Registration</h1>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <label>First Name</label>
        <input type="text" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" required>

        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>

        <label>Birthday</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($_POST['birthday'] ?? '') ?>" required>

        <label>Gender</label>
        <div class="gender-group">
            <?php foreach(['Male','Female','Other'] as $g): ?>
                <label>
                    <input type="radio" name="gender" value="<?= $g ?>"
                        <?= (($_POST['gender'] ?? '') === $g) ? 'checked' : '' ?> required> <?= $g ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label>Barangay</label>
        <select name="barangay" required>
            <option value="">-- Select Barangay --</option>
            <?php foreach(['Barangay Trapiche','Barangay Banadero','Barangay Ambulong','Barangay San Pedro'] as $b): ?>
                <option value="<?= $b ?>" <?= (($_POST['barangay'] ?? '') === $b) ? 'selected' : '' ?>>
                    <?= $b ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
