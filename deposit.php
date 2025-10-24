<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; 
session_start();
include("minks.php");
$page_name = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION["user_id"];
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$email = $_SESSION["email"];

//get bank account details
$bn = "Bank Name";
$stmt_s = $con -> prepare('SELECT value FROM settings WHERE name = ?');
$stmt_s -> bind_param('s', $bn);
$stmt_s -> execute(); 
$stmt_s -> store_result(); 
$stmt_s -> bind_result($official_bank_name); 
$numrows_s = $stmt_s -> num_rows();
if($numrows_s > 0){ 
    while ($stmt_s -> fetch()) {}
}

$ba = "Bank Account";
$stmt_s1 = $con -> prepare('SELECT value FROM settings WHERE name = ?');
$stmt_s1 -> bind_param('s', $ba);
$stmt_s1 -> execute(); 
$stmt_s1 -> store_result(); 
$stmt_s1 -> bind_result($official_bank_account); 
$numrows_s1 = $stmt_s1 -> num_rows();
if($numrows_s1 > 0){ 
    while ($stmt_s1 -> fetch()) {}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json'); // Always return JSON
    ini_set('display_errors', 0); // Don't leak PHP warnings in JSON
    error_reporting(E_ALL);

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception("Invalid request method.");
        }

        // Validate required fields
        if (empty($_POST['amount']) || empty($_FILES['file'])) {
            throw new \Exception("Please fill in all required fields.");
        }

        $amount = str_replace(',', '', $_POST['amount']);

        // File validation
        $allowed = ['pdf', 'png', 'jpg', 'jpeg'];
        $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed)) {
            throw new \Exception("Invalid file type. Only PDF, PNG, JPG, and JPEG are allowed.");
        }

        // Move uploaded file
        $random_id = substr(md5(rand()), 0, 10);
        $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $proof_file = "$random_id.$extension";

        if (!move_uploaded_file($_FILES['file']['tmp_name'], "proof/$proof_file")) {
            throw new \Exception("Failed to upload proof file.");
        }

        // Insert into DB
        $db_id = 0;
        $transaction_id = substr(md5(rand()), 0, 10);
        $credit_word = "Credit";
        $pending_word = "Pending";
        $start_date = date("Y-m-d H:i:s");
        $details = "User Deposit";
        $empty = "";
        $account_paid_into = "$official_bank_name - $official_bank_account";

        $stmt_deposit = $con->prepare('INSERT INTO transactions VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt_deposit->bind_param(
            'issssssssssssssss',
            $db_id, $transaction_id, $user_id, $credit_word, $amount, $pending_word, $start_date,
            $details, $proof_file, $account_paid_into, $empty, $empty, $empty, $empty, $empty, $empty, $empty
        );
        $stmt_deposit->execute();

        if ($stmt_deposit->affected_rows < 1) {
            throw new \Exception("Database insert failed.");
        }

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
        $mail->Subject = "$first_name $last_name made a deposit";

        $message = "
        <div style='font-family:Calibri;background:cornflowerblue;padding:10px;border-radius:5px;'>
            Hello Admin,<br/><br/>
            A deposit request has been made.<br/><br/>
            <b>Depositor:</b> $first_name $last_name<br/>
            <b>Amount:</b> $currency$amount<br/>
            <b>Date:</b> $start_date<br/>
            <b>Transaction ID:</b> $transaction_id<br/>
            <b>Account Paid Into:</b> $official_bank_name - $official_bank_account<br/>
            <br/>
            Please log in to approve or reject this deposit.<br/>
            Regards,<br/>
            $company_name Admin Notification System<br/>
            $email_logo
        </div>";

        $mail->msgHTML("<html><body>$message</body></html>");
        $mail->addAddress("$company_email", "$company_name");

        if (!$mail->send()) {
            echo json_encode([
                //"status" => "warning",
                //"message" => "Deposit recorded successfully, but email could not be sent. Error: " . $mail->ErrorInfo
                "status" => "success",
                "message" => "Wallet funding request submitted successfully. Please wait for approval."
            ]);
            exit;
        }

        // Success
        echo json_encode([
            "status" => "success",
            "message" => "Wallet funding request submitted successfully. Please wait for approval."
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
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Deposit</title>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->
    <!-- breadcrumb -->
    <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account">Home</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Deposit</li>
                    </ol>
                </nav>
                <h5>Top up your wallet</h5>
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
                <h5>Fill in the fields and transfer to the account shown below </h5>
                <style>
                .copy-success {
                    display: none;
                    color: green;
                    font-size: 0.9rem;
                    margin-left: 10px;
                }
                </style>

                <p class="text-secondary">
                <?= $official_bank_name ?> -
                <span id="account-number"><?= $official_bank_account ?></span>
                <span id="copy-success" class="copy-success">Copied!</span>
                </p>

                <button class="btn btn-theme" onclick="copyAccount()">Copy Account</button>
                <p style='color:cornflowerblue;' id='response'></p>
            </div>
            <form id='theForm' method="post" enctype='multipart/form-data' class="card-body" data-url="deposit">
                <input type="hidden" name="ajax" value="1">
                <div class="row mb-2">
                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name='amount' id="amount" placeholder="Amount" value="" required>
                            <label for="amount">Amount (<?= $currency ?>)</label>
                             <div class="invalid-feedback"></div>
                        </div>
                    </div>
                     <div class="col-12 col-md-6 col-xl-6 mb-3">
                        <div class="form-floating">
                            <input type="file" class="form-control" name='file' id="file" accept=".pdf, .png, .jpg, .jpeg" required>
                            <label for="file">Upload a Screenshot or Transaction Receipt</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
   
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <h5></h5>
                        <p class="text-secondary small">Amount will be credited to wallet on approval</p>
                    </div>
                    <div class="col-auto">
                        <button type='submit' class="btn btn-theme">I have done the transfer</button>
                    </div>
                </div>
            </form>
            <div id="formMessage" style="margin-top:10px;"></div>
            
        </div>
    </div>
    <?php
        $active = "Active"; $count = 0;
        $stmt = $con -> prepare('SELECT plan_id, type, name, level, min_amount, max_amount, roi, duration, picture FROM plans WHERE status = ? ORDER BY RAND() LIMIT 1 ');
        $stmt -> bind_param('s', $active);
        $stmt -> execute(); 
        $stmt -> store_result(); 
        $stmt -> bind_result($plan_id, $type, $name, $level, $min_amount, $max_amount, $roi, $duration, $picture); 
        $numrows = $stmt -> num_rows();
        if($numrows > 0){
            while ($stmt -> fetch()) { 
        ?>
    <div class="col-12 col-lg-4 mb-4">
        <!-- offer -->
        <div class="card adminuiux-card position-relative overflow-hidden bg-theme-1 h-100">
            <div class="position-absolute top-0 start-0 h-100 w-100 z-index-0 coverimg opacity-50">
                <img src="site_img/investments/<?= $picture ?>" alt="">
            </div>
            <div class="card-body z-index-1">
                <div class="avatar avatar-60 rounded bg-white-opacity text-white mb-4">
                    <i class="bi bi-tags h4"></i>
                </div>
                <h2><?= $name ?></h2>
                <h4 class="fw-medium"><?= $roi ?>%</h4>
                <p class="mb-4">Min Deposit: <b><?= $currency ?><?= number_format((float)$min_amount, 2, '.', ','); ?></b></p>
                <a href='invest_details?u=<?= $plan_id ?>' class="btn btn-light my-1">Invest Now</a>
            </div>
        </div>
    </div>
    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</main>

            </div>
<script>
    //copy account button
function copyAccount() {
    const accountNumber = document.getElementById("account-number").innerText;
    const successMsg = document.getElementById("copy-success");

    navigator.clipboard.writeText(accountNumber).then(() => {
        // Show success message
        successMsg.style.display = "inline";

        // Hide after 2 seconds
        setTimeout(() => {
            successMsg.style.display = "none";
        }, 2000);
    }).catch((err) => {
        console.error("Copy failed", err);
    });
}
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