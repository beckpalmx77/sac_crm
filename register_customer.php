<?php
include('config/connect_db.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/favicon.ico">
    <title>สมัครสมาชิก SAC Customer Relation</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container mt-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4 text-center">
        <div class="d-flex justify-content-center mb-4">
            <img src="img/logo/sac_crm_logo.png" style="height: 70px; width: auto; max-width: 100%;" />
        </div>
        <h4 class="mb-4">สมัครสมาชิก SAC Customer Relation</h4>

        <form id="registerForm">
            <div class="mb-4 text-center">
                <img id="profilePic" src="" class="rounded-circle" width="100" alt="รูปโปรไฟล์">
            </div>

            <input type="hidden" id="lineUserId" name="lineUserId">
            <div class="mb-4 text-start">
                <label class="form-label"><b>ชื่อ:</b></label>
                <input type="text" id="name" name="name" class="form-control" readonly>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label"><b>เลือกลูกค้า:</b></label>
                <select id="customer_select" class="form-control" style="width: 100%;">
                    <option value="">-- ค้นหารายชื่อลูกค้า --</option>
                    <?php
                    $sql = "SELECT * FROM ims_customer_master";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($customers as $customer) {
                        echo "<option value='{$customer['customer_id']}' data-name='{$customer['customer_name']}'>{$customer['customer_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <input type="hidden" id="customer_id" name="customer_id" value="">
            <input type="hidden" id="customer_name" name="customer_name" value="">

            <div class="mb-4 text-start">
                <label class="form-label"><b>อีเมล:</b></label>
                <input type="email" id="email" name="email" class="form-control" placeholder="กรอกอีเมล" required>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label"><b>เบอร์โทร:</b></label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="กรอกเบอร์โทร" required>
            </div>

            <input type="hidden" id="picture" name="picture" value="">
            <input type="hidden" id="statusMessage" name="statusMessage">

            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">สมัครสมาชิก</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initializing Select2 on the customer select dropdown
        $('#customer_select').select2({
            placeholder: "ค้นหารายชื่อลูกค้า",
            allowClear: true,
        });

        liff.init({liffId: "2007157323-vgg6RBW7"}).then(() => {
            if (liff.isLoggedIn()) {
                liff.getProfile().then(profile => {
                    document.getElementById("lineUserId").value = profile.userId;
                    document.getElementById("name").value = profile.displayName;
                    document.getElementById("profilePic").src = profile.pictureUrl;
                    document.getElementById("picture").value = profile.pictureUrl;
                    document.getElementById("statusMessage").value = profile.statusMessage || "ไม่มีข้อความสถานะ";
                });
            } else {
                liff.login();
            }
        }).catch(err => {
            console.error("LIFF Error: ", err);
        });

        // Handle form submission via AJAX
        document.getElementById("registerForm").addEventListener("submit", function (event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้า

            const formData = new FormData(this);
            fetch("https://syycp.com/sac_crm/api/line_app/register_customer_with_line.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("✅ สมัครสมาชิกสำเร็จ!");
                    } else {
                        alert("❌ สมัครสมาชิกไม่สำเร็จ: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("AJAX Error:", error);
                    alert("❌ เกิดข้อผิดพลาด กรุณาลองใหม่");
                });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initializing Select2
        $('#customer_select').select2({
            placeholder: "ค้นหารายชื่อลูกค้า",
            allowClear: true,
        });

        // On customer selection, update the hidden inputs
        $('#customer_select').on('change', function () {
            let selectedOption = $(this).find('option:selected');
            let customerId = selectedOption.val();
            let customerName = selectedOption.data('name');

            // Set the hidden fields with the selected customer's data
            $('#customer_id').val(customerId);
            $('#customer_name').val(customerName);
        });
    });
</script>

</body>
</html>
