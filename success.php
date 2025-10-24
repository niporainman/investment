<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
 include("minks.php"); ?>
 <!DOCTYPE html>
<html lang="en">
<head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Link of CSS files -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/swiper.bundle.min.css">
        <link rel="stylesheet" href="assets/css/scrollcue.min.css">
        <link rel="stylesheet" href="assets/css/remixicon.css">
        <link rel="stylesheet" href="assets/css/header.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/footer.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
        <link rel="stylesheet" href="assets/css/dark-theme.css">
        <link rel="icon" type="image/png" href="assets/img/favicon.png">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <script src='jquery-3.2.1.min.js'></script>
    </head>
    <body>
<title><?php echo $company_name; ?> - Action Successful</title>
<?php
//lets us know if its coming from a legit source or someone just typed it in the address bar
if ( $_SESSION["action"] == "true" ){
	//get the necessary info for success page display
	if (isset($_GET['u']) AND isset($_GET['m'])) {
		$page_redirect = mysqli_real_escape_string($con,$_GET['u']);
		$message = mysqli_real_escape_string($con,$_GET['m']);
	}
	else{ echo "<meta http-equiv=\"refresh\" content=\"0; url=index.php\">";exit();}
}
else{ echo "<meta http-equiv=\"refresh\" content=\"0; url=index.php\">";exit();}
?>
<script>
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var end =setInterval(function () {
            minutes = parseInt(timer / 60, 10)
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "" + minutes : minutes;
            seconds = seconds < 10 ? "" + seconds : seconds;

            display.textContent = seconds;

            if (--timer < 0) {
                window.location = "<?php echo"$page_redirect"; ?>";
                clearInterval(end);
            }
        }, 1000);
    }

    window.onload = function () {
        var fiveMinutes = 2,
            display = document.querySelector('#time');
        startTimer(fiveMinutes, display);
    };
</script>
<style>
svg {
  width: 100px;
  display: block;
  margin: 40px auto 0;
}
.path {
  stroke-dasharray: 1000;
  stroke-dashoffset: 0;
}
.path.circle {
  -webkit-animation: dash 2.5s ease-in-out;
  animation: dash 2.5s ease-in-out;
}
.path.line {
  stroke-dashoffset: 1000;
  -webkit-animation: dash 2.5s 0.35s ease-in-out forwards;
  animation: dash 2.5s 0.35s ease-in-out forwards;
}
.path.check {
  stroke-dashoffset: -100;
  -webkit-animation: dash-check 2.5s 0.35s ease-in-out forwards;
  animation: dash-check 2.5s 0.35s ease-in-out forwards;
}
p {
  text-align: center;
  margin: 20px 0 60px;
  font-size: 1.25em;
}
p.success {
  color: forestgreen;
}
p.error {
  color: red;
}
@-webkit-keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}
@keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}
@-webkit-keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}
@keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}


 @media only screen and (max-width: 767px) {
	.ss{
		position:;
		left:;
	}
}
 @media only screen and (min-width: 768px) {
	.ss{
		position:relative;
		left:0px;
	}
}
.ss{
	margin:auto !important;
	text-align:center !important;
}
</style>
    <!-- Start About Page  -->
    <div class="contact-box-main">
        <div class="container">
           <div class="row align-items-center justify-content-center">
                
                <div class ='col-lg-4'>
                <div class="card" style='margin-top:150px;'>
            <div class="card-header" style='padding:0px;text-align:center;'>
                <h2 style='color:forestgreen;text-align:center;font-size:30px;'>Success</h2>
                <?php echo"$message"; ?>
              </div>
            <div class="card-body">
<svg class='ss' version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
  <circle class="path circle" fill="none" stroke="forestgreen" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
  <polyline class="path check" fill="none" stroke="forestgreen" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
</svg>  
 <div class='explanation1' style='text-align:center;'>Redirecting in <span id="time">5</span><br/>

			<br/>
			<div style='text-align:center;margin:auto !important;'>
			  <a href='<?php echo"$page_redirect"; ?>' class='btn btn-primary'>Redirect</a>
			</div><br>
        </div>              
                
               
            </div>
            
        </div><br><br>
                </div>
        </div>
    </div>
    <!-- End About Page -->
<?php 
//set the session to false so it wont be used again.
$_SESSION["action"] = "false";
?>
</body>
</html>