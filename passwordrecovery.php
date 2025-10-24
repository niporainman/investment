<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
$page_title = "Password Recovery";
$page_header = "a0da0d6486.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
<br><br>
<?php $expired_error=""; $errors=0;$date_today = date("Y-m-d");$msg=""; $password_mismatch_error="";
if (isset($_GET['u'])) {
	$security_code = mysqli_real_escape_string($con,$_GET['u']);

	$check = mysqli_query($con,"SELECT *
		FROM password_recovery WHERE security_code='$security_code'");
		if (mysqli_num_rows($check)===1) {
			$get = mysqli_fetch_assoc($check);
			$email = $get['email'];
			$security_code = $get['security_code'];
			$date_requested = $get['date'];
			$used = $get['used'];
			
			if( ($date_requested !== $date_today) OR ($used !== 'no') ){
				$expired_error = "
					
						<b>Link expired, <a href='forgotpassword'>Resend Link</a></b>
					
				";
				$errors = 1;
				
			}
			//exit();
		}
		else{echo "<meta http-equiv=\"refresh\" content=\"0; url=$link\">";exit();}
}
else{echo "<meta http-equiv=\"refresh\" content=\"0; url=$link\">";exit();}

if($errors == 0 AND isset($_POST['reset_password'])){
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	
	if($password1 !== $password2){
		$password_mismatch_error = "
		
				<b>New Passwords do not match</b>
			
		";
		$errors = 1;
	}
	if($errors == 0){
		//$new_password_mde = md5("$password1");
		$query = mysqli_query($con, "UPDATE users SET password='$password1' WHERE email ='$email'");
		$query34 = mysqli_query($con, "UPDATE password_recovery SET used='yes' WHERE security_code ='$security_code'");
		//create a session to verify it's coming from here
		$_SESSION["action"] = "true";
		$message="You have successfully changed your password.";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=success?u=$link&m=$message\">";
	}
	
}
?>

  <section id="maincontent">
    <div class="container">
      <div class="row">
        <div class="col-6">
        <br>
		  <?php echo $expired_error; echo $msg; echo $password_mismatch_error; ?> 
		  <form method="post" action="passwordrecovery<?php echo "?u=$security_code" ?>" enctype="multipart/form-data">
			 <input name="password1" class='form-control' type="password" placeholder='New password' required /> <br>
			 <input name="password2" class='form-control' type="password" placeholder='Type new password again' required />
			<br> 
			 <div style="text-align:left;">
			  <button class="btn btn-medium" style='background:#1063c8;color:white;' type="submit" name="reset_password">Reset Password</button><br/><br/>
			</div>
		  </form>
        </div>

        <div class="col-6">
        </div>
      </div>
    </div>
  </section>
<?php include("footer.php");?>