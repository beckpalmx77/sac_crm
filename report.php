
<?php
if (isset($_GET['customer_id'])) {
    $customerId = $_GET['customer_id'];
    // ใช้ $customerId ตามที่ต้องการ
    echo "Customer ID: " . $customerId;
} else {
    echo "Customer ID not found.";
}

