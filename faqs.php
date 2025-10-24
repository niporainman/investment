<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']);
$page_title = "Frequently Asked Questions";
$page_header = "7525c64aca.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
<!-- FAQ Section Start -->
        <div class="faq-wrap position-relative ptb-130 index-1">
            <div class="container">
                <div class="row align-items-xxl-start center align-items-center">
                    <div class="col-lg-6" data-cue="slideInUp">
                        <div class="faq-img">
                            <img src="site_img/general/13ddf1e74a.jpg" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-6" data-cue="slideInUp">
                        <div class="faq-content">
                            <div class="section-title mb-25">
                                <span class="section-subtitle d-inline-block fs-15 fw-semibold text-title">FAQs</span>
                                <h2 class="mb-15">Find Answers. Be Informed</h2>
                                <p>Browse through our frequently asked questions to find answers to any enquires you may have. If you can't find your question please contact us <a href="contact">here</a></p>
                            </div>
                            <div class="accordion" id="accordionExample">
                                <?php
								$stmt = $con -> prepare('SELECT * FROM faqs');
								$stmt -> execute(); 
								$stmt -> store_result(); 
								$stmt -> bind_result($id,$question,$answer); 
								$numrows = $stmt -> num_rows();
								if($numrows > 0){
									while ($stmt -> fetch()) { 
							    ?>
                                <div class="accordion-item round-2">
                                    <h2 class="accordion-header" id="heading<?= $id ?>">
                                        <button class="accordion-button collapsed fs-20 text-title" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse<?= $id ?>"
                                            aria-expanded="false" aria-controls="collapse<?= $id ?>">
                                            <span class="d-flex flex-column align-items-center justify-content-center">
                                                <i class="ri-arrow-down-s-fill"></i>
                                            </span>
                                            <?= $question ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $id ?>" class="accordion-collapse collapse"
                                        aria-labelledby="heading<?= $id ?>" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p><?= $answer ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FAQ Section End -->
<?php include("footer.php"); ?>