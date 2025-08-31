<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type']) && isset($_GET['user_id'])) {
    $type = $_GET['type'];
    $generated_by = $_GET['user_id'];
    $generated_at = date('Y-m-d H:i:s');

    // In this version, we are only recording the generation of the report.
    // The actual report data will be sent back in the JSON response.

    if ($type == 'bookings') {
        $sql_report_data = "SELECT b.*, u.name as user_name, r.room_number
                            FROM booking b
                            JOIN user u ON b.user_id = u.user_id
                            JOIN room r ON b.room_id = r.room_id";
        $result_report_data = $conn->query($sql_report_data);

        $report_data = [];
        if ($result_report_data->num_rows > 0) {
            while ($row = $result_report_data->fetch_assoc()) {
                $report_data[] = $row;
            }
        }

        // --- INSERT INTO REPORT TABLE ---
        $sql_insert_report = "INSERT INTO report (generated_by, generated_at, report_type) VALUES (?, ?, ?)";
        $stmt_insert_report = $conn->prepare($sql_insert_report);
        $stmt_insert_report->bind_param("iss", $generated_by, $generated_at, $type);

        if ($stmt_insert_report->execute()) {
            echo json_encode(['success' => true, 'message' => 'Booking report generated and logged successfully', 'report_data' => $report_data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error logging report generation: ' . $stmt_insert_report->error, 'report_data' => $report_data]);
        }

    } else {
        echo json_encode(['error' => 'Invalid report type']);
    }
} else {
    echo json_encode(['error' => 'Invalid request parameters']);
}

$conn->close();
?>