<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("minks.php"); ?>
<title><?php echo $company_name; ?> - Sign in</title>
<?php $errors=0;
if (isset($_POST["log_in"])) {
	
	$email = mysqli_real_escape_string($con,$_POST['email']);
	$password = mysqli_real_escape_string($con,$_POST['password']);
	
	$sql = "SELECT * FROM users WHERE email = '$email' AND password='$password' LIMIT 1" ;
		$check_query = mysqli_query($con,$sql);
		$count_email = mysqli_num_rows($check_query);
		if($count_email > 0){
			while ($row_first_name = mysqli_fetch_array($check_query, MYSQLI_ASSOC)) {
				$first_name = $row_first_name['first_name'];
                $last_name = $row_first_name['last_name'];
				$user_id = $row_first_name['user_id'];
				$email = $row_first_name['email'];
			}
		}
		
		if($count_email !== 1){
			//create a session to verify it's coming from here
			$_SESSION["action"] = "true";
			$message="Wrong username or password";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=failure.php?u=$page_name&m=$message\">";exit();
			$errors = 1;
		}
		
	if($errors ==0 AND isset($_POST['log_in'])){
		
	
	
	//create the sesssion
	$_SESSION["email"] = $email;
	$_SESSION["user_id"] = $user_id;
	$_SESSION["first_name"] = $first_name;
    $_SESSION["last_name"] = $last_name;
							
	//take them to the success page
	
	echo "<meta http-equiv=\"refresh\" content=\"0; url=account.php\">";
	exit();
	}	
}
?>
<!DOCTYPE html><html lang="en">
    <head>
    <!-- Required meta tags  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    
    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <style>
        :root {
            --adminuiux-content-font: "DM Sans", sans-serif;
            --adminuiux-content-font-weight: 400;
            --adminuiux-title-font: "DM Sans", sans-serif;
            --adminuiux-title-font-weight: 600;
        }
    </style>

<script defer="" src="js/app.js"></script><link href="css/app.css" rel="stylesheet"></head>

<body class="main-bg main-bg-opac main-bg-blur adminuiux-sidebar-fill-white adminuiux-sidebar-boxed  theme-blue roundedui" data-theme="theme-blue" data-sidebarfill="adminuiux-sidebar-fill-white" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" tabindex="0">
    <!-- Pageloader -->
<div class="pageloader">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center text-center h-100">
            <div class="col-12 mb-auto pt-4"></div>
            <div class="col-auto">
                <img src="assets/img/logo.png" alt="" class="height-60 mb-3">
                <div class="loader10 mb-2 mx-auto"></div>
            </div>
            <div class="col-12 mt-auto pb-4">
                <p class="text-secondary"></p>
            </div>
        </div>
    </div>
</div>

        <main class="flex-shrink-0 pt-0 h-100">
            <div class="container-fluid">
                <div class="auth-wrapper">
                    <!--Page body-->

                    <!-- login wrap -->
                    <div class="row">
                        <div class="col-12 col-md-6 col-xl-4 minvheight-100 d-flex flex-column px-0">
                            <!-- standard header -->
                            <!-- standard header -->
<header class="adminuiux-header">
    <!-- Fixed navbar -->
    <nav class="navbar">
        <div class="container-fluid">
            <!-- logo -->
            <a class="navbar-brand" href="index.php">
                <img data-bs-img="light" src="assets/img/logo.png" style="object-fit:cover;">
                <img data-bs-img="dark" src="assets/img/logo.png" style="object-fit:cover;">
            </a>

            <div class=" ms-auto "></div>
            <!-- right icons button -->
            <div class="ms-auto">


            </div>
        </div>
    </nav>
</header>

                                <div class="h-100 py-4 px-3">
                                    <div class="row h-100 align-items-center justify-content-center mt-md-4">
                                        <form action="sign_in" method="post" class="col-11 col-sm-8 col-md-11 col-xl-11 col-xxl-10 login-box">
                                            <div class="text-center mb-4">
                                                <h1 class="mb-2">Welcome</h1>
                                                <p class="text-secondary">Enter your credentials to continue</p>
                                            </div>
                                          
                                            <div class="form-floating mb-3">
                                                <input type="email" class="form-control" id="emailadd" placeholder="Email Address" value="" name="email" required="">
                                                <label for="emailadd">Email Address</label>
                                            </div>

<div class="position-relative">
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="passwd" name="password" placeholder="Enter your password" required>
        <label for="passwd">Password</label>
    </div>
    <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2" onclick="togglePasswordVisibility()">
        <i class="bi bi-eye-slash" id="toggleIcon"></i>
    </button>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('passwd');
    const icon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
}
</script>


                                           
                                            <button type="submit" name='log_in' class="btn btn-lg btn-theme w-100 mb-4">Login</button>
                                            <!-- <button class="btn btn-lg btn-theme w-100 mb-4">Login</button> -->
                                           

                                            

                                           
                                            
</form>
<div class='text-center'>
<a href="sign_up">Don't have an account?</a> &nbsp;&nbsp;&nbsp;
<a href="forgotpassword">Forgot Your Password?</a>
</div>
                                    </div>
                                </div>

                                <!-- standard footer -->
                                <!-- standard index footer -->
<footer class="adminuiux-footer mt-auto">
    <div class="container-fluid text-center">
        <span class="small">Copyright &copy; <?php echo date("Y"); ?> <?php echo $company_name; ?></a>. All rights reserved. </span>
        </span>
    </div>
</footer>

<!-- theming action-->
<div class="position-fixed bottom-0 end-0 m-3 z-index-5">
   
    <br>
    <button class="btn btn-theme btn-square rounded-circle shadow mt-2 d-none" id="backtotop"><i class="bi bi-arrow-up"></i></button>
</div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-8 p-4 d-none d-md-block">
                            <div class="card adminuiux-card bg-theme-1-space position-relative overflow-hidden h-100">
                                <div class="position-absolute start-0 top-0 h-100 w-100 coverimg opacity-75 z-index-0">
                                   
                                </div>
                                <div class="card-body position-relative z-index-1">
                                    <div class="row h-100 d-flex flex-column justify-content-center align-items-center gx-0 text-center">
                                        <div class="col-10 col-md-11 col-xl-8 mb-4 mx-auto">

                                            <!-- Slider container -->
                                            <div class="swiper swipernavpagination pb-5">
                                                <div class="swiper-wrapper">
                                                    <!-- Slides -->
                                                    <div class="swiper-slide">
                                                        <img src="images/nairabag.png" style="object-fit:cover; height:300px;" class="mw-100 mb-3">
                                                        <h2 class="text-white mb-3">Manage Your Investments with Ease</h2>
                                                        <p class="lead opacity-75">Welcome to our new online investment platform, cratfted to ensure you are always on top of your finances</p>
                                                    </div>
                                                   
                                                </div>
                                                <!-- pagination -->
                                                <div class="swiper-pagination white bottom-0"></div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Page Level js -->
        <script src="js/investment-auth.js"></script>

        

</body></html>