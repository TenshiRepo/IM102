<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM room";
    $result = $conn->query($sql);

    $rooms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }
    echo json_encode(['success' => true, 'rooms' => $rooms]);
}

$conn->close();
?>