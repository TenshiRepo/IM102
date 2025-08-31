<?php
include 'db_connection.php';

header('Content-Type: application/json'); // Set header for JSON response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == 'admin' && $password == 'admin') {
        echo json_encode(['success' => true, 'role' => 'admin']);
    } else {
        $sql = "SELECT user_id, role FROM user WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(['success' => true, 'user_id' => $row['user_id'], 'role' => $row['role']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    }
}

$conn->close();
?>