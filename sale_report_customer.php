<?php
include('config/connect_db.php');

// เก็บค่า liffId ใน PHP
$liffId = "2007157323-wOpQ3vN6";
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/favicon.ico">

    <!-- Include CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>

    <title>SAC Customer Relation</title>
</head>
<body class="bg-light">

<!-- Spinner Loading -->
<div id="loadingSpinner" class="d-flex justify-content-center align-items-center vh-100 bg-white position-fixed w-100" style="z-index: 1050;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container mt-5 d-flex justify-content-center align-items-center min-vh-100" id="content" style="display: none;">
    <div class="card shadow-lg p-4 text-center">
        <div class="d-flex justify-content-center mb-4">
            <img src="img/logo/sac_crm_logo.png" style="height: 70px; width: auto; max-width: 100%;" />
        </div>
        <h4 class="mb-4">รายการสะสมคะแนน</h4>
        <form id="registerForm">
            <div class="mb-4 text-center">
                <img id="profilePic" src="img/icon/user-001.png" class="rounded-circle" width="100" alt="รูปโปรไฟล์">
            </div>
            <input type="hidden" id="lineUserId" name="lineUserId">
            <input type="hidden" id="picture" name="picture">
            <input type="hidden" id="name" name="name" class="form-control" readonly>
        </form>
    </div>
</div>

<!-- Bootstrap & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

<script>
    $(document).ready(function () {
        $("#loadingSpinner").show();
        $("#content").hide();

        // ดึงค่า liffId จาก PHP ผ่าน JavaScript
        const liffId = "<?php echo $liffId; ?>";

        liff.init({liffId: liffId})
            .then(() => {
                if (liff.isLoggedIn()) {
                    liff.getProfile().then(profile => {
                        let userId = profile.userId;
                        let displayName = profile.displayName;
                        let profilePic = profile.pictureUrl ? profile.pictureUrl : "img/icon/user-001.png";

                        $("#lineUserId").val(userId);
                        $("#name").val(displayName);
                        $("#profilePic").attr("src", profilePic);
                        $("#picture").val(profilePic);

                        checkCustomer(userId);
                    });
                } else {
                    liff.login();
                }
            })
            .catch(err => {
                console.error("LIFF Error:", err);
                hideLoading();
            });

        function checkCustomer(userId) {
            $.ajax({
                url: "model/check_customer.php",
                method: "POST",
                data: { userId: userId },
                dataType: "json",
                success: function(response) {
                    hideLoading();
                    if (response.status === "found") {
                        // ส่ง customer_id ไปกับ report2.php
                        let customerId = response.customerId; // สมมติว่า customer_id ถูกส่งกลับมาใน response
                        window.location.href = "report2?customer_id=" + customerId;
                    } else {
                        alertify.error("⚠️ คุณยังไม่ได้ลงทะเบียน กรุณาติดต่อเจ้าหน้าที่");
                        setTimeout(closeWindow, 3000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    hideLoading();
                    alertify.error("เกิดข้อผิดพลาดในการตรวจสอบข้อมูล");
                    setTimeout(closeWindow, 3000);
                }
            });
        }


        function hideLoading() {
            $("#loadingSpinner").fadeOut(300, function() {
                $("#content").fadeIn(300);
            });
        }

        function closeWindow() {
            if (liff.isInClient()) {
                liff.closeWindow();
            } else {
                window.close();
            }
        }
    });
</script>
</body>
</html>
