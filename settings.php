<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; 
session_start();
include("minks.php");
$page_name = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION["user_id"];
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$email = $_SESSION["email"];

//get user account details from users table 
$stmt_u = $con -> prepare('SELECT phone, bank_name, account_number FROM users WHERE user_id = ?');
$stmt_u -> bind_param('s', $user_id);
$stmt_u -> execute();
$stmt_u -> store_result();
$stmt_u -> bind_result($phone, $user_bank_name, $user_account_number);
$numrows_u = $stmt_u -> num_rows();
if($numrows_u > 0){
    while ($stmt_u -> fetch()) {}
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
        if (empty($_POST['phone']) || empty($_POST['user_bank_name']) || empty($_POST['user_account_number']) ){
            throw new \Exception("Please fill in all required fields.");
        }

        $phone = $_POST['phone'];
        $user_bank_name = $_POST['user_bank_name'];
        $user_account_number = $_POST['user_account_number'];

        $stmt_d = $con -> prepare('UPDATE users SET phone = ?, bank_name = ?, account_number = ? WHERE user_id = ?');
	    $stmt_d -> bind_param('ssss', $phone, $user_bank_name, $user_account_number, $user_id);
	    $stmt_d -> execute();

        if ($stmt_d->affected_rows < 1) {
            //throw new \Exception("Database insert failed.");
        }

        // Success
        echo json_encode([
            "status" => "success",
            "message" => "Account details updated successfully.",
            "updates" => [
                "#phone" => [
                    "data-phone" => $phone,
                    "value" => $phone
                ],
                "#user_bank_name" => [
                    "data-bank-name" => $user_bank_name,
                    "value" => $user_bank_name
                ],
                "#user_account_number" => [
                    "data-account-number" => $user_account_number,
                    "value" => $user_account_number
                ]
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
<title><?php echo $company_name; ?> - Settings</title>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->
    <!-- breadcrumb -->
   <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Settings</li>
                    </ol>
                </nav>
                <h5>Update your profile</h5>
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
                <h5>Update your details </h5>
                
            </div>
             <form id='theForm' method="post" enctype='multipart/form-data' class="card-body" data-url="settings">
                <input type="hidden" name="ajax" value="1">
                <div class="row mb-2">
                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                        <div class="form-floating">
                            <input type="text" data-phone="<?= $phone?>" class="form-control" name='phone' id="phone" placeholder="Phone" value="<?= $phone ?>" required>
                            <label for="phone">Phone</label>
                             <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" data-bank-name='<?= $user_bank_name ?>' name='user_bank_name' id="user_bank_name" placeholder="Bank" value="<?= $user_bank_name ?>" required>
                            <label for="user_bank_name">Bank</label>
                             <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                        <div class="form-floating">
                            <input type="number" data-account-number='<?= $user_account_number ?>' class="form-control" name='user_account_number' id="user_account_number" placeholder="Account Number" value="<?= $user_account_number ?>" required>
                            <label for="user_account_number">Account Number</label>
                             <div class="invalid-feedback"></div>
                        </div>
                    </div>
   
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <h5></h5>
                        
                    </div>
                    <div class="col-auto">
                        <button type='submit' class="btn btn-theme">Update</button>
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
        
<?php include("footer_acc.php"); ?>
<script src="js/dropzone.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='ajax.js'></script>
</body>
</html>