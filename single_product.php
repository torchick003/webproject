<?php
$ip_add = getenv("REMOTE_ADDR");
include "db.php";

if(isset($_GET["singleProd"])){
	$limit = 9;
	$reviewCount = 0;
	$stars = 0;
	$p_id = $_GET["singleProd"];
	if(isset($_POST["setPage"])){
		$pageno = $_POST["pageNumber"];
		$start = ($pageno * $limit) - $limit;
	}else{
		$start = 0;
	}
	if(isset($_POST["submitReview"])){
	$uid = $_SESSION["uid"];
		$stars = $_POST["stars"];
		$review = $_POST["review"];
		$sql = "INSERT INTO user_feed
			(`user_id`, `product_id`, `reviewText`, `stars`) 
			VALUES ('$uid','$p_id','$review','$stars')";
			if(mysqli_query($con,$sql)){
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Review posted!</b>
					</div>
				";
			}
	}
	$product_query = "SELECT * FROM products WHERE product_id=$p_id";
	$run_query = mysqli_query($con,$product_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$pro_id    = $row['product_id'];
			$pro_cat   = $row['product_cat'];
			$pro_brand = $row['product_brand'];
			$pro_key =  $row['product_keywords'];
			$pro_title = $row['product_title'];
			$pro_price = $row['product_price'];
			$pro_image = $row['product_image'];


			$product_query = "SELECT * FROM categories WHERE cat_id=$pro_cat";
			$run_query = mysqli_query($con,$product_query);
				while($row = mysqli_fetch_array($run_query)){
					$pro_cat_name = $row['cat_title'];
				}
			$product_query = "SELECT * FROM brands WHERE brand_id=$pro_brand";
			$run_query = mysqli_query($con,$product_query);
				while($row = mysqli_fetch_array($run_query)){
					$pro_brand_name = $row['brand_title'];
				}


			echo "
				<div class='col-md-4'>
							<div class='panel panel-primary' style='overflow:hidden'>
								<div class='panel-heading'>Product Information</div>
								<div class='panel-body' style='width:290px; height:250px'>
									<img src='product_images/$pro_image' style='width:auto; height:200px; margin-right:auto; margin-left:auto;display:block'/>
								</div>
								<div class='panel-heading'>
								<p style='font-size:17px'><strong>Product Name:</strong> $pro_title</p>
								<p style='font-size:17px'><strong>Price:</strong> ₱$pro_price.00</p>
								<p style='font-size:17px'><strong>Product Category:</strong> $pro_cat_name</p>
								<p style='font-size:17px'><strong>Brand Name:</strong> $pro_brand_name</p>
								<p style='font-size:17px'><strong>Other Details:</strong> $pro_key</p>
								<p><div class='fb-share-button' data-href='http://localhost/ecomm/index.php' data-layout='button' data-size='small'><a target='_blank' href='https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flocalhost%2Fecomm%2Findex.php&amp;src=sdkpreparse' class='fb-xfbml-parse-ignore'><img src='images/6.png' style='width:15%'></a></div></p>
									<button pid='$pro_id' style='float:right;' id='product' class='btn btn-danger btn-xs'>AddToCart</button>
								</div>
							</div>
						</div>	

						<div class='col-lg-6' style='background-color:white' >";

	$product_query = "SELECT uf.reviewText, uf.reviewTime, uf.stars, ui.first_name, ui.last_name 
						FROM user_feed uf
						INNER JOIN user_info ui
						ON uf.user_id = ui.user_id 
						WHERE uf.product_id=$p_id";
	$run_query = mysqli_query($con,$product_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$rev_review    = $row['reviewText'];
			$rev_time   = $row['reviewTime'];
			$stars += $row['stars'];
			$reviewCount ++;
			$starsOnce = $row['stars'];
			$rev_first=  $row['first_name'];
			$rev_last = $row['last_name'];
			$time = array();
			$time = explode(" ", $rev_time);

						echo "
							<div class='comment_list'>
								<div class='review_item'>
									<div class='media'>
										<div class='d-flex'>
										</div>
										<div class='media-body'>
											<h4>$rev_first $rev_last</h4>";
						if($starsOnce == 1){
							echo "<p><img src='images/2.png'></p>";
						}
						else if($starsOnce == 2){
							echo "<p><img src='images/2.png'><img src='images/2.png'></p>";
						}
						else if($starsOnce == 3){
							echo "<p><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'></p>";
						}
						else if($starsOnce == 4){
							echo "<p><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'></p>";
						}
						else if($starsOnce == 5){
							echo "<p><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'></p>";
						}

						echo "

											<h5>$time[0] at $time[1]</h5>
											<a class='reply_btn' href='#'>Reply</a>
										</div>
									</div>
									<p>$rev_review</p>
								</div>
							</div>
						<hr>";
					}
				}
				else{
					echo "<h2>No reviews available</h2>
							<hr>";
				}
						if(isset($_SESSION['uid']))
						{	
							echo "
								<div class='panel-heading'><h4><strong>Tell us what you think about the product!</strong></h4></div>
								<form>
									<div class='col-md-12'>
										<div class='form-group'>
									<input type='radio' id='f-option2' name='selector' value='1' required>
									<label style='font-size:16px'> 1 <img src='images/2.png'></label><br>
									<input type='radio' id='f-option2' name='selector' value='2'required>
									<label style='font-size:16px'> 2 <img src='images/2.png'><img src='images/2.png'></label><br>
									<input type='radio' id='f-option2' name='selector' value='3' required>
									<label style='font-size:16px'> 3 <img src='images/2.png'><img src='images/2.png'><img src='images/2.png'></label><br>
									<input type='radio' id='f-option2' name='selector' value='4' required>
									<label style='font-size:16px'> 4 <img src='images/2.png'><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'></label><br>
									<input type='radio' id='f-option2' name='selector' value='5' required>
									<label style='font-size:16px'> 5 <img src='images/2.png'><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'><img src='images/2.png'></label>
										</div>
									</div>
									<div class='col-md-12'>
										<div class='form-group'>
											<textarea class='form-control' name='message' id='review' rows='3' placeholder='Write your review here!' required></textarea>
										</div>
									</div>
									<div class='col-md-12 text-right'>
										<button type='submit' pid='$pro_id' value='submit' class='btn submit_btn' id='submitReview'>Submit Review</button>
									</div>
								</form>
						</div> ";
					}
					else
						echo "</div>";
		}
	}
}

?>