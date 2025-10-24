<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Choose a Plan</title>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->

    <!-- breadcrumb -->
    <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Investment Plans</li>
                    </ol>
                </nav>
                <h5>Investment Plans</h5>
            </div>
            <div class="col-12 col-sm-auto text-end py-3 py-sm-0">

            </div>
        </div>
    </div>

    <!-- Content  -->
    <div class="container mt-4" id="main-content">

        <div class="row" >
            <div class="col-12 col-md-12 col-xl-10">
                <?php
$active = "Active";
$plans = [];

$stmt = $con->prepare('SELECT plan_id, type, name, level, min_amount, max_amount, roi, duration, picture FROM plans WHERE status = ?');
$stmt->bind_param('s', $active);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all plans into array
while ($row = $result->fetch_assoc()) {
    $plans[] = $row;
}

// Find the highest ROI and shortest duration
$highest_roi = max(array_column($plans, 'roi'));
$shortest_duration = min(array_column($plans, 'duration'));

$count = 0;
foreach ($plans as $plan) {
    $count++;
    $plan_id = $plan['plan_id'];
    $type = $plan['type'];
    $name = $plan['name'];
    $level = $plan['level'];
    $min_amount = $plan['min_amount'];
    $max_amount = $plan['max_amount'];
    $roi = $plan['roi'];
    $duration = $plan['duration'];
    $picture = $plan['picture'];

    // Format max_amount
    if ($max_amount == 0) {
        $max_amount = "Unlimited";
    } else {
        $max_amount = $currency . number_format((float)$max_amount, 2, '.', ',');
    }

    // Assign ribbon (only one)
    $highest_roi_class = "";
    $shortest_duration_class = "";
    $featured_text = "";

    if ($roi == $highest_roi) {
        $highest_roi_class = "border border-theme-1 theme-green position-relative";
        $featured_text = "<span class='ribbon bg-theme-1 position-absolute top-0 end-0 z-index-1'>High ROI!</span>";
    } elseif ($duration == $shortest_duration) {
        $shortest_duration_class = "border border-theme-1 theme-orange position-relative";
        $featured_text = "<span class='ribbon bg-theme-1 position-absolute top-0 end-0 z-index-1'>Short Duration!</span>";
    }

    // Combine any classes if needed (optional)
    $card_classes = trim("$highest_roi_class $shortest_duration_class");
?>

<div class="card adminuiux-card mb-3 <?= $card_classes ?>">
    <?= $featured_text ?>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-12 col-sm-9 col-xxl mb-3 mb-xxl-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar avatar-60 rounded coverimg">
                            <img src="site_img/investments/<?= $picture ?>" alt="">
                        </div>
                    </div>
                    <div class="col">
                        <h6><?= $name ?></h6>
                        <span class="badge badge-sm badge-light text-bg-theme-1"><?= $type ?></span>
                        <span class="badge badge-sm badge-light text-bg-success mx-1">Level <?= $level ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3 col-xxl-auto mb-3 mb-sm-0">
                <p class='text-secondary small'>Max Deposit: <b><?= $max_amount ?></b></p>
                <p class='text-secondary small'>Min Deposit: <b><?= $currency ?><?= number_format((float)$min_amount, 2, '.', ','); ?></b></p>
            </div>
            <div class="col-12 col-md-9 col-xxl-4 mb-3 mb-md-0">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto text-start">
                                <h6 class="mb-1"><?= $roi ?>%
                                    <small>
                                        <span class="badge badge-sm badge-light text-bg-success mx-1 fw-normal"><?php if($type == "Fixed"){echo"FIXED";} else{echo"DAILY";} ?> ROI</span>
                                    </small>
                                </h6>
                                <p class="text-secondary small"></p>
                            </div>
                            <div class="col-auto text-end">
                                <p class="text-secondary small" style='font-size:13px;'>in <?= $duration ?> days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-theme" href='invest_details?u=<?= $plan_id ?>'>Invest NOW</a>
            </div>
        </div>
    </div>
</div>

<?php
} // end foreach
?>
                
            </div>
        </div>
    </div>
</main>

</div>
<?php include("footer_acc.php"); ?>