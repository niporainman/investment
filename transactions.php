<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("headerstrict.php"); ?>
<title><?php echo $company_name; ?> - Transactions</title>

<main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <!-- body content of pages -->
    <!-- breadcrumb -->
    < <div class="container-fluid mt-4">
        <div class="row gx-3 align-items-center">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="account"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Transaction History</li>
                    </ol>
                </nav>
                <h5>View all your activities</h5>
            </div>
            <div class="col-12 col-sm-auto text-end py-3 py-sm-0">

            </div>
        </div>
    </div>
       
   
<!-- content -->
<div class="container mt-4" id="main-content">
    
    <h5>Transactions History</h5>
    <div class="table-responsive" style='border-radius:20px;'>
        <table class="table">

<?php
//user wallet fundings
$ten = 10;
$stmt_wallet1 = $con -> prepare('SELECT * FROM transactions WHERE user_id = ?');
$stmt_wallet1 -> bind_param('s', $user_id);
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