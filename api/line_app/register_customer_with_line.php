<?php

include '../../config/connect_db.php';

// รับข้อมูลจากฟอร์ม พร้อมตั้งค่า default ให้เป็นค่าว่างในกรณีที่ไม่ได้รับข้อมูล
$lineUserId         = $_POST['lineUserId'] ?? '';
$lineUserName       = $_POST['name'] ?? '';
$lineEmail          = $_POST['email'] ?? '';
$linePhone          = $_POST['phone'] ?? '';
$linePictureProfile = $_POST['picture'] ?? '';
$lineStatusProfile  = $_POST['statusMessage'] ?? '';
$customer_id  = $_POST['customer_id'] ?? '';
$customer_name  = $_POST['customer_name'] ?? '';

/*
$my_file = fopen("a-log.txt", "w") or die("Unable to open file!");
fwrite($my_file, $customer_id . " | " . $customer_name);
fclose($my_file);
*/

// ตรวจสอบค่าที่ได้รับมาจากฟอร์มก่อนการประมวลผล
if (empty($lineUserId) || empty($lineEmail) || empty($linePhone)) {
    echo json_encode([
        "success" => false,
        "message" => "กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน"
    ]);
    exit;
}

// คำสั่ง SQL เพื่อค้นหาข้อมูลลูกค้าในฐานข้อมูล
$sql_find = "SELECT COUNT(*) FROM ims_customer_line_user WHERE line_user_id = :lineUserId";
$stmt_find = $conn->prepare($sql_find);
$stmt_find->execute([ ':lineUserId' => $lineUserId ]);
$nRows = $stmt_find->fetchColumn();

if ($nRows > 0) {
    // หากมีข้อมูล userId ซ้ำในฐานข้อมูล
    echo json_encode([
        "success" => false,
        "message" => "Line Account นี้มีอยู่ในระบบแล้ว"
    ]);
    exit;
} else {
    // ถ้าไม่มีข้อมูลซ้ำ, ทำการบันทึกข้อมูลใหม่ลงฐานข้อมูล
    try {
        $sql_insert = "INSERT INTO ims_customer_line_user 
                    (line_user_id, line_user_name, line_email, line_phone, line_picture_profile, line_status_profile,customer_id,customer_name)
                    VALUES (:lineUserId, :lineUserName, :lineEmail, :linePhone, :linePictureProfile, :lineStatusProfile, :customer_id ,:customer_name)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':lineUserId', $lineUserId, PDO::PARAM_STR);
        $stmt_insert->bindParam(':lineUserName', $lineUserName, PDO::PARAM_STR);
        $stmt_insert->bindParam(':lineEmail', $lineEmail, PDO::PARAM_STR);
        $stmt_insert->bindParam(':linePhone', $linePhone, PDO::PARAM_STR);
        $stmt_insert->bindParam(':linePictureProfile', $linePictureProfile, PDO::PARAM_STR);
        $stmt_insert->bindParam(':lineStatusProfile', $lineStatusProfile, PDO::PARAM_STR);
        $stmt_insert->bindParam(':customer_id', $customer_id, PDO::PARAM_STR);
        $stmt_insert->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
        $stmt_insert->execute();

        // หากบันทึกสำเร็จ
        $lastInsertId = $conn->lastInsertId();
        if ($lastInsertId) {
            echo json_encode(["success" => true, "message" => "สมัครสมาชิกสำเร็จ"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถบันทึกข้อมูลได้"]);
        }

    } catch (PDOException $e) {
        // หากเกิดข้อผิดพลาดในการทำงานกับฐานข้อมูล
        echo json_encode([
            "success" => false,
            "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()
        ]);
    }
}
