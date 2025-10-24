<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; 
session_start();
include("minks.php");
$page_name = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION["user_id"];
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$email = $_SESSION["email"];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    ob_clean(); // Clear anything PHP buffered before
    header('Content-Type: application/json'); // Always return JSON
    ini_set('display_errors', 0); // Don't leak PHP warnings in JSON
    error_reporting(E_ALL);

function calculateCurrentInvestmentTotal($amount, $roi, $start_date, $duration_days) {
    $today = new DateTime();
    $start = new DateTime($start_date);
    
    // Calculate plan end date
    $plan_end = clone $start;
    $plan_end->modify("+{$duration_days} days");

    // Initialize
    $weekdays = 0;
    $status = "";
    $simple_status = "";

    if ($today < $start) {
        // Not started
        $days_to_start = $today->diff($start)->days;
        $status = "Not Started Yet, starts in {$days_to_start} days";
        $simple_status = "Not Started";
        $weekdays = 0;

    } elseif ($today >= $plan_end) {
        // Matured
        $days_since_end = $plan_end->diff($today)->days;
        $status = "Matured {$days_since_end} days ago";
        $simple_status = "Matured";

        $tmp_start = clone $start;
        while ($tmp_start <= $plan_end) {
            if ($tmp_start->format('N') < 6) {
                $weekdays++;
            }
            $tmp_start->modify('+1 day');
        }

    } else {
        // Ongoing
        $days_remaining = $today->diff($plan_end)->days;
        $status = "Ongoing, {$days_remaining} days more";
        $simple_status = "Ongoing";

        $tmp_start = clone $start;
        while ($tmp_start <= $today) {
            if ($tmp_start->format('N') < 6) {
                $weekdays++;
            }
            $tmp_start->modify('+1 day');
        }
    }

    // Calculate profits
    $profit_per_day = $amount * ($roi / 100);
    $current_profit = $profit_per_day * $weekdays;
    $total = $amount + $current_profit;

    return [
        'simple_status' => $simple_status,
        'status' => $status,
        'weekdays_passed' => $weekdays,
        'current_profit' => $current_profit,
        'total' => $total
    ];
}
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception("Invalid request method.");
        }

        // Validate required fields
        if (empty($_POST['amount'])) {
            throw new \Exception("Please fill in all required fields.");
        }

        $amount = str_replace(',', '', $_POST['amount']);
        //get the data from db and not form 
        $plan_id = $_GET['u'];
        $stmt = $con -> prepare('SELECT type, name, level, min_amount, max_amount, roi, duration, picture FROM plans WHERE plan_id = ?');
        $stmt -> bind_param('i', $plan_id);
        $stmt -> execute(); 
        $stmt -> store_result(); 
        $stmt -> bind_result($type, $name, $level, $min_amount, $max_amount, $roi, $duration, $picture); 
        $numrows = $stmt -> num_rows();
        if($numrows > 0){
            while ($stmt -> fetch()) { }
        }
       
        //book balance 
        $book_balance = 0;
        $pending_balance = 0;
        $no_plans = 0;
        $today = date("Y-m-d H:i:s");

        $stmt_t = $con -> prepare('SELECT * FROM transactions WHERE user_id = ?');
        $stmt_t -> bind_param('s', $user_id);
        $stmt_t -> execute(); 
        $stmt_t -> store_result(); 
        $stmt_t -> bind_result($t_id, $transaction_id, $user_id, $type_db, $amount_db, $status_db, $start_date_db, $details_db, $proof_db, $account_paid_into_db, $investment_id_db, $investment_type_db, $plan_name_db, $level_db, $roi_db, $duration_db, $end_date_db); 
        $numrows_t = $stmt_t -> num_rows();
        if($numrows_t > 0){
            while ($stmt_t -> fetch()) { 
                if($status_db == "Approved"){
                    if($type_db == "Credit"){
                        $book_balance += $amount_db;
                    }
                    if($details_db == "Investment"){
                        $no_plans ++;
                        //calculate investment amount to add to book balance
                        if($investment_type_db == "Running"){
                            $result = calculateCurrentInvestmentTotal($amount_db, $roi_db, $start_date_db, $duration_db);
                            if($result['simple_status'] == "Matured"){
                                $book_balance += $result['total'];
                            }
                            else{
                                $pending_balance += $result['total'];
                                $book_balance -= $amount_db;
                            }
                        }

                        if ($investment_type_db == "Fixed") {
                            $total_roi1 = $amount_db * ($roi_db / 100);
                            $final_amount = $amount_db + $total_roi1;

                            $today_dt = new DateTime($today);
                            $end_dt = new DateTime($end_date_db);

                            if ($end_dt <= $today_dt) {
                                $book_balance += $final_amount;
                            } else {
                                $pending_balance += $amount_db;
                                $book_balance -= $amount_db;
                            }
                        }


                    }
                    if($type_db == "Debit"){
                        $book_balance -= $amount_db;
                    }
                }
                if($status_db == "Pending"){
                    if($type_db == "Debit"){
                        $pending_balance += $amount_db;
                        $book_balance -= $amount_db;
                    }
                }
            }
        }
        

        //check that amount is equal to or less than book balance and greater than or equal to min amount
        if($amount < $min_amount){
            throw new \Exception("Minimum amount for this plan is " . $currency . number_format((float)$min_amount, 2, '.', ',') . ".");
        } elseif($amount > $book_balance) {
            throw new \Exception("Amount exceeds your book balance of " . $currency . number_format((float)$book_balance, 2, '.', ',') . ".");
        }

        $db_id=0;
        $transaction_id = substr(md5(rand()), 0, 10);
        $approved_word = "Approved";
        $start_date = date("Y-m-d H:i:s");
        $end_date = date("Y-m-d H:i:s", strtotime("+$duration days"));
        $details = "Investment";
        $empty = "";
        $stmt_deposit = $con -> prepare('INSERT INTO transactions VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt_deposit -> bind_param('issssssssssssssss', $db_id, $transaction_id, $user_id, $empty, $amount, $approved_word, $start_date, $details, $empty, $empty, $plan_id, $type, $name, $level, $roi, $duration, $end_date);
        $stmt_deposit -> execute();

        if ($stmt_deposit->affected_rows < 1) {
            throw new \Exception("Database insert failed.");
        }

        //new book balance after deposit
        $book_balance_after = $book_balance - $amount;

        $message = "
        <div style='font-family:Calibri;background:cornflowerblue;padding:10px;border-radius:5px;'>
            Hello Admin,<br/><br/>
            This is to notify you that an investment has been made. 
            <br/><br/>
            Details are as follows:<br/>
            Investor - $first_name $last_name<br/>
            Amount - $currency$amount<br/>
            Date - $start_date<br/>
            Transaction ID - $transaction_id<br/>
            Plan name - $name - LEVEL $level<br/>
            <br/>
            You do not need to take any action at this time.<br/>
            Regards,<br/>
            $company_name Admin Notification System<br/>
            $email_logo
        </div>
        ";

        //send email to admin 
        require 'PHPMailer/src/PHPMailer.php'; 
        require 'PHPMailer/src/SMTP.php'; 
        require 'PHPMailer/src/Exception.php';

        $mail = new PHPMailer();

        //$mail->IsSMTP(); // telling the class to use SMTP
        //$mail->SMTPAuth = true; // enable SMTP authentication
        $mail->Host = "localhost"; // sets the SMTP server
        $mail->Port = 25; // set the SMTP port for the GMAIL server
        $mail->Username = "$no_reply_email"; // SMTP account username
        $mail->Password = "$no_reply_password"; // SMTP account password

        $mail->SetFrom("$company_email", "$company_name");//Use a fixed address in your own domain as the from address
        $mail->AddReplyTo("$email","$email"); //Put the submitter's address in a reply-to header
        $mail->Subject = "$first_name $last_name made an investment";
        $mail->MsgHTML("<html><body>$message</body></html>");
        $mail->AddAddress("$company_email", "$company_name");//Send the message to yourself, or whoever should receive contact for submissions
        //end of email to admin

        if (!$mail->send()) {
            echo json_encode([
                //"status" => "warning",
                //"message" => "Deposit recorded successfully, but email could not be sent. Error: " . $mail->ErrorInfo
                "status" => "success",
                "message" => "Investment plan successfully activated",
                "updates" => [
                    "#bookBalance" => '₦' . number_format((float)$book_balance_after, 2, '.', ','),
                    "#amount" => [ "data-balance" => $book_balance_after ]
                ]
            ]);
            exit;
        }
        

        // Success
        echo json_encode([
            "status" => "success",
            "message" => "Investment plan successfully activated",
            "updates" => [
                "#bookBalance" => '₦' . number_format((float)$book_balance_after, 2, '.', ','),
                "#amount" => [ "data-balance" => $book_balance_after ]
            ]
        ]);
        exit;

    } catch (\Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
        exit;
    }
} // End of POST check

