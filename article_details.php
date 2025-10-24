<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
$page_header = "6916609cf7.jpg";
include("header.php"); ?>
<link rel="stylesheet" href="blog.css">
<?php
	if (isset($_GET['article_id'])){
	$article_id = mysqli_real_escape_string($con,$_GET['article_id']);
	$stmt = $con -> prepare('SELECT * FROM articles WHERE article_id=?');
	$stmt -> bind_param('s',$article_id);
	$stmt -> execute(); 
	$stmt -> store_result(); 
	$stmt -> bind_result($id,$article_id,$title,$category_id,$preamble,$paragraph,$picture,$featured,$date,$keywordss,$comments_allowed);
	$numrows = $stmt -> num_rows();
	if($numrows > 0){
		while ($stmt -> fetch()) {
			$date_day = substr("$date",0,2);
			$date_month = substr("$date",2,3);
			$date_year = substr("$date",5);
			$date_formatted = " $date_day $date_month $date_year";	
		}
	}
	$stmtt = $con -> prepare('SELECT * FROM blog_categories WHERE id=?');
	$stmtt -> bind_param('s',$category_id);
	$stmtt -> execute(); 
	$stmtt -> store_result(); 
	$stmtt -> bind_result($category_id_db,$category_name_db);
	while ($stmtt -> fetch()){}
	
}
else{echo "<meta http-equiv=\"refresh\" content=\"0; url=$link\">";exit();}
?>
<?php $page_title = "$title"; ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
  	 <br><br>
  <section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
      <div class="row gy-4">
        <div class="col-xl-9 col-lg-8 col-12 ratio50_2">
          <div class="row">
            <div class="col-12">
              <div class="blog-main-box blog-details">
                <div>
                  <div class="blog-img"> <img class="img-fluid bg-img" src="site_img/articles/<?php echo $picture; ?>"
                      alt=""></div>
                </div>
                <div class="blog-content"><span class="blog-date"><?php echo $date_formatted; ?></span><a
                    href="">
                    <h1><?php echo $title; ?></h1> <br>
                  </a>
                  <p><?php echo $paragraph; ?></p>
                  
                  
                  <div class="comments-box">
                  <?php //count comments 

$yes="Yes";
if($comments_allowed =="Yes"){
	$stmt_ca = $con -> prepare('SELECT id FROM comments WHERE article_id=? AND display=?');
	$stmt_ca -> bind_param('ss',$article_id,$yes);
	$stmt_ca -> execute(); 
	$stmt_ca -> store_result(); 
	$stmt_ca -> bind_result($commm_id);
	$numrows_cd = $stmt_ca -> num_rows();
?>
<h5><?php echo $numrows_cd; ?> Comment(s)</h5>
<?php }
else{echo"";}
 ?>
                    <ul class="theme-scrollbar">
                    <?php $yes='Yes';
if($comments_allowed =="Yes"){
	$stmt_c = $con -> prepare('SELECT * FROM comments WHERE article_id=? AND display=?');
	$stmt_c -> bind_param('ss',$article_id,$yes);
	$stmt_c -> execute(); 
	$stmt_c -> store_result(); 
	$stmt_c -> bind_result($comm_id,$article_id,$name,$email,$comment,$display,$comm_date);
	$numrows_c = $stmt_c -> num_rows();
	if($numrows_c > 0){
		while ($stmt_c -> fetch()) {
			if($display=="Yes"){
?>
                      <li>
                        <div class="comment-items">
                          <div class="user-img"></div>
                          <div class="user-content">
                            <div class="user-info">
                              <div class="d-flex justify-content-between gap-3">
                                <h6> <i class="iconsax" data-icon="user-1"></i><?php echo $name; ?></h6><span> <i class="iconsax"
                                    data-icon="clock"></i><?php echo $comm_date; ?></span>
                              </div>
                             
                            </div>
                            <p><?php echo $comment; ?></p>
                          </div>
                        </div>
                      </li>
<?php } } } } ?>
                    </ul>
                  </div>
