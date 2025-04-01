<?php
session_start();
error_reporting(0);

include '../config/connect_db.php';

$year = isset($_POST["year"]) && $_POST["year"] !== '' && $_POST["year"] !== '-' ? $_POST["year"] : "";
$month = isset($_POST["month"]) && $_POST["month"] !== '' && $_POST["month"] !== '-' ? $_POST["month"] : "";
$AR_CODE = isset($_POST["customerId"]) && $_POST["customerId"] !== '' && $_POST["customerId"] !== '-' ? $_POST["customerId"] : "";

//$AR_CODE = "SAC0069";

$where_ar_code = " AND a.AR_CODE = '" . $AR_CODE . "'";

$sql = "
SELECT
    a.DI_DATE, 
    a.AR_CODE,
    a.AR_NAME,
    a.SKU_CODE,
    a.SKU_NAME,
    p.TIRES_EDGE,
    CASE 
        WHEN s.status = 'Y' THEN 'SHOP' 
        ELSE 'ร้านทั่วไป' 
    END AS SHOP_TYPE,
    a.TRD_QTY,
    p.TRD_U_POINT,
    a.TRD_QTY * p.TRD_U_POINT AS TRD_R_POINT,
    CASE 
        WHEN s.status = 'Y' THEN p.TRD_S_POINT 
        ELSE 0 
    END AS TRD_S_POINT,
    a.TRD_QTY * 
        CASE 
            WHEN s.status = 'Y' THEN p.TRD_S_POINT 
            ELSE 0 
        END AS TRD_T_POINT,
    (a.TRD_QTY * p.TRD_U_POINT) + 
        a.TRD_QTY * 
        CASE 
            WHEN s.status = 'Y' THEN p.TRD_S_POINT 
            ELSE 0 
        END AS TRD_TOTAL_POINT_ALL
FROM ims_data_sale_sac_all a
LEFT JOIN ims_ar_shop s ON s.ar_code = a.AR_CODE
LEFT JOIN ims_sac_tires_point p ON p.sku_code = a.SKU_CODE
WHERE 
    (a.SKU_CODE LIKE 'LL%' OR a.SKU_CODE LIKE 'LE%' OR a.SKU_CODE LIKE 'AT%') 
    AND a.SKU_CODE NOT LIKE 'CL%'
    AND p.TIRES_EDGE LIKE 'R%'    
    AND a.DI_MONTH LIKE '%" . $month . "%' 
    AND a.DI_YEAR LIKE '%" . $year . "%'" . $where_ar_code . " ORDER BY STR_TO_DATE(a.DI_DATE, '%d-%m-%Y') ; ";

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


