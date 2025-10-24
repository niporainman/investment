<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Your Plans</title>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->

    <!-- breadcrumb -->
    <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Your Plans</li>
                    </ol>
                </nav>
                <h5>Your Investment Plans</h5>
            </div>
            <div class="col-12 col-sm-auto text-end py-3 py-sm-0">

                <a href="invest" class="btn btn-theme">
                    <i class="bi bi-plus-lg"></i> Add Plan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- content -->
    <div class="container mt-4" id="main-content">
        <div class="row">
            <?php
            $counter = 0;
            $details_word = "Investment";
            $approved_word = "Approved";
            $stmt_s = $con -> prepare('SELECT transaction_id, amount, date, investment_id, investment_type, plan_name, level, roi, duration, end_date FROM transactions WHERE user_id = ? AND (details = ? AND status = ?)');
            $stmt_s -> bind_param('sss', $user_id, $details_word, $approved_word);
            $stmt_s -> execute(); 
            $stmt_s -> store_result(); 
            $stmt_s -> bind_result($transaction_id, $amount, $start_date, $investment_id, $investment_type, $plan_name, $level, $roi, $duration, $end_date); 
            $numrows_s = $stmt_s -> num_rows();
            if($numrows_s > 0){ 
                while ($stmt_s -> fetch()) {
                    //initiate a counter
                    $counter++;

                    // Calculate days remaining
                    $days_remaining = ceil((strtotime($end_date) - time()) / 86400);

                    // Ensure it never goes below 0
                    $days_remaining = max(0, $days_remaining);

                    // Calculate daily ROI
                    $daily_amount = ($amount * $roi) / 100;

                    //format end date
                    $end_date1=date_create("$end_date");
		            $end_date_formatted = date_format($end_date1,"D,dS M, Y");

                    //format start date
                    $start_date1=date_create("$start_date");
		            $start_date_formatted = date_format($start_date1,"D,dS M, Y");
            ?>
            <div class="col-12 col-lg-6 col-xxl-4 mb-4">
                <div class="card adminuiux-card">
                    <div class="card-body">
                        <div class="row gx-3 mb-3">
                            <div class="col-auto">
                                <i class="bi bi-coin fs-4 avatar avatar-50 bg-success text-white rounded"></i>
                            </div>
                            <div class="col">
                                <h4 class="mb-0 fw-medium"><?= $plan_name ?></h4>
                                <p class="small opacity-75">Level <?= $level ?></p>
                            </div>
                        </div>
                        <div class="text-center mt-2 mb-3">
                            <div class="height-120 width-120 position-relative d-inline-block mx-auto text-white mb-3">
                                <?php
                                    // Calculate progress
                                    $total_days = (strtotime($end_date) - strtotime($start_date)) / 86400;
                                    $elapsed_days = (time() - strtotime($start_date)) / 86400;

                                    // Prevent invalid percentages
                                    if ($total_days > 0) {
                                        $percentage = ($elapsed_days / $total_days) * 100;
                                    } else {
                                        $percentage = 0;
                                    }

                                    // Clamp between 1 and 100, but only if investment has started
                                    if ($elapsed_days > 0) {
                                        $percentage = max(1, min(100, $percentage));
                                    } else {
                                        $percentage = 0; // Not started yet
                                    }
                                    ?>
                                    <?php
                                    // Create unique ID based on plan ID or loop index
                                    $progress_id = "circleprogressgreen" . $counter; // or use $loop_index
                                    ?>
                                    <div id="<?= $progress_id ?>"></div>

                                    <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var el = document.getElementById("<?= $progress_id ?>");
                                        if (el) {
                                            var t = new ProgressBar.Circle(el, {
                                                color: "#000000",
                                                strokeWidth: 10,
                                                trailWidth: 10,
                                                easing: "easeInOut",
                                                trailColor: "rgba(126, 170, 0, 0.15)",
                                                duration: 1400,
                                                text: { autoStyleContainer: false },
                                                from: { color: "#6faa00", width: 10 },
                                                to: { color: "#6faa00", width: 10 },
                                                step: function(state, circle) {
                                                    circle.path.setAttribute("stroke", state.color);
                                                    circle.path.setAttribute("stroke-width", state.width);
                                                    var value = Math.round(circle.value() * 100);
                                                    circle.setText(value > 0 ? value + "<small>%<small>" : "");
                                                }
                                            });

                                            t.text.style.fontSize = "24px";
                                            t.animate(<?= $percentage / 100 ?>);
                                        }
                                    });
                                    </script>


                            </div>
                            <h2 class="mb-0"><?= $currency ?> <?= number_format((float)$amount, 2, '.', ','); ?></h2>
                            <p class="text-secondary"><?= $duration ?> days</p>
                        </div>
                        <hr>

                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <i class="bi bi-toggles"></i>
                            </div>
                            <div class="col">
                                <p class="text-secondary small">Type: <b><?= $investment_type ?></b></p>
                            </div>
                        </div>
                        
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <i class="bi bi-calendar"></i>
                            </div>
                            <div class="col">
                                <p class="text-secondary small">Days Remaining: <b><?= $days_remaining ?> day<?= $days_remaining !== 1 ? 's' : '' ?></b></p>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <i class="bi bi-percent"></i>
                            </div>
                            <div class="col">
                                <p class="text-secondary small">ROI: <b><?= $roi ?>%</b></p>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <i class="bi bi-coin"></i>
                            </div>
                            <div class="col">
                                <?php
                                if ($investment_type == "Running") { ?>
                                <p class="text-secondary small">Daily Profit: <b><?= $currency ?><?= number_format((float)$daily_amount, 2, '.', ','); ?></b></p>
                                <?php } else { ?>
                                <p class="text-secondary small">Total Profit: <b><?= $currency ?><?= number_format((float)($daily_amount), 2, '.', ','); ?></b></p>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <i class="bi bi-flag"></i>
                            </div>
                            <div class="col">
                                <p class="text-secondary small">Start Date: <b><?= $start_date_formatted ?></b></p>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <i class="bi bi-flag"></i>
                            </div>
                            <div class="col">
                                <p class="text-secondary small">End Date: <b><?= $end_date_formatted ?></b></p>
                            </div>
                        </div>
                       
                        
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</main>

</div>

<?php include("footer_acc.php"); ?>