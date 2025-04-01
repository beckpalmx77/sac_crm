<?php
include('includes/Header.php');
include("config/connect_db.php");

$month_num = str_replace('0', '', date('m'));
$year_num = date('Y');
$customerId = "";
$customerId = "";

if (isset($_GET['customer_id'])) {
    $customerId = $_GET['customer_id'];
}

if (isset($_GET['customer_name'])) {
    $customerName = $_GET['customer_name'];
}

$sql_curr_month = " SELECT * FROM ims_month where month = '" . $month_num . "'";

$stmt_curr_month = $conn->prepare($sql_curr_month);
$stmt_curr_month->execute();
$MonthCurr = $stmt_curr_month->fetchAll();
foreach ($MonthCurr as $row_curr) {
    $month_name = $row_curr["month_name"];
}

$sql_month = " SELECT * FROM ims_month ";
$stmt_month = $conn->prepare($sql_month);
$stmt_month->execute();
$MonthRecords = $stmt_month->fetchAll();

$sql_year = " SELECT DISTINCT(DI_YEAR) AS DI_YEAR
    FROM ims_data_sale_sac_all WHERE 1
    order by DI_YEAR desc ";
$stmt_year = $conn->prepare($sql_year);
$stmt_year->execute();
$YearRecords = $stmt_year->fetchAll();


?>

<!DOCTYPE html>
<html lang="th">

<body id="page-top">
<div id="wrapper">

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Container Fluid-->
            <div class="container-fluid" id="container-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-12">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            </div>
                            <div class="card-body">
                                <section class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12 col-md-offset-2">
                                            <div class="panel">
                                                <div class="panel-body">

                                                    <form id="myform" name="myform"
                                                          action="engine/chart_data_daily.php" method="post">

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h3>รายงานคะแนนสะสม</h3>
                                                                <div><h3><?php echo $customerName; ?></h3></div>
                                                                <label for="month">เลือกเดือน :</label>
                                                                <select name="month" id="month" class="form-control"
                                                                        required>
                                                                    <option value="-"
                                                                            selected>ทุกเดือน
                                                                    </option>
                                                                    <?php foreach ($MonthRecords as $row) { ?>
                                                                        <option value="<?php echo $row["month"]; ?>">
                                                                            <?php echo $row["month_name"]; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                                <label for="year">เลือกปี :</label>
                                                                <select name="year" id="year" class="form-control"
                                                                        required>
                                                                    <option value="<?php echo $year_num ?>"
                                                                            selected><?php echo $year_num ?>
                                                                    </option>
                                                                    <?php /* foreach ($YearRecords as $row) { ?>
                                                                        <option value="<?php echo $row["DI_YEAR"]; ?>">
                                                                            <?php echo $row["DI_YEAR"]; ?>
                                                                        </option>
                                                                    <?php } */ ?>
                                                                </select>

                                                                <br>
                                                                <div><input type="hidden" id="customerName"
                                                                            name="customerName"
                                                                            value="<?php echo $customerName; ?>"></div>
                                                                <div><input type="hidden" id="customerId"
                                                                            name="customerId"
                                                                            value="<?php echo $customerId; ?>"></div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <button type="button" id="BtnSale1"
                                                                                name="BtnSale1"
                                                                                class="btn btn-primary mb-3">
                                                                            รายละเอียด
                                                                        </button>
                                                                        <button type="button" id="BtnSale2"
                                                                                name="BtnSale2"
                                                                                class="btn btn-primary mb-3">
                                                                            คะแนนรวม
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.col-md-8 col-md-offset-2 -->
                                    </div>
                                    <!-- /.row -->

                                </section>


                            </div>

                        </div>

                    </div>

                </div>
                <!--Row-->

                <!-- Row -->

            </div>

            <!---Container Fluid-->

        </div>

    </div>
</div>

<!-- Scroll to top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Select2 -->
<script src="vendor/select2/dist/js/select2.min.js"></script>
<!-- Bootstrap Datepicker -->
<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap Touchspin -->
<script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
<!-- ClockPicker -->
<script src="vendor/clock-picker/clockpicker.js"></script>
<!-- RuangAdmin Javascript -->
<script src="js/myadmin.min.js"></script>
<!-- Javascript for this page -->

<script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
<script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
<!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
<link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

<script src="js/MyFrameWork/framework_util.js"></script>

<script>
    $(document).ready(function () {
        $("#BtnSale1").click(function () {
            if ($("#year").val() !== '-') {
                document.forms['myform'].action = 'show_data_tires_point_by_ar_name1';
                document.forms['myform'].submit();
            } else {
                alertify.error("เลือก ปี");
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#BtnSale2").click(function () {
            if ($("#year").val() !== '-') {
                document.forms['myform'].action = 'show_data_tires_point_by_ar_name2';
                document.forms['myform'].submit();
            } else {
                alertify.error("เลือก ปี");
            }
        });
    });
</script>

</body>

</html>