$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Invest</title>
<?php
//get plan details
if(isset($_GET['u'])){
    $plan_id = $_GET['u'];
    $stmt = $con -> prepare('SELECT type, name, level, min_amount, max_amount, roi, duration, picture FROM plans WHERE plan_id = ?');
    $stmt -> bind_param('i', $plan_id);
    $stmt -> execute(); 
    $stmt -> store_result(); 
    $stmt -> bind_result($type, $name, $level, $min_amount, $max_amount, $roi, $duration, $picture); 
    $numrows = $stmt -> num_rows();
    if($numrows > 0){
        $stmt -> fetch();
    } else {
        $_SESSION["action"] = "true";
        $message="Invalid plan selected.";
        echo "<meta http-equiv=\"refresh\" content=\"0; url=failure.php?u=invest.php&m=$message\">";
        exit();
    }
} else {
    $_SESSION["action"] = "true";
    $message="Invalid plan selected.";
    echo "<meta http-equiv=\"refresh\" content=\"0; url=failure.php?u=invest.php&m=$message\">";
    exit();
}

//set max to absurdly high value if it is 0, because it means unlimited
if($max_amount == 0){
    $max_amount = PHP_INT_MAX;
}

//do they have enough money for this plan?
/*
if($book_balance < $min_amount){
    $_SESSION["action"] = "true";
    $message="You do not have enough funds in your wallet to invest in this plan.";
    echo "<meta http-equiv=\"refresh\" content=\"0; url=failure.php?u=deposit.php&m=$message\">";
    exit();
}
*/

