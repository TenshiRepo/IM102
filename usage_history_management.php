<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'log') {
        $user_id = $_POST['user_id'];
        $room_id = $_POST['room_id'];
        $date_used = date('Y-m-d'); // Assuming today's date for 'date_used'
        $duration = $_POST['duration'];
        $purpose = $_POST['purpose'];

        $sql = "INSERT INTO usagehistory (user_id, room_id, date_used, duration, purpose) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiss", $user_id, $room_id, $date_used, $duration, $purpose);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usage history logged']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error logging usage history: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT uh.*, u.name as user_name, r.room_number
            FROM usagehistory uh
            JOIN user u ON uh.user_id = u.user_id
            JOIN room r ON uh.room_id = r.room_id
            WHERE uh.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $history = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
    }
    echo json_encode(['success' => true, 'usage_history' => $history]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>