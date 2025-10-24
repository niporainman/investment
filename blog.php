<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']);
$page_title = "News & Events";
$page_header = "6916609cf7.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
<link rel="stylesheet" href="blog.css">
		<br><br>
  <section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
      <div class="row gy-4">
        <div class="col-xl-9 col-lg-8 ratio50_2">
          <div class="row gy-4 sticky">
          <?php $cat_id=1;
	$sql = "SELECT COUNT(id) FROM articles ORDER BY id DESC";
	$query = mysqli_query($con, $sql);
	$row = mysqli_fetch_row($query);
	//here we have the total row count
	$rows = $row[0];
	//number of results we want per page
	$page_rows = 9;
	//tells us the page number of our last page
	$last = ceil($rows/$page_rows);
	//this makes sure last cannot be less than 1
	if($last < 1){$last = 1;}
	//establish the page num variable
	$pagenum = 1;
	//Get pageum from $GET if it is present, else its 1
	if(isset($_GET['pn'])){
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
	}
	//below 1 or more than last page
	if($pagenum < 1){
	   $pagenum = 1;
	}else if ($pagenum > $last) {
		$pagenum = $last;
	}
	//this sets the range of rows to query for the chosen pagenum
	$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
	//grabs one page worth of rows
	$sql = "SELECT * FROM articles ORDER BY id DESC $limit";
	$query = mysqli_query($con, $sql);
	//this shows the user what page they on and total number
	$textline1 = "Messages $rows";
	$textline2 = "Page $pagenum of $last";
	//establish the pagination controls
	$paginationCtrls = "";
	//if there is more than one page worth of results
	if($last != 1){
		if($pagenum > 1){
			$previous = $pagenum - 1;
			//$paginationCtrls .='<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a> &nbsp';
			//$paginationCtrls .='<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Prev</a></li>';
			$paginationCtrls .= "
      <li>
        <a class='prev' href=\"$_SERVER[PHP_SELF]?pn=$previous&id=$cat_id\">
          <i class='iconsax' data-icon='chevron-left'></i>
        </a>
      </li>";
			//render clickable links to the left of target page number
			for($i = $pagenum-4; $i < $pagenum; $i++){
				if($i > 0){
					//$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'"></a> &nbsp; ';
					//$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
					$paginationCtrls .= "
          <li>
            <a href=\"$_SERVER[PHP_SELF]?pn=$i&id=$cat_id\">$i</a>
          </li>";
				}
			}
		}
		//render target number bt not link
		//$paginationCtrls .= ''.$pagenum.' &nbsp; ';
		$paginationCtrls .= '<li><a class="active" href="#">'.$pagenum.'</a></li>';
		//render clickable number links to the right of target number
		for($i = $pagenum+1; $i <= $last; $i++){
			//$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
			//$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
			$paginationCtrls .= "
      <li>
        <a href=\"$_SERVER[PHP_SELF]?pn=$i&id=$cat_id\">$i</a>
      </i>";
			
			if($i >= $pagenum+4){
				break;
			}
		}
		//this does the same as above, only checking if we are on the last page
		if($pagenum != $last) {
			$next = $pagenum + 1;
			//$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a></li>';
			$paginationCtrls .= "
      <li>
        <a href=\"$_SERVER[PHP_SELF]?pn=$next&id=$cat_id\">
          <i class='iconsax' data-icon='chevron-right'></i>
        </a>
      </li>";
		}
	}
	if(mysqli_num_rows($query) > 0){
		while ($row_back_deals = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
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
			while ($stmt_cat -> fetch()){}

?>
            <div class="col-xl-4 col-sm-6">
              <div class="blog-main-box">
                <div>
                  <div class="blog-img"> <img class="img-fluid bg-img" src="site_img/articles/<?php echo $picture; ?>"
                      alt=""></div>
                </div>
                <div class="blog-content"> <span class="blog-date"><?php echo $date_month; ?> <?php echo $date_day; ?>, <?php echo $date_year; ?> - <?php echo $category_name; ?></span><a
                    href="article_details?article_id=<?php echo $article_id; ?>">
                    <h4><?php echo $title; ?></h4>
                  </a>
                  <p><?php echo $preamble; ?></p>
                  <div class="share-box">
                    <div class="d-flex align-items-center gap-2">
                      <h6></h6>
                    </div><a href="article_details?article_id=<?php echo $article_id; ?>"> Read More..</a>
                  </div>
                </div>
              </div>
            </div>
<?php } } ?>


            <div class="pagination-wrap mt-0" style='font-size:16px;'>
              <ul class="pagination" style='font-size:16px;'>
                <?php echo $paginationCtrls; ?>
              </ul>
              <?php echo "<span style='font-size:16px;'>$textline2</span>"; ?>
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