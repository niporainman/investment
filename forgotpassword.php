<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
$page_title = "Password Recovery";
$page_header = "a0da0d6486.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
<br><br>
<?php $emailexsit_Error=""; $errors=0;$date = date("Y-m-d");$msg="";
if (isset($_POST['recover_password'])) {
	$email = $_POST["email"];
	
	$sql = "SELECT first_name,last_name FROM users WHERE email = '$email' LIMIT 1" ;
	$check_query = mysqli_query($con,$sql);
	$count_email = mysqli_num_rows($check_query);
	if($count_email !== 1){
		$emailexsit_Error = "<b>No account is associated with $email</b>";
		$errors = 1;
	}
	else{
		$get = mysqli_fetch_array($check_query);
		$first_name = $get["first_name"];
		$last_name = $get["last_name"];
	}
	
	if($errors == 0 AND isset($_POST['recover_password'])){
		$security_code = substr(md5(rand()), 0, 26);
		$query = mysqli_query($con,"INSERT INTO password_recovery VALUES(
			'0',
			'$email',
			'$security_code',
			'$date',
			'no'
			)")or die(mysqli_error($con));

			
//
$msg = '';
$subject = "$company_name Password Recovery";
$message = "Hello $first_name $last_name,<br/><br/>
You are receiving this email beacuse you requested for a password reset.<br/>
To get started click <a href='$link/training/passwordrecovery?u=$security_code'>here</a><br/>
If you have any enquires please feel free to get in touch at $company_email<br/><br/>

Regards,<br/>
The $company_name Team.<br/><br/>
$email_logo<br/><br/>
";
require 'PHPMailer/src/PHPMailer.php'; 
			require 'PHPMailer/src/SMTP.php'; 
			require 'PHPMailer/src/Exception.php';

	$mail = new PHPMailer();

	//$mail->IsSMTP(); // telling the class to use SMTP
	//$mail->SMTPAuth = true; // enable SMTP authentication
	$mail->Host = "localhost"; // sets the SMTP server
	//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Set encryption to STARTTLS
    $mail->Port = 25; // Use port 587 for TLS
	$mail->Username = "$no_reply_email"; // SMTP account username
	$mail->Password = "$no_reply_password"; // SMTP account password
	$mail->SetFrom("$no_reply_email", "$company_name");//Use a fixed address in your own domain as the from address
			$mail->AddReplyTo("$company_email", "$company_name"); //Put the submitter's address in a reply-to header
			$mail->Subject = "$subject";
			$mail->MsgHTML("<html><body>$message<br></body></html>");
			$mail->AddAddress("$email", "$email");//Send the message to yourself, or whoever should receive contact for submissions
 
//$mail->AddAttachment(""); // attachment

	if(!$mail->Send()) {
	//echo "Mailer Error: " . $mail->ErrorInfo;
	$msg = "
				<h3><span style='color:#1063c8;'>Something went wrong, please try again.</span></h3>
			";
	} 
	else {
	$msg = "
				<h3><span style='color:#1063c8;'>Please check your email, you have been sent a link to reset your password.</span></h3>
			";
	}					
	
	}
}
?>
  <section id="maincontent">
    <div class="container">
      <div class="row">
        <div class="col-6">
        <br>
		  <?php echo $emailexsit_Error; echo $msg; ?> 
		  <form method="post" action="forgotpassword" enctype="multipart/form-data">
			 <input name="email" class='form-control' type="email" placeholder='Enter your email' required /> <br>
			 <div style="text-align:left;">
			  <button class="btn btn-medium" style='background:#1063c8;color:white;' type="submit" name='recover_password'>Recover Password</button><br/><br/>
			</div>
		  </form>
        </div>

        <div class="col-6">
        </div>
      </div>
    </div>
  </section>
<?php include("footer.php");?>