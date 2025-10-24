<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("header.php"); ?>
<title><?php echo $company_name; ?> - Home</title>

        <!-- Hero Section Start -->
        <div class="hero-section style-one position-relative">
            <div class="hero-slider swiper">
                <div class="swiper-wrapper">
<?php
    $stmt_slider = $con -> prepare('SELECT * FROM picture_slider'); 
    $stmt_slider -> execute(); 
    $stmt_slider -> store_result(); 
    $stmt_slider -> bind_result($slideid,$slideheading,$slideparagraph,$slidepicture); 
    $numrows_slider = $stmt_slider -> num_rows();
    if($numrows_slider > 0){
        while ($stmt_slider -> fetch()) { 
?>
                    <div class="swiper-slide">
                        <div class="hero-slide-item hero-slide-1 bg-f position-relative" style="background-image: url(site_img/home_background/<?php echo $slidepicture; ?>);">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-8 col-md-10 move_text">
                                        <div class="hero-content">
                                            <span data-animate="bottom" class="hero-subtitle d-inline-block fs-15 fw-semibold"><?= $company_name ?></span>
                                            <h1 data-animate="bottom" class="text-white hero-title"><?= $slideheading; ?></h1>
                                            <p data-animate="bottom" class="hero-para"><?= $slideparagraph; ?></p>
                                            <a href="sign_up" data-animate="bottom" class="btn style-one hero-btn">Sign Up</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } } ?>
                </div>
                <div class="container position-relative">
                    <div class="slider-pagination hero-pagination d-flex flex-column align-items-end"></div>
                </div>
            </div>
        </div>
        <!-- Hero Section End -->

        <!-- Feature Section Start-->
        <div class="container pt-130 pb-100">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition" data-cue="slideInUp">
                        <div class="feature-icon bg-optional position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="assets/img/icons/calculation.svg" alt="Image" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Financial Planning</h3>
                            <p class="mb-0">Develop a personalized investment strategy to grow and protect your wealth for the future.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition" data-cue="slideInUp">
                        <div class="feature-icon bg-optional position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="assets/img/icons/startup.svg" alt="Image" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Portfolio Management</h3>
                            <p class="mb-0">Expert management of your investment portfolio to maximize returns and minimize risk.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ps-xxl-4">
                    <div class="feature-card style-one d-flex flex-wrap mb-30 transition" data-cue="slideInUp">
                        <div class="feature-icon bg-optional position-relative d-flex flex-coulmn justify-content-center align-items-center transition">
                            <img src="assets/img/icons/data-management.svg" alt="Image" class="transition">
                        </div>
                        <div class="feature-text">
                            <h3 class="fs-20">Retirement Solutions</h3>
                            <p class="mb-0">Secure your financial future with tailored retirement planning and investment options.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature Section End-->

        <!-- Moving Text Start -->
        <div class="move-text overflow-hidden" data-cue="slideInUp">
            <ul class="list-unstyle">
                <li>AGRICULTURE</li>
                <li>REAL ESTATE</li>
                <li>OIL AND GAS</li>
                <li>GOLD</li>
                <li>BITCOIN FARMING</li>
            </ul>
        </div>
        <!-- Moving Text End -->

        <!-- Service Section Start -->
        <div class="service-wrap style-one pt-130 position-relative index-1" data-cue="slideInUp">
            <img src="assets/img/footer-shape-1.webp" alt="Image" class="section-shape-one position-absolute">
            <div class="container position-relative index-1">
                <img src="assets/img/shape-17.webp" alt="Image" class="section-shape-two position-absolute bounce">
                <a href="sign_up" class="btn style-one hero-btn">Get Started</a> <br><br>
            </div>
            <div class="service-slider swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="service-card-item position-relative index-1 ptb-130">
                            <div class="container">
                                <div class="section-title d-flex flex-wrap align-items-center justify-content-between mb-25">
                                    <h2 class="mb-15">Financial Advisors</h2>
                                    <span class="service-counter mt-sm-20">01</span>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <div class="service-bg service-bg-1" style="background-image: url(site_img/general/be05e9ce84.webp)"></div>
                                    </div>
                                    <div class="col-lg-9 ps-xxl-3">
                                        <div class="service-card-wrap d-flex flex-wrap">
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/investment.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Investment</a></h3>
                                                <p class="transition">Comprehensive investment solutions tailored to your financial goals, including stocks, bonds, and alternative assets.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/retirement.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Retirement</a></h3>
                                                <p class="transition">Secure your future with expert retirement planning, ensuring a comfortable and worry-free retirement.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/education.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Education</a></h3>
                                                <p class="transition">Empowering you with financial education and resources to make informed investment decisions.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="service-card-item position-relative index-1 ptb-130">
                            <div class="container">
                                <div class="section-title d-flex flex-wrap align-items-center justify-content-between mb-25">
                                    <h2 class="mb-15">Wealth Management</h2>
                                    <span class="service-counter mt-sm-20">02</span>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <div class="service-bg service-bg-2" style="background-image: url(site_img/general/345ac622f0.jpg)"></div>
                                    </div>
                                    <div class="col-lg-9 ps-xxl-3">
                                        <div class="service-card-wrap d-flex flex-wrap">
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/investment.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Management</a></h3>
                                                <p class="transition">Professional management of your assets to optimize growth and minimize risk across all market conditions.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/retirement.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Wealth Collection</a></h3>
                                                <p class="transition">Build and preserve your wealth with strategic investment opportunities and expert guidance.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/education.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Risk Management</a></h3>
                                                <p class="transition">Protect your investments with robust risk management strategies and insurance solutions.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="service-card-item position-relative index-1 ptb-130">
                            <div class="container">
                                <div class="section-title d-flex flex-wrap align-items-center justify-content-between mb-25">
                                    <h2 class="mb-15">Financial Solutions</h2>
                                    <span class="service-counter mt-sm-20">03</span>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <div class="service-bg service-bg-3" style="background-image: url(site_img/general/fe0b0bbeef.jpg)"></div>
                                    </div>
                                    <div class="col-lg-9 ps-xxl-3">
                                        <div class="service-card-wrap d-flex flex-wrap">
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/investment.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Planning</a></h3>
                                                <p class="transition">Maximize your returns with effective planning and compliance for all your investments.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/retirement.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Estate Planning</a></h3>
                                                <p class="transition">Safeguard your legacy with comprehensive estate planning and wealth transfer solutions.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                            <div class="service-card bg-white text-center transition">
                                                <img src="assets/img/icons/education.svg" alt="Image" class="service-icon transition">
                                                <h3 class="fs-24"><a href="" class="transition">Wealth Preserve</a></h3>
                                                <p class="transition">Preserve your wealth for future generations with expert advice and innovative financial solutions.</p>
                                                <a href="" class="service-link d-flex flex-column align-items-center justify-content-center round-2 transition"><img src="assets/img/icons/right-arrow-2.svg" alt="Image"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider-pagination service-pagination d-flex flex-lg-column align-items-center justify-content-center"></div>
            </div>
        </div>
        <!-- Service Section End -->

        <!-- About Section Start -->
        <div class="about-wrap style-one position-relative ptb-130 overflow-hidden">
            <img src="assets/img/shape-1.webp" alt="Image" class="about-shape-one position-absolute md-none">
            <img src="assets/img/shape-3.webp" alt="Image" class="about-shape-two position-absolute md-none">
            <div class="container">
                <div class="row align-items-xl-start align-items-center">
                    <div class="col-lg-6" data-cue="slideInUp">
                        <div class="about-content">
                            <div class="section-title">
                                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">About Us</span>
                                <h2 class="mb-15">Empowering Your Financial Future</h2>
                                <p>Our mission is to help you achieve financial independence through strategic investment planning, expert advice, and personalized service. With years of experience, our team is dedicated to building your wealth and securing your future.</p>
                                <a href="sign_up" class="btn style-one hero-btn">Get Started</a>
                            </div>
                            <div class="feature-list list-unstyle">
                                <div class="feature-item position-relative">
                                    <img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 top-0">
                                    <h5 class="fs-20 fw-semibold">Investment Analysis</h5>
                                    <p class="mb-0">We evaluate your financial situation and recommend the best investment strategies for growth and security.</p>
                                </div>
                                <div class="feature-item position-relative">
                                    <img src="assets/img/icons/check.svg" alt="Image" class="position-absolute start-0 top-0">
                                    <h5 class="fs-20 fw-semibold">Expert Advisors</h5>
                                    <p class="mb-0">Our certified financial advisors provide guidance and support for all your investment needs.</p>
                                </div>
                                <div class="feature-item position-relative">
                                    <img src="assets/img/icons/check.svg" alt="Image">
                                    <h5>Long-term Partnerships</h5>
                                    <p class="mb-0">We build lasting partnerships with our clients, focused on trust, transparency, and mutual success.</p>
                                </div>
                            </div>
                             
                        </div>
                    </div>
                    <div class="col-lg-6 ps-xxl-3" sdata-cue="slideInUp">
                        <div class="about-img-wrap position-relative">
                            <img src="assets/img/shape-2.webp" alt="Image" class="about-img-shape position-absolute">
                            <div class="about-img position-relative index-1"><img src="site_img/general/fdeba5fcda.jpg" alt="Image"></div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About Section End -->

        <!-- Brand Section Start-->
        <div class="bg-optional ptb-130" data-cue="slideInUp">
            <h6 class="subtitle f-optional text-center mb-50 fs-20 text-title">Join <b class="text_secondary">12+</b> companies who’ve partnered with us</h6>
            <div class="brand-slider swiper">
                <div class="swiper-wrapper">
                    <?php
                    $stmt_part = $con -> prepare('SELECT partner FROM partners'); 
                    $stmt_part -> execute(); 
                    $stmt_part -> store_result(); 
                    $stmt_part -> bind_result($partner); 
                    $numrows_part = $stmt_part -> num_rows();
                    if($numrows_part > 0){
                        while ($stmt_part -> fetch()) {  ?>
                            <div class="swiper-slide">
                                <div class="brand-logo">
                                    <img src="site_img/partners/<?= $partner ?>" alt="Image" class="mx-auto d-block">
                                </div>
                            </div>
                    <?php } } ?>
                    
                </div>
            </div>
        </div>
        <!-- Brand Section End-->

        <!-- Working Process Section Start -->
        <div class="work-process-wrap pt-130 pb-100 position-relative">
            
            <div class="container px-xxl-0">
                <div class="section-title text-center mb-50" data-cue="slideInUp">
                    <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Work Process</span>
                    <h2 class="mb-15">How To Invest</h2>
                </div>
                <div class="work-card-wrap d-flex flex-wrap justify-content-center">
                    <div class="work-card text-center position-relative index-1 mb-30" data-cue="slideInUp">
                        <h3 class="fs-24">Sign Up</h3>
                        <p> Create an account by filling a few details. It's easy and takes less than 2 minutes</p>
                        <span class="work-counter text-white fs-20 d-flex flex-column align-items-center justify-content-center mx-auto">01</span>
                    </div>
                    <div class="work-card text-center position-relative index-1 mb-30" data-cue="slideInUp">
                        <h3 class="fs-24">Choose a Plan</h3>
                        <p>Select one of the investment portfolios, and watch your investment mature</p>
                        <span class="work-counter text-white fs-20 d-flex flex-column align-items-center justify-content-center mx-auto">02</span>
                    </div>
                    <div class="work-card text-center position-relative index-1 mb-30" data-cue="slideInUp">
                        <h3 class="fs-24">Withdraw</h3>
                        <p>Once your investment matures, withdraw your captial plus your return on investment! It's as easy as that</p>
                        <span class="work-counter text-white fs-20 d-flex flex-column align-items-center justify-content-center mx-auto">03</span>
                    </div>
                </div><br><br>
                <div class='d-flex justify-content-center mt-50'>
                    <a href="sign_up" class="btn style-one hero-btn">Create Account</a>
                </div>
            </div>
        </div>
        <!-- Working Process Section End -->

        <!-- Counter Section Start -->
       <div class="container position-relative">
    <img src="assets/img/shape-5.webp" alt="Image" class="counter-shape-one position-absolute rotate">
    <div class="counter-card-wrap style-one d-flex flex-wrap pt-130 pb-100">
        <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
            <h4>+<span class="counter">258</span>%</h4>
            <p class="mb-0">Average portfolio growth for top-performing clients.</p>
        </div>
        <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
            <h4>+<span class="counter">237</span>K</h4>
            <p class="mb-0">Satisfied investors who trust our expertise.</p>
        </div>
        <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
            <h4><span class="counter">8.2</span>X</h4>
            <p class="mb-0">Average return multiplier on strategic investments.</p>
        </div>
        <div class="counter-card position-relative text-center mb-30" data-cue="slideInUp">
            <h4>$<span class="counter">10</span>+</h4>
            <p class="mb-0">Billion in assets under active management.</p>
        </div>
    </div>
