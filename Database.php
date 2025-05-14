<?php

$servername = "localhost"; 
$username = "root";       
$password = "";            
$dbname = "resident_records"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

   
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input_username); 
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    
        if ($input_password == $user['password']) {
           
            echo "Login successful!";
            
           
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that username!";
    }

    $stmt->close();
    $conn->close();
}
?>
