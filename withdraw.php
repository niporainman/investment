<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; 
session_start();
include("minks.php");
$page_name = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION["user_id"];
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$email = $_SESSION["email"];
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json'); // Always return JSON
    ini_set('display_errors', 0); // Don't leak PHP warnings in JSON
    error_reporting(E_ALL);

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

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception("Invalid request method.");
        }

        // Validate required fields
        if (empty($_POST['amount'])) {
            throw new \Exception("Please fill in all required fields.");
        }

        $amount = str_replace(',', '', $_POST['amount']);
        $password = $_POST['password'];

        if(empty($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new \Exception("Please enter a valid amount to withdraw.");
        }
        
        //get book balance 
        $book_balance = 0;
        $pending_balance = 0;
        $no_plans = 0;
        $today = date("Y-m-d H:i:s");

        $stmt_tt = $con -> prepare('SELECT * FROM transactions WHERE user_id = ?');
        $stmt_tt -> bind_param('s', $user_id);
        $stmt_tt -> execute(); 
        $stmt_tt -> store_result(); 
        $stmt_tt -> bind_result($t_id_db, $transaction_id_db, $user_id, $type_db, $amount_db, $status_db, $start_date_db, $details_db, $proof_db, $account_paid_into_db, $investment_id_db, $investment_type_db, $plan_name_db, $level_db, $roi_db, $duration_db, $end_date_db); 
        $numrows_tt = $stmt_tt -> num_rows();
        if($numrows_tt > 0){
            while ($stmt_tt -> fetch()) { 
                if($status_db == "Approved"){
                    if($type_db == "Credit"){
                        $book_balance += $amount_db;
                    }
                    if($details_db == "Investment"){
                        $no_plans ++;
                        //calculate investment amount to add to book balance
                        if($investment_type_db == "Running"){
                            //function runs
                            $result_db = calculateCurrentInvestmentTotal($amount_db, $roi_db, $start_date_db, $duration_db);

                            //get current profit 
                            $book_balance += $result_db['current_profit'];
                            //all credit balances are added to book balance, so remove the principal 
                            $book_balance -= $amount_db;

                            
                            // if plan is not matured, add to pending balance
                            if($result_db['simple_status'] !== "Matured"){
                                $pending_balance += $amount_db;
                            }
                        }

                        if ($investment_type_db == "Fixed") {
                            $total_roi1 = $amount_db * ($roi_db / 100);
                            $final_amount = $amount_db + $total_roi1;

                            $today_dt = new DateTime($today);
                            $end_dt = new DateTime($end_date_db);

                        
                            if ($end_dt <= $today_dt) {
                                // If the plan is matured, add ROI book balance
                                $book_balance += $total_roi1;

                                // Remove the principal amount from book balance
                                $book_balance -= $amount_db;
                            } else {
                                // If the plan is not matured, add to pending balance
                                $pending_balance += $amount_db;

                                // Subtract the principal amount from book balance
                                $book_balance -= $amount_db;
                            }
                        }


                    }
                    if($type_db == "Debit"){
                        $book_balance -= $amount_db;
                        //check if there has been a withdrawal today 
                        $today_dtb = new DateTime($today);
                        $start_date_dtb = new DateTime($start_date_db);
                        if($start_date_dtb->format('Y-m-d') === $today_dtb->format('Y-m-d')) {
                            throw new \Exception("You have already made a withdrawal today. Please try again tomorrow.");
                        }
                           
                    }
                }
                if($status_db == "Pending"){
                    if($type_db == "Debit"){
                        $pending_balance += $amount_db;
                        $book_balance -= $amount_db;
                         //check if there has been a withdrawal today 
                        $today_dtb = new DateTime($today);
                        $start_date_dtb = new DateTime($start_date_db);
                        if($start_date_dtb->format('Y-m-d') === $today_dtb->format('Y-m-d')) {
                            throw new \Exception("You have already made a withdrawal today. Please try again tomorrow.");
                        }
                    }
                }
            }
        }

        if($amount > $book_balance) {
            throw new \Exception("Amount exceeds your book balance of $currency$book_balance.");
        }

        $stmt_u = $con -> prepare('SELECT password,bank_name, account_number FROM users WHERE user_id = ?');
        $stmt_u -> bind_param('s', $user_id);
        $stmt_u -> execute();
        $stmt_u -> store_result();
        $stmt_u -> bind_result($user_password,$user_bank_name1, $user_account_number1);
        $numrows_u = $stmt_u -> num_rows();
        if($numrows_u > 0){
            while ($stmt_u -> fetch()) {}
        }

        if(empty($user_bank_name1) || empty($user_account_number1)){
            throw new \Exception("Please set your bank details in your profile before making a withdrawal.");
        }

        if(empty($password) || ($password !== $user_password) ) {
            throw new \Exception("Incorrect password, please try again.");
        }

        $db_id=0;
        $transaction_id = substr(md5(rand()), 0, 10);
        $debit_word = "Debit";
        $pending_word = "Pending";
        $start_date = date("Y-m-d H:i:s");
        $empty = "";
        $stmt_deposit = $con -> prepare('INSERT INTO transactions VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt_deposit -> bind_param('issssssssssssssss', $db_id, $transaction_id, $user_id, $debit_word, $amount, $pending_word, $start_date, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty);
        $stmt_deposit -> execute();

        if ($stmt_deposit->affected_rows < 1) {
            throw new \Exception("Database insert failed.");
        }

        //new book balance after withdrawal
        $book_balance_after = $book_balance - $amount;

        // Prepare email
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        require 'PHPMailer/src/Exception.php';

        $mail = new PHPMailer();
        $mail->Host = "localhost";
        $mail->Port = 25;
        $mail->Username = "$no_reply_email";
        $mail->Password = "$no_reply_password";

        $mail->setFrom("$company_email", "$company_name");
        $mail->addReplyTo($company_email, $company_name);
        $mail->Subject = "$first_name $last_name made a withdrawal request";

        $message = "
        <div style='font-family:Calibri;background:cornflowerblue;padding:10px;border-radius:5px;'>
            Hello Admin,<br/><br/>
            This is to notify you that a withdrawal request has been made. 
            <br/><br/>
            Details are as follows:<br/>
            Depositor - $first_name $last_name<br/>
            Amount - $currency$amount<br/>
            Date - $start_date<br/>
            Transaction ID - $transaction_id<br/>
            To be Paid Into - $user_bank_name1 - $user_account_number1<br/>
            <br/>
            Log in to approve or reject this withdrawal.<br/>
            Regards,<br/>
            $company_name Admin Notification System<br/>
            $email_logo
        </div>
        ";

        $mail->msgHTML("<html><body>$message</body></html>");
        $mail->addAddress("$company_email", "$company_name");

        if (!$mail->send()) {
            echo json_encode([
                //"status" => "warning",
                //"message" => "Deposit recorded successfully, but email could not be sent. Error: " . $mail->ErrorInfo
                "status" => "success",
                "message" => "Withdrawal request submitted successfully. Please wait for approval.",
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
            "message" => "Withdrawal request submitted successfully. Please wait for approval.",
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
?>

<?php
$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Withdrawals</title>
<?php
//get user account details from users table 
$stmt_ua = $con -> prepare('SELECT bank_name, account_number FROM users WHERE user_id = ?');
$stmt_ua -> bind_param('s', $user_id);
$stmt_ua -> execute();
$stmt_ua -> store_result();
$stmt_ua -> bind_result($user_bank_name, $user_account_number);
$numrows_ua = $stmt_ua -> num_rows();
if($numrows_ua > 0){
    while ($stmt_ua -> fetch()) {}
}
?>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->
    <!-- breadcrumb -->
   <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Withdrawals</li>
                    </ol>
                </nav>
                <h5>Withdraw your funds</h5>
            </div>
            <div class="col-12 col-sm-auto text-end py-3 py-sm-0">

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
                <h5>Fill in the amount you would like to withdraw</h5>
                <p>Your current balance is <b id="bookBalance"><?= $currency ?><?= number_format((float)$book_balance, 2, '.', ','); ?></b></p>
            </div>
            <form id='theForm' method="post" enctype='multipart/form-data' class="card-body" data-url="withdraw">
                <input type="hidden" name="ajax" value="1">
                <div class="row mb-2">
                   
                    <div class="col-12 col-md-12 col-xl-12 mb-3">
                        <div class="form-floating">
                            <input type="password" class="form-control" name='password' id="password" placeholder="Password" required>
                            <label for="password">Password</label>
                             <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <!-- Buttons Row -->
                    <div class="col-12 col-md-12 col-xl-12 mb-3">
                        <div class="btn-group flex-wrap amountbtns" role="group" aria-label="Amount buttons">
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="1000">1,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="3000">3,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="5000">5,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="20000">20,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="40000">40,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="100000">100,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="200000">200,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="600000">600,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="2000000">2,000,000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" data-amount="10000000">10,000,000</button>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-6 mb-3">
                        <div class="form-floating"> 
                            <input type="text" class="form-control" name="amount" id="amount"
                                placeholder="Amount"
                                data-min="1000"
                                data-max="<?= $book_balance ?>"
                                data-balance="<?= $book_balance ?>"
                                required readonly>
                            <label for="amount">Amount (<?= $currency ?>)</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                   
                     
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <h5></h5>
                        <?php if(empty($user_bank_name) || empty($user_account_number)){ ?>
                        <p class="text-danger small">Please set your bank details <a href="settings">here</a> before making a withdrawal.</p>
                        <?php } else { ?>
                        <p class="text-secondary small">Amount will be credited to <?= $user_bank_name ?> - <?= $user_account_number ?></p>
                        <?php } ?>
                    </div>
                    <div class="col-auto">
                        <button type='submit' class="btn btn-theme">Submit</button>
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
(function () {
  const amountInput = document.getElementById('amount');
  const form = document.getElementById('theForm');
  const errorDiv = amountInput.nextElementSibling.nextElementSibling; // .invalid-feedback
  const buttons = document.querySelectorAll('.amount-btn');

  function stripCommas(s) {
    return (s || '').toString().replace(/,/g, '');
  }

  function setInvalid(msg) {
    errorDiv.textContent = msg || 'Enter a valid amount';
    amountInput.classList.add('is-invalid');
  }

  function clearInvalid() {
    errorDiv.textContent = '';
    amountInput.classList.remove('is-invalid');
  }

  function validateAndFormatAmount() {
    const raw = stripCommas(amountInput.value);
    const value = Number(raw);
    const max = Number(amountInput.dataset.max);        // book balance (server-truth)
    const balance = Number(amountInput.dataset.balance); // available balance (if different)
    const min = Number(amountInput.dataset.min);         // optional, only enforced if present
    

    if (!Number.isFinite(value) || value <= 0) {
      setInvalid('Enter a valid amount');
      return false;
    }

    if (!Number.isNaN(min) && value < min) {
      setInvalid(`Minimum amount is ${min.toLocaleString()}`);
      return false;
    }

    // Prefer the stricter of the two (if both are numbers)
    if (!Number.isNaN(max) && value > max) {
      setInvalid(`Amount exceeds your book balancee of ${max.toLocaleString()}`);
      return false;
    }
    if (!Number.isNaN(balance) && value > balance) {
      setInvalid(`Amount exceeds your available balance of ${balance.toLocaleString()}`);
      return false;
    }

    // If OK, format with commas
    amountInput.value = value.toLocaleString();
    clearInvalid();
    return true;
  }

  // Validate on user edits (programmatic or manual)
  amountInput.addEventListener('input', validateAndFormatAmount);

  // Amount buttons -> set value, validate, highlight active
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      amountInput.value = btn.getAttribute('data-amount');
      validateAndFormatAmount();

      buttons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // HARD GUARD: block submission if invalid
  form.addEventListener('submit', (e) => {
    // force one more validation right before submit
    const ok = validateAndFormatAmount();
    if (!ok) {
      e.preventDefault();
      e.stopPropagation(); // prevents your delegated jQuery handler from firing
      document.getElementById('formMessage').innerHTML =
        '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">Please fix the amount before submitting.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
  });
})();
</script>

<style>
  .amount-btn.active {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
  }
</style>

        
<?php include("footer_acc.php"); ?>
<script src="js/dropzone.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='ajax.js'></script>
</body>
</html>