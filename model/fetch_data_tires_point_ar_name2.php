<?php

session_start();
error_reporting(0);

include '../config/connect_db.php';

$year = isset($_POST["year"]) && $_POST["year"] !== '' && $_POST["year"] !== '-' ? $_POST["year"] : "";
$month = isset($_POST["month"]) && $_POST["month"] !== '' && $_POST["month"] !== '-' ? $_POST["month"] : "";
$AR_CODE = isset($_POST["customerId"]) && $_POST["customerId"] !== '' && $_POST["customerId"] !== '-' ? $_POST["customerId"] : "";

//$AR_CODE = "SAC0069";

$where_ar_code = " AND AR_CODE = '" . $AR_CODE . "'";


$sql = "SELECT 
  AR_CODE, 
  AR_NAME,
  shop_type,
  SUM(COALESCE(sum_trd_qty, 0)) AS qty_all, 
  SUM(COALESCE(sum_trd_u_point, 0)) AS u_point, 
  SUM(COALESCE(sum_trd_u_point_total, 0)) AS u_point_all, 
  SUM(COALESCE(sum_trd_s_point, 0)) AS s_point, 
  SUM(COALESCE(sum_trd_s_point_total, 0)) AS s_point_all,  
  SUM(COALESCE(total_points, 0)) AS total_points
FROM v_sac_tires_summary_point_3
WHERE 1 
  AND DI_MONTH LIKE '%" . $month . "%'" . " AND DI_YEAR LIKE '%" . $year . "%' 
  AND (SKU_CODE LIKE 'LL%' OR SKU_CODE LIKE 'LE%' OR SKU_CODE LIKE 'AT%')" . $where_ar_code
. " GROUP BY AR_CODE;";

/*
$myfile = fopen("a-param1.txt", "w") or die("Unable to open file!");
fwrite($myfile, "year = " . $year . "| month = " . $month . " | " . $sql);
fclose($myfile);
*/

// ดำเนินการคำสั่ง SQL
$stmt = $conn->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ส่งผลลัพธ์กลับเป็น JSON
echo json_encode($results);


