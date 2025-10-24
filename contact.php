<?php use PHPMailer\PHPMailer\PHPMailer;use PHPMailer\PHPMailer\Exception;use PHPMailer\PHPMailer\SMTP; session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
$page_title = "Contact us";
$page_header = "3e5e6c7f1c.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>

<?php 
$msg='';$captcha_error=""; $errors=0;

if (isset($_POST["send_message"])) {
	
include("captcha_start.php");
if($errors == 0){
	
	$subject = mysqli_real_escape_string($con,$_POST['subject']);
	$last_name = mysqli_real_escape_string($con,$_POST['last_name']);
	$first_name = mysqli_real_escape_string($con,$_POST['first_name']);
	$email = mysqli_real_escape_string($con,$_POST['email']);
	$message1 = mysqli_real_escape_string($con,$_POST['message']);
	
	$msg = '';
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
	$mail->Subject = "$subject";
	$mail->MsgHTML("<html><body>$message1</body></html>");
	$mail->AddAddress("$company_email", "Contact Form");//Send the message to yourself, or whoever should receive contact for submissions
	 
	//$mail->AddAttachment(""); // attachment

		if(!$mail->Send()) {
		//echo "Mailer Error: " . $mail->ErrorInfo;
		$msg = "<div class='alert alert-danger'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Something went wrong, please try again</b>
				</div>";
		} 
		else {
		$msg = "<div class='alert alert-success'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Email Sent</b>
				</div>";
		}
		//email an autoresponse to the person too
	$mail->clearAddresses();
	$mail->clearReplyTos();
	
		$subject1 = "Thanks for contacting us"; // form field
		$message="";
		$button_link="$link/sign_in.php";
		$button_text="Log in";
		$email_topic="Thanks for contacting us";
		include("email_header.php");
		$message .=	"
		Dear $first_name,<br/><br/>
		Thank you for contacting our support team. Your request is in progress and is being worked on by our service team. We are prioritizing your request and will notify you via email.
		<br/><br/>
		The $company_name Team.<br/>
		$email_logo
		 ";
		 include("email_footer.php");
$mail->SetFrom("$company_email", "$company_name");//Use a fixed address in your own domain as the from add
$mail->AddAddress("$email", "$email");//Send the message to yourself, or whoever should receive contact for submissions
$mail->AddReplyTo("$company_email", "$company_name"); //Put the submitter's address in a reply-to header
$mail->Subject = "$subject1";
$mail->MsgHTML("<html><body>$message<br></body></html>");
	if(!$mail->Send()) {
	//echo "Mailer Error: " . $mail->ErrorInfo;
	$msg = "Email not sent, please try again Mailer Error: ".$mail->ErrorInfo;
	} 
	else {
	//echo "Thanks for getting in touch, we will get back to ASAP";
	$msg = "<span style='color:steelblue;'>Hey $first_name thanks for getting in touch with us, we will get back to you very shortly!</span>";
	}
}
}

