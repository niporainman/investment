<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']);
$page_title = "Choose an Investment Plan";
$page_header = "21c721c626.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
<!-- Pricing Section Start -->
        <div class="pt-130 pb-100">
            <div class="container">
                <div class="row justify-content-center">
                    <?php
                    $active = "Active"; $count = 0;
                    $stmt = $con -> prepare('SELECT type, name, level, min_amount, max_amount, roi, duration FROM plans WHERE status = ?');
                    $stmt -> bind_param('s', $active);
                    $stmt -> execute(); 
                    $stmt -> store_result(); 
                    $stmt -> bind_result($type, $name, $level, $min_amount, $max_amount, $roi, $duration); 
                    $numrows = $stmt -> num_rows();
                    if($numrows > 0){
                        while ($stmt -> fetch()) { 
                            $count++;
                            if ($count % 2 == 0) {$featured = "featured";} else {$featured = "";}
                            if($max_amount == 0){
                                $max_amount = "Unlimited";
                            } else {
                                $max_amount = $currency . number_format((float)$max_amount, 2, '.', ',');
                            }
                    ?>
                    <div class="col-xl-4 col-md-6">
                        <div class="pricing-card <?= $featured ?> position-relative style-two transition index-1 mb-30">
                            <h2 class="price-tag transition"><?= $roi ?>%<span class="text-para f-primary fs-15 transition"><?php if($type == "Running"){echo" /daily (Excluding Weekends)";} ?></span></h2>
                            <h5 class="fw-semibold transition"><?= $name ?></h5>
                            <p class="transition">LEVEL <?= $level ?></p>
                            <h6 class="fs-16 f-primary transition">Plan Details</h6>
                            <ul class="pricing-features list-unstyle">
                                <li class="position-relative transition"><img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 transition"><b>Min Deposit</b> - <?= $currency ?><?= number_format((float)$min_amount, 2, '.', ','); ?></li>
                                <li class="position-relative transition"><img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 transition"><b>Max Deposit</b> - <?= $max_amount ?></li>
                                <li class="position-relative transition"><img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 transition">Duration - <?= $duration ?> Days</li>
                                <li class="position-relative transition"><img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 transition">High Yield</li>
                                <li class="position-relative transitione"><img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 transition">Investment Dashboard Available</li>
                                <li class="position-relative transition"><img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 transition">24/7 Customer Support</li>

                            </ul>
                            <a href="account" class="btn style-one d-block w-100">Get Started Now</a>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
        <!-- Pricing Section End -->
<?php include("footer.php"); ?>