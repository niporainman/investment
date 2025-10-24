<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Referral Program</title>
<?php 
//count referrals 
$referrals = 0;
$stmt_s1 = $con -> prepare('SELECT user_id FROM users WHERE referral_code = ?');
$stmt_s1 -> bind_param('s', $user_id);
$stmt_s1 -> execute(); 
$stmt_s1 -> store_result(); 
$stmt_s1 -> bind_result($referral_user_id); 
$numrows_s1 = $stmt_s1 -> num_rows();
if($numrows_s1 > 0){ 
    while ($stmt_s1 -> fetch()) {
        //have they invested 
        $details_word = "Investment";
        $approved_word = "Approved";
        $stmt_s = $con -> prepare('SELECT id FROM transactions WHERE user_id = ? AND (details = ? AND status = ?)');
        $stmt_s -> bind_param('sss', $referral_user_id, $details_word, $approved_word);
        $stmt_s -> execute(); 
        $stmt_s -> store_result(); 
        $stmt_s -> bind_result($transaction_id); 
        $numrows_s = $stmt_s -> num_rows();
        if($numrows_s > 0){ 
            while ($stmt_s -> fetch()) {
                $referrals++;
            }
        }

    }
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
                        <li class="breadcrumb-item bi"><a href="">Home</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Referral</li>
                    </ol>
                </nav>
                <h5>Referral Program</h5>
            </div>
        </div>
    </div>

    <!-- Content  -->
    <div class="container mt-4" id="main-content">

        <div class="row align-items-center">
            <!-- Welcome box -->
            <div class="col-12 col-md-10 col-lg-8 mb-4">
                <h3 class="fw-normal mb-0 text-secondary">Love it? Refer a friend and get rewarded!</h3>
                <h1>Start referring your friends to join us.</h1>
            </div>
            <div class="col-12 py-2"></div>
            <!-- copy code-->
            <div class="col-12 col-md-8 col-lg-6 col-xxl-5 mb-4">
                <p>Copy and Share your referral link with your network</p>
                <div class="input-group mb-3">
                    <div class="input-group mb-2">
                        <input 
                            type="text" 
                            class="form-control form-control-lg border-theme-1" 
                            placeholder="Referral Code" 
                            aria-describedby="button-addon2" 
                            value="<?php echo $link . '/sign_up?ref=' . $user_id; ?>" 
                            id="referralCode"
                            readonly
                        >
                        <button 
                            class="btn btn-lg btn-outline-theme" 
                            type="button" 
                            id="copyButton"
                        >
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>

                    <!-- Hidden Bootstrap alert -->
                    <div id="copyAlert" class="alert alert-success py-2 px-3 w-100" role="alert" style="display:none;">
                        Referral code copied to clipboard!
                    </div>

                    <script>
                    document.getElementById("copyButton").addEventListener("click", function() {
                        const input = document.getElementById("referralCode");
                        navigator.clipboard.writeText(input.value)
                            .then(() => {
                                const alertBox = document.getElementById("copyAlert");
                                alertBox.style.display = "block"; // Show alert
                                setTimeout(() => {
                                    alertBox.style.display = "none"; // Hide after 2s
                                }, 2000);
                            })
                            .catch(err => {
                                console.error("Failed to copy: ", err);
                            });
                    });
                    </script>


                </div>
            </div>
            <div class="col-12 py-2"></div>
            <!-- registration -->
            <div class="col-6 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card">
                    <div class="card-body">
                        <h2><?= $referrals ?></h2>
                        <p class="text-secondary small">Total Referrals</p>
                    </div>
                </div>
            </div>
            
            <!-- referral earnings -->
            <!--<div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card position-relative overflow-hidden bg-theme-1 h-100">
                    <div class="position-absolute top-0 start-0 h-100 w-100 z-index-0 coverimg opacity-50">
                        <img src="images/flamingo-4.jpg" alt="">
                    </div>
                    <div class="card-body z-index-1">
                        <div class="row gx-3 align-items-center h-100">
                            <div class="col-auto">
                                <span class="avatar avatar-60 text-bg-warning rounded">
                                    <i class="bi bi-cash-coin h4"></i>
                                </span>
                            </div>
                            <div class="col">
                                <h2><?= $currency ?>.00</h2>
                                <p>Referral earning</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
        <br>
        <div class="row align-items-center jsutify-content-center">
            <div class="col-12 mb-4">
                <h5>Learn how it works!</h5>
            </div>
            <!-- step 1 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-link avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>1. Invite</h6>
                <p class="text-secondary">Invite unlimited individuals by sharing referral link</p>
            </div>
            <!-- step 2 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-person avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>2. Registration</h6>
                <p class="text-secondary">Let your referral join our platform and track earnings</p>
            </div>
            <!-- step 3 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-coin avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>3. Fast Earning</h6>
                <p class="text-secondary">Earn on successful completion of account opening</p>
            </div>
            <!-- step 4 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <i class="bi bi-cash-stack avatar avatar-60 bg-theme-1-subtle text-theme-1 rounded h4 mb-3"></i>
                <br>
                <h6>4. More to be made</h6>
                <p class="text-secondary">Earn on successful investments made by referrals</p>
            </div>
        </div>
    </div>
</main>

</div>

<!-- page footer -->
<!-- standard footer -->
<?php include("footer_acc.php"); ?>


                <!-- theming offcanvas-->


                    <!-- Page Level js -->
                    

</body></html>