<?php if($comments_allowed =="Yes"){ ?>
  <?php $comm_response="";
if (isset($_POST["sub_com"])) {
	$display = "Yes";
	$name = mysqli_real_escape_string($con,$_POST['name']);
	$email = mysqli_real_escape_string($con,$_POST['email']);
	$comment = mysqli_real_escape_string($con,$_POST['comment']);
	$date_comm = date("D,dS M, Y g:ia");

	$db_id=0;
	$stmt_s = $con -> prepare('INSERT INTO comments VALUES (?,?,?,?,?,?,?)');
	$stmt_s -> bind_param('issssss', $db_id,$article_id,$name,$email,$comment,$display,$date_comm);
	$stmt_s -> execute();
	
	$comm_response = "Thanks for your comment $name";
}
?>
                  <h5 class="pt-3">Leave a Comment</h5>
                  <p style='color:forestgreen;'><?php echo $comm_response; ?></p>
                  <form action="article_details?article_id=<?php echo $article_id; ?>" method="post">
                  <div class="row gy-3 message-box">
                    <div class="col-sm-6"> <label class="form-label">Full Name</label><input style='border:1px solid #333;border-radius:5px;' class="form-control"
                        type="text" name='name' required></div>
                    <div class="col-sm-6"> <label class="form-label">Email address</label><input style='border:1px solid #333;border-radius:5px;' class="form-control"
                        type="email" name='email' required></div>
                        
                    <div class="col-12"> <label class="form-label">Message</label><textarea style='border:1px solid #333;border-radius:5px;' class="form-control"
                        id="message" rows="6" name='comment' required></textarea></div>
                    <div class="col-12"> <button class="btn btn_black rounded sm" type="submit" name='sub_com'>Post Comment </button>
                    </div>
                  </div>
</form>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4">
          <div class="blog-sidebar">
            <div class="row gy-4">
              <div class="col-12">
              <form action="search_blog.php" method='post' class='no_style'>
                <div class="blog-search"> <input name='search' type="search" placeholder="Search Here..." required>
                <button type='submit' name='search_for'>
                  <i class="iconsax" data-icon="search-normal-2"></i>
                </button>
                </div>
              </form>
              </div>
              <div class="col-12">
                <div class="sidebar-box">
                  <div class="sidebar-title">
                    <div class="loader-line"></div>
                    <h5> Categories</h5>
                  </div>
                  <ul class="categories">
                  <?php
	$stmt = $con -> prepare('SELECT * FROM blog_categories'); 
	$stmt -> execute(); 
	$stmt -> store_result(); 
	$stmt -> bind_result($catt_id,$category_name); 
	$numrows = $stmt -> num_rows();
	if($numrows > 0){
		while ($stmt -> fetch()) {
			$stmt_fun = $con -> prepare('SELECT id FROM articles WHERE category=?');
			$stmt_fun -> bind_param('i',$catt_id);
			$stmt_fun -> execute(); 
			$stmt_fun -> store_result(); 
			$stmt_fun -> bind_result($caid); 
			$numrows_fun = $stmt_fun -> num_rows();
		
?>
<li><p><a href="blog_category_details?category_id=<?php echo $catt_id; ?>"><?php echo $category_name ?></a><span><?php echo $numrows_fun; ?></span></p></li>
	<?php } } ?>
                    
                  </ul>
                </div>
              </div>
              <div class="col-12">
                <div class="sidebar-box">
                  <div class="sidebar-title">
                    <div class="loader-line"></div>
                    <h5> Featured Posts</h5>
                  </div>
                  <ul class="top-post">
  <?php $yes="Yes";$four=4;
	$stmt_f = $con -> prepare('SELECT * FROM articles WHERE featured=? ORDER BY RAND() LIMIT ?');
	$stmt_f -> bind_param('si',$yes,$four);
	$stmt_f -> execute(); 
	$stmt_f -> store_result(); 
	$stmt_f -> bind_result($idf,$article_idf,$titlef,$category_idf,$preamblef,$paragraphf,$picturef,$featuredf,$datef,$keywordsf,$comments_allowedf); 
	$numrows_f = $stmt_f -> num_rows();
		
	if($numrows_f > 0){
		while ($stmt_f -> fetch()) {
			$stmt_cat = $con -> prepare('SELECT * FROM blog_categories WHERE id = ?');
		$stmt_cat -> bind_param('i',$category_idf);
		$stmt_cat -> execute(); 
		$stmt_cat -> store_result(); 
		$stmt_cat -> bind_result($cat_iddf,$category_namef); 
		while ($stmt_cat -> fetch()){}
			
			$date_dayf = substr("$datef",0,2);
			$date_monthf = substr("$datef",2,3);
			$date_yearf = substr("$datef",5);
			$date_formattedf = " $date_monthf $date_dayf, $date_yearf";
		?>
                    <li> <img class="img-fluid" src="site_img/articles/<?php echo $picturef; ?>" alt="">
                      <div> <a href="article_details?article_id=<?php echo $article_idf; ?>">
                          <h6><?php echo $titlef; ?></h6>
                        </a>
                        <p><?php echo $date_formattedf ?></p>
                      </div>
                    </li>
<?php } } ?>
                   
                  </ul>
                </div>
              </div>
              
              
              <div class="col-12 d-none d-lg-block">
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include("footer.php"); ?>