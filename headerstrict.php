<?php
include ("minks.php");
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION["user_id"];
    $first_name = $_SESSION["first_name"];
    $last_name = $_SESSION["last_name"];
    $email = $_SESSION["email"];
}
else{ 
    echo "<meta http-equiv=\"refresh\" content=\"0; url=sign_in.php\">";
    exit();
}
?>
<?php
function calculateCurrentInvestmentTotal($amount, $roi, $start_date, $duration_days) {
    $today = new DateTime();
    $start = new DateTime($start_date);

    // Calculate plan end date
    $plan_end = clone $start;
    $plan_end->modify("+{$duration_days} days");

    // === Weekdays for current profit ===
    $profit_end = $today < $start ? clone $start : clone $today;
    if ($profit_end > $plan_end) {
        $profit_end = clone $plan_end;
    }

    $weekdays = 0;
    $tmp_date = clone $start;
    while ($tmp_date <= $profit_end) {
        if ($tmp_date->format('N') < 6) { // Weekdays only
            $weekdays++;
        }
        $tmp_date->modify('+1 day');
    }

    // === Weekdays for max possible profit ===
    $max_weekdays = 0;
    $tmp_date = clone $start;
    while ($tmp_date <= $plan_end) {
        if ($tmp_date->format('N') < 6) {
            $max_weekdays++;
        }
        $tmp_date->modify('+1 day');
    }

    // Status determination
    if ($today < $start) {
        $days_to_start = $today->diff($start)->days;
        $status = "Not Started Yet, starts in {$days_to_start} days";
        $simple_status = "Not Started";
    } elseif ($today >= $plan_end) {
        $days_since_end = $plan_end->diff($today)->days;
        $status = "Matured {$days_since_end} days ago";
        $simple_status = "Matured";
    } else {
        $days_remaining = $today->diff($plan_end)->days;
        $status = "Ongoing, {$days_remaining} days more";
        $simple_status = "Ongoing";
    }

    // Profit calculations
    $profit_per_day = $amount * ($roi / 100);
    $current_profit = $profit_per_day * $weekdays;
    $max_possible_profit = $profit_per_day * $max_weekdays;
    $total = $amount + $current_profit;

    return [
        'simple_status'       => $simple_status,
        'status'              => $status,
        'weekdays_passed'     => $weekdays,
        'current_profit'      => $current_profit,
        'max_possible_profit' => $max_possible_profit,
        'total'               => $total
    ];
}

/*
echo "Status: {$result['status']}<br>";
echo "Weekdays passed: {$result['weekdays_passed']}<br>";
echo "Current profit: {$result['current_profit']}<br>";
echo "Total: {$result['total']}<br>";
*/

    // Get the user balance
    $book_balance = 0;
    $pending_balance = 0;
    $no_plans = 0;
    $today = date("Y-m-d H:i:s");

    $stmt_t = $con -> prepare('SELECT * FROM transactions WHERE user_id = ?');
    $stmt_t -> bind_param('s', $user_id);
    $stmt_t -> execute(); 
    $stmt_t -> store_result(); 
    $stmt_t -> bind_result($t_id, $transaction_id, $user_id, $type, $amount, $status, $start_date, $details, $proof, $account_paid_into, $investment_id, $investment_type, $plan_name, $level, $roi, $duration, $end_date); 
    $numrows_t = $stmt_t -> num_rows();
    if($numrows_t > 0){
        while ($stmt_t -> fetch()) { 
            if($status == "Approved"){
                if($type == "Credit"){
                    $book_balance += $amount;
                }
                if($details == "Investment"){
                    $no_plans ++;
                    //calculate investment amount to add to book balance
                    if($investment_type == "Running"){
                        //function runs
                        $result = calculateCurrentInvestmentTotal($amount, $roi, $start_date, $duration);

                        //get current profit 
                        $book_balance += $result['current_profit'];
                        //all credit balances are added to book balance, so remove the principal 
                        $book_balance -= $amount;

                        
                        // if plan is not matured, add to pending balance
                        if($result['simple_status'] !== "Matured"){
                            $pending_balance += $amount;
                        }
                    }

                    if ($investment_type == "Fixed") {
                        $total_roi1 = $amount * ($roi / 100);
                        $final_amount = $amount + $total_roi1;

                        $today_dt = new DateTime($today);
                        $end_dt = new DateTime($end_date);

                    
                        if ($end_dt <= $today_dt) {
                            // If the plan is matured, add ROI book balance
                            $book_balance += $total_roi1;

                            // Remove the principal amount from book balance
                            $book_balance -= $amount;
                        } else {
                            // If the plan is not matured, add to pending balance
                            $pending_balance += $amount;

                            // Subtract the principal amount from book balance
                            $book_balance -= $amount;
                        }
                    }


                }
                if($type == "Debit"){
                    $book_balance -= $amount;
                }
            }
            if($status == "Pending"){
                if($type == "Debit"){
                    $pending_balance += $amount;
                    $book_balance -= $amount;
                }
            }
        }
    }
    


    
