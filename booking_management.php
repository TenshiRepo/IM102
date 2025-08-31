<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $room_id = $_POST['room_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = 'pending'; // Initial status

    $sql = "INSERT INTO booking (user_id, room_id, start_time, end_time, status) 
            VALUES ('$user_id', '$room_id', '$start_time', '$end_time', '$status')";

    if ($conn->query($sql) === TRUE) {
        $booking_id = $conn->insert_id; // Get the last inserted ID
        echo json_encode(['success' => true, 'message' => 'Booking created successfully', 'booking_id' => $booking_id]);

        //  ---  INSERT INTO NOTIFICATION TABLE ---
        $message = "New booking request created.";
        $sent_at = date('Y-m-d H:i:s');
        $sql_notification = "INSERT INTO notification (user_id, booking_id, message, sent_at) VALUES ('$user_id', '$booking_id', '$message', '$sent_at')";

        if ($conn->query($sql_notification) !== TRUE) {
            echo json_encode(['success' => false, 'message' => 'Error creating notification: ' . $conn->error]);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating booking: ' . $conn->error]);
    }
}

$conn->close();
?>

<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['pending'])) {
    $sql = "SELECT b.*, u.name as user_name, r.room_number 
            FROM booking b
            JOIN user u ON b.user_id = u.user_id
            JOIN room r ON b.room_id = r.room_id
            WHERE b.status = 'pending'";
    $result = $conn->query($sql);

    $bookings = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    echo json_encode(['success' => true, 'bookings' => $bookings]);
}

$conn->close();
?>

<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id']) && isset($_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    $sql = "UPDATE booking SET status = '$status' WHERE booking_id = '$booking_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Booking status updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating booking status: ' . $conn->error]);
    }
}

$conn->close();
?>

<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT b.*, r.room_number 
            FROM booking b
            JOIN room r ON b.room_id = r.room_id
            WHERE b.user_id = '$user_id'
            ORDER BY b.created_at DESC";
    $result = $conn->query($sql);

    $bookings = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    echo json_encode(['success' => true, 'bookings' => $bookings]);
}

$conn->close();
?>