?>

        <!-- Contact Section Start -->
        <div class="contact-wrap style-one position-relative ptb-130 overflow-hidden">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="contact-img-wrap position-relative me-xxl-auto mb-md-30">
                            <div class="contact-img position-relative">
                                <img src="site_img/general/d9aea1c8ca.jpg" alt="Image" style='height:400px; object-fit:cover;'>
                            </div>
                            <div class="client-review position-absolute bg-optional d-flex align-items-center justify-content-between round-2">
                                <div class="trustlogo d-flex align-items-center">
                                    <div class="client-logo">
                                        <p class="fw-bold text-title">Review On</p>
                                        <img src="assets/img/contact/trustpilot-logo.webp" alt="Image" class="logo-light">
                                        <img src="assets/img/contact/trustpilot-logo-2.webp" alt="Image" class="logo-dark">
                                    </div>
                                    <div class="ratings">
                                        <ul class="list-unstyle d-flex">
                                            <li><img src="assets/img/icons/star-2.svg" alt="Image"></li>
                                            <li><img src="assets/img/icons/star-2.svg" alt="Image"></li>
                                            <li><img src="assets/img/icons/star-2.svg" alt="Image"></li>
                                            <li><img src="assets/img/icons/star-2.svg" alt="Image"></li>
                                            <li><img src="assets/img/icons/half-star.svg" alt="Image"></li>
                                        </ul>
                                        <span>544+ Reviews</span>
                                    </div>
                                </div>
                                <div class="client-ratings d-flex flex-wrap align-items-center justify-content-md-between">
                                    <img src="assets/img/contact/google.webp" alt="Image" class="logo-light">
                                    <div class="ratings">
                                        <ul class="list-unstyle d-flex">
                                            <li><i class="ri-star-s-fill"></i></li>
                                            <li><i class="ri-star-s-fill"></i></li>
                                            <li><i class="ri-star-s-fill"></i></li>
                                            <li><i class="ri-star-s-fill"></i></li>
                                            <li><i class="ri-star-s-fill"></i></li>
                                        </ul>
                                        <span>Reviews 4.9/5.0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="contact-content mb-0">
                            <div class="section-title mb-30">
                                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Send us a message</span>
                                <h2 class="mb-0">Contact Us</h2>
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="contact-form">
                                <div class="form-group d-flex flex-wrap align-items-center justify-content-between">
                                    <label for="email">Your email</label>
                                    <input type="email" name="email" id="email" class="fs-15 h-52 bg-transparent" required>
                                </div>
                                <div class="form-group d-flex flex-wrap align-items-center justify-content-between">
                                    <label for="name">First name</label>
                                    <input type="text" name="first_name" id="first_name" class="fs-15 h-52 bg-transparent" required>
                                </div>
                                 <div class="form-group d-flex flex-wrap align-items-center justify-content-between">
                                    <label for="name">Last name</label>
                                    <input type="text" name="last_name" id="last_name" class="fs-15 h-52 bg-transparent" required>
                                </div>
                                <div class="form-group d-flex flex-wrap align-items-center justify-content-between">
                                    <label for="subject">Subject</label>
                                    <input type="text" name="subject" id="subject" class="fs-15 h-52 bg-transparent" required>
                                </div>
                                <div class="form-group d-flex flex-wrap align-items-start justify-content-between">
                                    <label for="msg" class="pt-xxl-2">Message</label>
                                    <textarea name="message" id="msg" cols="30" rows="10" class="bg-transparent resize-0" placeholder="Type a message" required></textarea>
                                </div>
                                <div class="form-group d-flex flex-wrap align-items-start justify-content-between">
                                    <label for="msg" class="pt-xxl-2">Captcha</label><?php include("captcha_end.php"); ?></div>
                                <div class="form-group d-flex flex-wrap align-items-center">
                                    <label for="msg"></label>
                                    <button type='submit' name='send_message' class="btn style-two">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pb-100">
            <div class="row align-items-center">
                <div class="col-xl-8 col-md-6 pe-xxl-45">
                    <div class="comp-map mb-30">
                        <iframe
                           src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2791.522361548054!2d11.631582776112678!3d45.6001457710765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x477f2d5c9c8f5915%3A0x432ceeff3e4b18b3!2sVia%20dell&#39;Artigianato%2C%2026%2C%2036050%20Bolzano%20Vicentino%20VI%2C%20Italy!5e0!3m2!1sen!2sng!4v1755161631705!5m2!1sen!2sng">
                        </iframe>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-45">
                    <div class="contact-info-box-wrap">
                        <div class="contact-info-box">
                            <h3 class="fs-24 fw-medium mb-15">Contact us</h3>
                            <ul class="list-unstyle">
                                <!--<li><span class="text-title fw-bold me-2">Toll Free:</span><a href="tel:<?= $company_phone ?>"><?= $company_phone ?></a></li>-->
                               
                                <li><span class="text-title fw-bold me-2">Email:</span><a href=""><span class="__cf_email__" data-cfemail=""><?= $company_email ?></span></a></li>
                            </ul>
                        </div>
                        <div class="contact-info-box">
                            <h3 class="fs-24 fw-medium mb-15">Our Address</h3>
                           <p class="mb-0"><?= $company_address ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact Section End -->
<?php include("footer.php") ?>