?>
<!DOCTYPE html><html lang="en"><!-- dir="rtl"--><head>
    <!-- Required meta tags  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --adminuiux-content-font: "DM Sans", sans-serif;
            --adminuiux-content-font-weight: 400;
            --adminuiux-title-font: "DM Sans", sans-serif;
            --adminuiux-title-font-weight: 600;
        }
    </style>

<script defer="" src="js/app.js"></script><link href="css/app.css" rel="stylesheet"></head>

<body class="main-bg main-bg-opac main-bg-blur adminuiux-sidebar-fill-white adminuiux-sidebar-boxed  theme-blue roundedui" data-theme="theme-blue" data-sidebarfill="adminuiux-sidebar-fill-white" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" tabindex="0">
  <!-- Pageloader -->
<div class="pageloader">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center text-center h-100">
            <div class="col-12 mb-auto pt-4"></div>
            <div class="col-auto">
                <img src="assets/img/favicon_trans.png" alt="" class="height-60 mb-3">
                <div class="loader10 mb-2 mx-auto"></div>
            </div>
            <div class="col-12 mt-auto pb-4">
                <p class="text-secondary"></p>
            </div>
        </div>
    </div>
</div>
    <!-- standard header -->
<header class="adminuiux-header">
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">

            <!-- main sidebar toggle -->
            <button class="btn btn-link btn-square sidebar-toggler" type="button" onclick="initSidebar()">
                <i class="sidebar-svg" data-feather="menu"></i>
            </button>

            <!-- logo -->
            <a class="navbar-brand" href="index.php">
                <img data-bs-img="light" src="assets/img/logo.png" style="150px; height:56px;" alt="">
                <img data-bs-img="dark" src="assets/img/logo.png" style="150px; height:56px;" alt="">
                
            </a>

            <!-- navigation inline -->
            <div class="collapse navbar-collapse right-in-device justify-content-center" id="header-navbar">
                
            </div>

            <!-- right icons button -->
            <div class="ms-auto">
                <!-- global search toggle -->
               

                <!-- dark mode -->
                <button class="btn btn-link btn-square btnsunmoon btn-link-header" id="btn-layout-modes-dark-page">
                    <i class="sun mx-auto" data-feather="sun"></i>
                    <i class="moon mx-auto" data-feather="moon"></i>
                </button>

                

                <!-- profile dropdown -->
                

                <!-- navigation inline toggler for small screen-->
                <button class="navbar-toggler btn btn-link btn-link-header btn-square btn-icon collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#header-navbar" aria-controls="header-navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i data-feather="more-vertical" class="openbtn"></i>
                    <i data-feather="x" class="closebtn"></i>
                </button>
            </div>
        </div>
    </nav>

   
</header>

      <!-- page wrapper -->
      <div class="adminuiux-wrap">
        <!-- Standard sidebar -->
        <!-- Standard sidebar -->
<div class="adminuiux-sidebar">
    <div class="adminuiux-sidebar-inner">
        <!-- Profile -->
        <div class="px-3 not-iconic mt-3">
            
            
        </div>

        <ul class="nav flex-column menu-active-line">
            <!-- investment sidebar -->
            <li class="nav-item">
                <a href="account" class="nav-link">
                    <i class="menu-icon bi bi-columns-gap"></i>
                    <span class="menu-name">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="deposit" class="nav-link">
                    <i class="menu-icon bi bi-calculator"></i>
                    <span class="menu-name">Deposit</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="invest" class="nav-link">
                    <i class="menu-icon bi bi-bullseye"></i>
                    <span class="menu-name">Invest</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="your_plans" class="nav-link">
                    <i class="menu-icon bi bi-bank"></i>
                    <span class="menu-name">Your Plans</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="withdraw" class="nav-link">
                    <i class="menu-icon bi bi-piggy-bank"></i>
                    <span class="menu-name">Withdraw</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="transactions" class="nav-link">
                    <i class="menu-icon bi bi-files"></i>
                    <span class="menu-name">Transactions</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="referral" class="nav-link">
                    <i class="menu-icon bi bi-people"></i>
                    <span class="menu-name">Referrals</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="settings" class="nav-link">
                    <i class="menu-icon bi bi-layers"></i>
                    <span class="menu-name">Settings</span>
                </a>
            </li>
            
           
           
            
            
            <li class="nav-item">
                <a class="nav-link" href="logout">
                    <i class="menu-icon bi bi-cpu"></i>
                    <span class="menu-name">Logout</span>
                </a>
            </li>

        </ul>
        <div class=" mt-auto "></div>
       
        <ul class="nav flex-column menu-active-line">
            <!-- bottom sidebar menu -->
            <li class="nav-item">
                <a href="contact" class="nav-link">
                    <i class="menu-icon" data-feather="users"></i>
                    <span class="menu-name">Support</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $link ?>" class="nav-link">
                    <i class="menu-icon bi bi-wallet"></i>
                    <span class="menu-name">Main Site</span>
                </a>
            </li>
        </ul>
    </div>
</div>