<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']); 
include("header.php"); ?>
<?php $page_title = "Search Blog"; $page_header = "6916609cf7.jpg"; ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
 <link rel="stylesheet" href="blog.css">
<style>h4,h2,h3,h5,h1,h6{color:#333;}</style>
	<br><br>
  <section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
      <div class="row gy-4">
        <div class="col-xl-9 col-lg-8 ratio50_2">
          
          <?php
if(isset($_POST["search_for"])){
	$search_term = $_POST["search"];
	$search_term = trim("$search_term");
}
?>
<?php
	if(isset($_POST["search_for"])){					
	$product_query = "SELECT * FROM articles WHERE keywords LIKE '%$search_term%' ORDER BY id DESC
	";
	$run_query = mysqli_query($con,$product_query);
	$numrows = mysqli_num_rows($run_query);
	if(mysqli_num_rows($run_query) > 0){
		echo" <br><br>
	<div class='row gy-4'> <br><br>
	<form method='post' action='index.php' style='width:100%;margin:auto;'>
		<h4>You searched for \"$search_term\", $numrows result(s) found.</h4>
		<input class='btn btn_black rounded sm' style='width:100px;' type='submit' name='back' value='Back'><br/><br/>
	</form>
	<br/><br/>
	</div>
	<div class='row gy-4 sticky'>
	";
		while($row_back_deals = mysqli_fetch_array($run_query)){
			$article_id = $row_back_deals['article_id'];
			$title = $row_back_deals['title'];
			$category_id = $row_back_deals['category'];
			$preamble = $row_back_deals['preamble'];
			$paragraph = $row_back_deals['paragraph'];
			$picture = $row_back_deals['picture'];
			$featured = $row_back_deals['featured'];
			$date = $row_back_deals['date'];
			$keywords = $row_back_deals['keywords'];
			$comments_allowed = $row_back_deals['comments_allowed'];
			$date_day = substr("$date",0,2);
			$date_month = substr("$date",2,3);
			$date_year = substr("$date",5);
			$date_formatted = " $date_day $date_month $date_year";
			$stmt_cat = $con -> prepare('SELECT * FROM blog_categories WHERE id = ?');
			$stmt_cat -> bind_param('i',$category_id);
			$stmt_cat -> execute(); 
			$stmt_cat -> store_result(); 
			$stmt_cat -> bind_result($cat_idd,$category_name); 

?>    
            <div class="col-xl-4 col-sm-6">
              <div class="blog-main-box">
                <div>
                  <div class="blog-img"> <img class="img-fluid bg-img" src="site_img/articles/<?php echo $picture; ?>"
                      alt=""></div>
                </div>
                <div class="blog-content"> <span class="blog-date"><?php echo $date_month; ?> <?php echo $date_day; ?>, <?php echo $date_year; ?> - <?php echo $category_name; ?></span><a
                    href="article_details.php?article_id=<?php echo $article_id; ?>">
                    <h4><?php echo $title; ?></h4>
                  </a>
                  <p><?php echo $preamble; ?></p>
                  <div class="share-box">
                    <div class="d-flex align-items-center gap-2">
                      <h6></h6>
                    </div><a href="article_details.php?article_id=<?php echo $article_id; ?>"> Read More..</a>
                  </div>
                </div>
              </div>
            </div>
            <?php
		}
	}
	else{echo"
	<form method='post' action='index.php' style='width:100%;margin:auto;'>
		<h4>You searched for \"$search_term\", $numrows result(s) found.</h4>
		<button class='btn btn_black rounded sm' style='width:100px;' type='submit' name='back'>Back</button>
	</form>
	<br/>
	<div class='row gy-4 sticky'>
	";}
	}
	else{echo"<div class='row gy-4 sticky'>";}
?>


           

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
<li><p><a href="blog_category_details.php?category_id=<?php echo $catt_id; ?>"><?php echo $category_name ?></a><span><?php echo $numrows_fun; ?></span></p></li>
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
                      <div> <a href="article_details.php?article_id=<?php echo $article_idf; ?>">
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