</div>

        <!-- Counter Section End -->

      

        <!-- Testimonial Section Start -->
        <div class="testimonial-wrap style-one bg-optional ptb-130">
            <div class="container position-relative">
                <img src="assets/img/shape-3.webp" alt="Image" class="section-shape-one position-absolute rotate">
                <img src="assets/img/shape-7.webp" alt="Image" class="section-shape-two position-absolute bounce">
                <div class="row">
                    <div class="col-xl-6 offset-xl-3 col-md-8 offset-md-2" data-cue="slideInUp">
                        <div class="section-title text-center mb-50">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Client Testimonials</span>
                            <h2 class="mb-0">Hear from satisfied customers</h2>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                $stmt_test = $con -> prepare('SELECT * FROM testimonials ORDER BY id DESC'); 
                $stmt_test -> execute(); 
                $stmt_test -> store_result(); 
                $stmt_test -> bind_result($test_id,$test_name,$test_comment,$test_picture); 
                $numrows_test = $stmt_test -> num_rows();
                if($numrows_test > 0){
            ?>
            <div class="testimonial-slider-one swiper" data-cue="slideInUp">
                <div class="swiper-wrapper">
                    
                    <?php while ($stmt_test -> fetch()) { ?>
                    <div class="swiper-slide">
                        <div class="testimonial-card style-one">
                            <div class="client-img-wrap d-flex justify-content-between" style='height:50px;'>
                               
                                
                            </div>
                            <div class="client-quote bg-white ms-auto round-2">
                                <p>“<?= $test_comment; ?>”</p>
                                <div class="client-info-wrap d-flex flex-wrap align-items-center justify-content-between">
                                    <div class="client-info">
                                        <h5 class="fs-20"><?= $test_name; ?></h5>
                                       
                                    </div>
                                    <img src="assets/img/icons/square-quote.svg" alt="Image" class="quote-icon">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
                <div class="slider-pagination testimonial-pagination d-flex align-items-center justify-content-center"></div>
            </div>
        </div>
        <!-- Testimonial Section End -->
        <?php }  ?>


        <?php $yes = "Yes";
        $stmt_articles = $con -> prepare('SELECT article_id,title,category,preamble,picture,date FROM articles WHERE featured = ? LIMIT 3');
        $stmt_articles -> bind_param('s',$yes);
        $stmt_articles -> execute(); 
        $stmt_articles -> store_result(); 
        $stmt_articles -> bind_result($article_id,$a_title,$a_category,$a_preamble,$a_picture,$a_date); 
        $numrows_articles = $stmt_articles -> num_rows();
        if($numrows_articles > 0){      
        ?>
        <!-- Blog Section Start -->
        <div class="blog-wrap style-one position-relative index-1 ptb-130">
            <div class="container position-relative">
                <img src="assets/img/shape-5.webp" alt="Image" class="blog-shape-one position-absolute rotate">
                <img src="assets/img/shape-6.webp" alt="Image" class="blog-shape-two position-absolute bounce">
                <div class="row">
                    <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-8 offset-md-2" data-cue="slideInUp">
                        <div class="section-title text-center mb-45">
                            <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">Articles</span>
                            <h2 class="mb-0">Thoughts and Insights, Updated Weekly</h2>
                        </div>
                    </div>
                </div>
                <div class="blog-slider swiper" data-cue="slideInUp">
                    <div class="swiper-wrapper">
                        <?php while ($stmt_articles -> fetch()) { 
                            $stmt_sli = $con -> prepare('SELECT category_name FROM blog_categories WHERE id = ?'); 
                            $stmt_sli -> bind_param('i',$a_category);
                            $stmt_sli -> execute(); 
                            $stmt_sli -> store_result(); 
                            $stmt_sli -> bind_result($category_name); 
                            $numrows_sli = $stmt_sli -> num_rows();
                            if($numrows_sli > 0){
                                while ($stmt_sli -> fetch()) {}
                            }
                            $date_day = substr("$a_date",0,2);
                            $date_month = substr("$a_date",2,3); 
                        ?>
                        <div class="swiper-slide">
                            <div class="blog-card style-one">
                                <div class="blog-info">
                                    <ul class="blog-metainfo list-unstyle">
                                        <li class="d-inline-block position-relative fs-15"><i class="ri-user-3-line"></i><a href="">Admin</a></li>
                                        <li class="d-inline-block position-relative fs-15"><i class="ri-calendar-2-line"></i><a href=""><?php echo $date_day; ?> <?php echo $date_month; ?></a></li>
                                    </ul>
                                    <h3><a href="article_details.php?article_id=<?php echo $article_id; ?>"><?php echo $a_title; ?></a></h3>
                                   
                                </div>
                                <div class="blog-img position-relative round-2">
                                    <img src="site_img/articles/<?php echo $a_picture; ?>" alt="Image" class="transition round-2" style='object-fit:cover;height:250px;width:100%;'>
                                    <a href="article_details.php?article_id=<?php echo $article_id; ?>" class="blog-link d-flex flex-column align-items-center justify-content-center round-2 bg-white position-absolute start-50 transition"><img src="assets/img/icons/long-arrow-blue.svg" alt="Image"></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="slider-pagination blog-pagination d-flex flex-wrap align-items-center justify-content-center"></div>
                </div>
            </div>
        </div>
        <!-- Blog Section End -->
        <?php } ?>
       <?php include("footer.php"); ?>