//ensure that that max amount is not exceeded
$final_max = min($book_balance, $max_amount);
?>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->
    <!-- breadcrumb -->
    <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account">Home</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Invest in <?= $name ?></li>
                    </ol>
                </nav>
                <h5>Start earning ROI now</h5>
            </div>
        </div>
    </div>

    <!-- content -->
    <div class="container mt-4" id="main-content">
        <div class='row'>
            <div class="col-12 ">
                <div class="row">
    <div class="col-12 col-lg-8 mb-4">
        <!-- create fixed deposit -->
        <div class="card adminuiux-card">
            <div class="card-header">
                <h3><?= $name ?> LEVEL <?= $level ?></h3>
                <h5>Enter the amount you want to invest</h5>
                <p>Your current balance is <b id="bookBalance"><?= $currency ?><?= number_format((float)$book_balance, 2, '.', ','); ?></b></p>
               
            </div>
            <form id='theForm' method="post" enctype='multipart/form-data' class="card-body" data-url="invest_details?u=<?= $plan_id ?>">
                <input type="hidden" name="ajax" value="1">
                <input type="hidden" name="min_amount" value="<?= $min_amount ?>">
                <input type="hidden" name="book_balance" value="<?= $book_balance ?>">
                <input type="hidden" name="duration" value='<?= $duration ?>'>
                <input type="hidden" name="plan_id" value="<?= $plan_id ?>">
                <input type="hidden" name="type" value="<?= $type ?>">
                <input type="hidden" name="name" value="<?= $name ?>">
                <input type="hidden" name="level" value="<?= $level ?>">
                <input type="hidden" name="roi" value="<?= $roi ?>">
                
                <div class="row mb-2">
                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name='amount' id="amount" placeholder="Amount" value="" data-min="<?= $min_amount ?>" data-max="<?= $final_max ?>" data-balance="<?= $book_balance ?>" required>
                            <label for="amount">Amount (<?= $currency ?>)</label>
                             <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
   
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <h5></h5>
                        <p class="text-secondary small"> <input type="checkbox" name="" style="position:relative; top:2px;" required> I agree that funds from my <?= $company_name; ?> wallet be deducted to invest in this plan</p>
                    </div>
                    <div class="col-auto">
                        <button type='submit' class="btn btn-theme">INVEST</button>
                    </div>
                </div>
            </form>
            <div id="formMessage" style="margin-top:10px;"></div>
        </div>
    </div>
   
                </div>
            </div>
        </div>
    </div>
</main>

            </div>
<script>
document.getElementById('amount').addEventListener('input', function () {
    const input = this;
    const raw = input.value.replace(/,/g, ''); // Remove commas
    const value = parseFloat(raw);
    const max = parseFloat(input.dataset.max);
    const min = parseFloat(input.dataset.min);
    const balance = parseFloat(input.dataset.balance);
    const errorDiv = input.nextElementSibling.nextElementSibling; // .invalid-feedback

    if (isNaN(value)) {
        errorDiv.textContent = "Enter a valid amount";
        input.classList.add("is-invalid");
        return;
    }

     if (value < min) {
        errorDiv.textContent = `Mininum amount is ${min.toLocaleString()}`;
        input.classList.add("is-invalid");
        return;
    }

    if (value > max) {
        errorDiv.textContent = `Amount exceeds your book balance of ${max.toLocaleString()}`;
        input.classList.add("is-invalid");
    } else if (value > balance) {
        errorDiv.textContent = `Amount exceeds your available balance of ${balance.toLocaleString()}`;
        input.classList.add("is-invalid");
    } else {
        input.classList.remove("is-invalid");
        errorDiv.textContent = "";
    }

    // Optional: Re-format input with commas
    input.value = value.toLocaleString();
});
</script>

<script>
//format the amount to display commmas
document.addEventListener('DOMContentLoaded', function () {
    const amountInput = document.getElementById('amount');

    amountInput.addEventListener('input', function (e) {
        let value = e.target.value;

        // Remove everything except numbers
        value = value.replace(/[^0-9]/g, '');

        // Format with commas
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        e.target.value = value;
    });

    // Optional: prevent pasting non-numeric text
    amountInput.addEventListener('paste', function (e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (/[^0-9]/.test(pasted)) {
            e.preventDefault();
        }
    });
});
</script>
        
<?php include("footer_acc.php"); ?>
<script src="js/dropzone.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='ajax.js'></script>
</body>
</html>