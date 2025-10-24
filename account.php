<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Overview</title>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->
    <!-- breadcrumb -->
    <div class="container-fluid mt-4">

        <div class="row gx-3 align-items-center">
            <div class="col-6 col-md-6 col-sm"> 
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                <div class="breadcrumb-ite activ"style='font-size:18px;'>
                    Hello <?= $first_name ?>!</div>
                    <b>Account Home</b>
                </div>
            </div>
            <div class="col-6 col-md-6 col-sm-auto text-end py-3 py-sm-0">
                <?php 
                $ip = $_SERVER['REMOTE_ADDR'];
                echo "Current IP Logged: <b>$ip</b>";
                ?>
            </div>
        </div>

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
    <div class="tradingview-widget-container__widget"></div>
    <div class="tradingview-widget-copyright"><a href="#" rel="noopener" target="_blank"><span
                class="blue-text">Markets today</span></a> </div>
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js"
        async>
    {
        "symbols": [{
                "proName": "FOREXCOM:SPXUSD",
                "title": "S&P 500"
            },
            {
                "proName": "FOREXCOM:NSXUSD",
                "title": "US 100"
            },
            {
                "proName": "FX_IDC:EURUSD",
                "title": "EUR/USD"
            },
            {
                "proName": "BITSTAMP:BTCUSD",
                "title": "Bitcoin"
            },
            {
                "proName": "BITSTAMP:ETHUSD",
                "title": "Ethereum"
            }
        ],
        "showSymbolLogo": true,
        "colorTheme": "light",
        "isTransparent": false,
        "displayMode": "adaptive",
        "locale": "en"
    }
    </script>
</div>
<!-- TradingView Widget END -->

<div class="container mt-4" id="main-content">
    <div class="row">
        <!-- balance -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card adminuiux-card bg-theme-1">
                <div class="card-body z-index-1">
                    <div class="row gx-2 align-items-center mb-4">
                        <div class="col-auto py-1">
                        <div class="avatar avatar-60 bg-white-opacity rounded" style='font-size:38px;'><i class="fa-solid fa-naira-sign"></i></div>
                        </div>
                        <div class="col px-0"></div>
                    </div>
                    <h3><?php echo $currency; ?>
                    <?php echo number_format((float)$book_balance, 2, '.', ','); ?></h3>
                    <h5 class="opacity-75 fw-normal mb-1">Balance</h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card adminuiux-card bg-theme-1">
                <div class="card-body z-index-1">
                    <div class="row gx-2 align-items-center mb-4">
                        <div class="col-auto py-1">
                            <div class="avatar avatar-60 bg-white-opacity rounded" style='font-size:38px;'><i class="fa-solid fa-hourglass"></i></div>
                        </div>
                        <div class="col px-0"></div>
                    </div>
                    <h3><?php echo $currency; ?>
                    <?php echo number_format((float)$pending_balance, 2, '.', ','); ?></h3>
                    <h5 class="opacity-75 fw-normal mb-1">Pending Balance</h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card adminuiux-card bg-theme-1">
                <div class="card-body z-index-1">
                    <div class="row gx-2 align-items-center mb-4">
                        <div class="col-auto py-1">
                        <div class="avatar avatar-60 bg-white-opacity rounded" style='font-size:38px;'><i class="fa-solid fa-book"></i></div>
                        </div>
                        <div class="col px-0"></div>
                    </div>
                    <h3><?= $no_plans ?></h3>
                    <h5 class="opacity-75 fw-normal mb-1">Investment Plans</h5>
                </div>
            </div>
        </div> 
    </div>

     <h5>Recent Plans </h5>
    <div class="row">
            <?php 
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
                                    $progress_id = "circleprogressgreen" . $transaction_id; // or use $loop_index
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

    <h5>Recent Transactions </h5>
    <div class="table-responsive" style='border-radius:20px;'>
        <table class="table">

            <?php
            //user wallet fundings
            $ten = 10;
            $stmt_wallet1 = $con -> prepare('SELECT * FROM transactions WHERE user_id = ? LIMIT ?');
            $stmt_wallet1 -> bind_param('si', $user_id, $ten);
            $stmt_wallet1 -> execute(); 
            $stmt_wallet1 -> store_result(); 
            $stmt_wallet1 -> bind_result($t_id_w, $transaction_id_w, $user_id, $type_w, $amount_w, $status_w, $start_date_w_raw, $details_w, $proof_w, $account_paid_into_w, $investment_id_w, $investment_type_w, $plan_name_w, $level_w, $roi_w, $duration_w, $end_date_w); 
            $numrows_wallet1 = $stmt_wallet1 -> num_rows();
            if($numrows_wallet1 > 0){  
                echo'
                <thead>
                    <tr class="outside">
                        <th scope="col">Date</th>
                        <th scope="col">Description</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                ';
                
                while ($stmt_wallet1 -> fetch()) {
                    //status
                    if($status_w == "Approved"){$status_color = "forestgreen";}
                    elseif($status_w == "Pending"){$status_color = "crimson";}
                    else{$status_color="";}

                    //if debit update description
                    if($type_w == "Debit"){
                        $details_w = "Withdrawal";
                    }

                    //format date
                    $date1=date_create("$start_date_w_raw");
                    $start_date_w = date_format($date1,"D,dS M, Y");
            ?>
                    <tr class='inside'>
                        <td scope="row" style='font-size:14px;'><?= $start_date_w; ?></td>
                        <td style='font-size:14px;'><?= $details_w; ?></td>
                        <td style='font-size:14px;'><?= $transaction_id_w; ?></td>
                        <td style='font-size:14px;'><?= $currency; ?><?php echo number_format((float)$amount_w, 2, '.', ','); ?>
                        </td>
                        <td style='font-size:14px;'><?= $type_w; ?></td>
                        <td style='color:<?php echo $status_color; ?>'><?= $status_w; ?></td>
                    </tr>

                    <?php 
                }
            }else{echo"You do not have any transactions at this time. <br><br>";}
            ?>




                    </tbody>
                </table>
    </div>
    
        </div>
    </main>
</div>


            <!-- page footer -->
            <!-- standard footer -->
<?php include("footer_acc.php"); ?>
<!-- Page Level js -->
<script src="js/investment-wallet.js"></script>

                    

</body></html>