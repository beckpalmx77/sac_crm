<?php
include('../config/connect_db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = trim($_POST['userId'] ?? '');

    if (!empty($userId)) {
        try {
            $sql = "SELECT customer_id,customer_name FROM ims_customer_line_user WHERE line_user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // ส่ง customer_id กลับไปใน response
                echo json_encode(["status" => "found", "userId" => $userId, "customerId" => $result['customer_id'], "customerName" => $result['customer_name']]);
            } else {
                echo json_encode(["status" => "not_found", "userId" => $userId]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User ID is empty"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
