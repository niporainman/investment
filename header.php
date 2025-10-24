<?php include("minks.php"); ?>
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
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>

        <!--  Preloader Start -->
        <div class="preloader-area" id="preloader">
            <div class="loader"> 
                <div class="waviy">
                    <span><img src="assets/img/logo.png" style="width:200px; height:74px;"></span>
                </div>
            </div>
        </div>
        <!--  Preloader End -->
        
        <!-- Theme Switcher Start -->
        <div class="switch-theme-mode">
            <label id="switch" class="switch">
                <input type="checkbox" onchange="toggleTheme()" id="slider">
                <span class="slider round"></span>
            </label>
        </div>
        <!-- Theme Switcher End -->

        <!-- Navbar Area Start -->
        <div class="navbar-area style-one position-absolute top-0 start-0 w-100" id="navbar">
            <div class="container">
                <div class="navbar-top d-flex flex-wrap align-items-center md-none">
                    <a href="index.html" class="logo">
                        <img src="assets/img/logo.png" style="width:150px;height:56px;" alt='logo'>
                    </a>
                    <div class="contact-info d-flex justify-content-end ms-auto">
                        <!--<div class="contact-item position-relative">
                            <span class="fs-15 d-block"><i class="ri-phone-fill"></i>24/7 Support</span>
                            <a href="tel:<?= $company_phone ?>" class="d-block lh-1"><?= $company_phone ?></a>
                        </div>-->
                        <div class="contact-item position-relative">
                            <span class="fs-15 d-block"><i class="ri-message-2-fill"></i>Mail for Support</span>
                            <a href="" class="d-block lh-1"><span class="__cf_email__" data-cfemail=""><?= $company_email ?></span></a>
                        </div>
                        <a href="contact" class="btn style-three ms-auto">Contact us</a>
                        
                    </div>
                    
                </div>
                <nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center bg-white">
                    <a href="<?= $link ?>" class="logo d-lg-none">
                        <img src="assets/img/logo.png" alt="Image" class="logo-light" style='width:200px;height:74px;'>
                        <img src="assets/img/logo.png" alt="Image" class="logo-dark" style='width:200px;height:74px;'>
                    </a>
                    <div class="option-item d-flex align-items-center d-lg-none">
                        
                        <a class="navbar-toggler d-lg-none" data-bs-toggle="offcanvas" href="#navbarOffcanvas" role="button" aria-controls="navbarOffcanvas">
                            <span class="burger-menu">
                                <span class="top-bar"></span>
                                <span class="middle-bar"></span>
                                <span class="bottom-bar"></span>
                            </span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a href="<?= $link ?>" class="nav-link <?php if($page_name == "index.php"){echo"active";} ?>">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="about" class="nav-link <?php if($page_name == "about.php"){echo"active";} ?>">
                                    About
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="faqs" class="nav-link <?php if($page_name == "faqs.php"){echo"active";} ?>">
                                    FAQs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="plans" class="nav-link <?php if($page_name == "plans.php"){echo"active";} ?>">
                                    Plans
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="blog" class="nav-link <?php if($page_name == "blog.php"){echo"active";} ?>">
                                    Insights
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="account" class="nav-link <?php if($page_name == "account.php"){echo"active";} ?>">
                                    Account
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="contact" class="nav-link <?php if($page_name == "contact.php"){echo"active";} ?>">
                                    Contact
                                </a>
                            </li>
                            
                            
                        </ul>
                        <div class="others-option d-flex align-items-center justify-content-end">
                            <form class="searchbox position-relative" method='post' action='search_blog.php'>
                                <input type="search" placeholder="Search" class="fs-15" name='search' required>
                                <button type='submit' name='search_for' class="bg-transparent border-0 p-0 position-absolute"><i class="ri-search-2-line"></i></button>
                            </form>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Start Responsive Navbar Area -->
        <div class="responsive-navbar offcanvas offcanvas-end border-0" data-bs-backdrop="static" tabindex="-1" id="navbarOffcanvas">
            <div class="offcanvas-header">
                <a href="<?= $link ?>" class="logo d-inline-block">
                    <img src="assets/img/logo.png" alt="Image" class="logo-light" style='width:200px;height:74px;'>
                    <img src="assets/img/logo.png" alt="Image" class="logo-dark" style='width:200px;height:74px;'>
                </a>
                <button type="button" class="close-btn bg-transparent position-relative lh-1 p-0 border-0" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <ul class="responsive-menu">
                    <li>
                        <a href="<?= $link ?>">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="about">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="faqs">
                            FAQs
                        </a>
                    </li>
                    <li>
                        <a href="plans">
                            Plans
                        </a>
                    </li>
                    <li>
                        <a href="blog">
                            Insights
                        </a>
                    </li>
                    <li>
                        <a href="account">
                            Account
                        </a>
                    </li>
                    <li>
                        <a href="contact">
                            Contact
                        </a>
                    </li>
                </ul>
                <div class="contact-info d-flex">
                    <div class="contact-item position-relative">
                        <span class="fs-15 d-block"><i class="ri-phone-fill"></i>24/7 Support</span>
                        <a href="tel:<?= $company_phone ?>" class="d-block lh-1"><?= $company_phone ?></a>
                    </div>
                    <div class="contact-item position-relative">
                        <span class="fs-15 d-block"><i class="ri-message-2-fill"></i>Mail for Support</span>
                        <a href="" class="d-block lh-1"><span class="__cf_email__" data-cfemail=""><?= $company_email ?></span></a>
                    </div>
                </div>
                <div class="other-options d-flex flex-wrap align-items-center justify-content-start">
                    <a href="contact" class="btn style-three">Get In Touch</a>
                </div>
            </div>
        </div>