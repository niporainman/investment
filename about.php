<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']);
$page_title = "About us";
$page_header = "a0da0d6486.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
        

        <!-- Why Choose Us Start -->
        <div class="wh-area position-relative index-1 pt-130">
            <div class="container">
                <div class="row pb-130 align-items-center">
                    <div class="col-lg-6 pe-xxl-1">
                        <div class="wh-img-wrap position-relative">
                           
                            <img src="site_img/general/0ec1013ecb.jpg" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-6 pe-xxl-0">
                        <div class="wh-content">
                            <div class="section-title">
                               <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Why Choose Us</span>
<h2 class="mb-15">Best Business Solutions for Your Financial Success</h2>
<p>We are dedicated to guiding you on your financial journey with expertise, integrity, and a personalized approach. Our team of experienced financial consultants is committed to helping you achieve your goals with smart, strategic solutions.</p>
<p>With proven strategies, market insight, and a client‑first philosophy, we provide the tools and guidance you need to grow, protect, and manage your wealth effectively.</p>

                            </div>
                            <h4 class="fs-24">We are an award winning company</h4>
                            <div class="award-logo-wrap d-flex flex-wrap justify-content-center">
                                <img src="assets/img/about/award-1.webp" alt="Award" style='margin-right: 10px;'>
                                
                                <img src="assets/img/about/award-3.webp" alt="Award">
                               
                            </div>
                            <a href="sign_up" class="link style-four">Sign Up <img src="assets/img/icons/long-arrow-blue.svg" alt="Image"></a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- Why Choose Us End -->
<?php include("footer.php"); ?>