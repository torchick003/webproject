<?php
session_start();
$ip_add = getenv("REMOTE_ADDR");
include "db.php";
if(isset($_POST["category"])){
	$category_query = "SELECT * FROM categories";
	$run_query = mysqli_query($con,$category_query) or die(mysqli_error($con));
	$json = array();
/*	echo "
		<div class='nav nav-pills nav-stacked'>
			<li class='active'><a href='#'><h4>Categories</h4></a></li>
	"; */
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$cid = $row["cat_id"];
			$cat_name = $row["cat_title"];
			array_push($json, array($cid,$cat_name));
		/*	echo "
					<li><a href='#' class='category' cid='$cid'>$cat_name</a></li>
			"; */
		}
		$json2 = json_encode($json);
		echo $json2;
	//	echo "</div>";
	}
}
if(isset($_POST["brand"])){
	$brand_query = "SELECT * FROM brands";
	$run_query = mysqli_query($con,$brand_query);
	echo "
		<div class='nav nav-pills nav-stacked'>
			<li class='active'><a href='#'><h4>Brands</h4></a></li>
	";
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$bid = $row["brand_id"];
			$brand_name = $row["brand_title"];
			echo "
					<li><a href='#' class='selectBrand' bid='$bid'>$brand_name</a></li>
			";
		}
		echo "</div>";
	}
}
if(isset($_POST["page"])){
	$sql = "SELECT * FROM products";
	$run_query = mysqli_query($con,$sql);
	$count = mysqli_num_rows($run_query);
	$pageno = ceil($count/9);
	for($i=1;$i<=$pageno;$i++){
		echo "
			<li><a href='#' page='$i' id='page'>$i</a></li>
		";
	}
}
if(isset($_POST["getProduct"])){
	$limit = 9;
	if(isset($_POST["setPage"])){
		$pageno = $_POST["pageNumber"];
		$start = ($pageno * $limit) - $limit;
	}else{
		$start = 0;
	}
	$product_query = "SELECT * FROM products LIMIT $start,$limit";
	$run_query = mysqli_query($con,$product_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$pro_id    = $row['product_id'];
			$pro_cat   = $row['product_cat'];
			$pro_brand = $row['product_brand'];
			$pro_title = $row['product_title'];
			$pro_price = $row['product_price'];
			$pro_image = $row['product_image'];
			echo "
				<div class='col-md-4'>
							<div class='panel panel-info' style='overflow:hidden'>
								<div class='panel-heading'>$pro_title
									<button pid='$pro_id' style='float:right;' id='productinfo' class='btn btn-success btn-xs'>View Product</button></div>
								<div class='panel-body' style='width:290px; height:250px'>
									<img src='product_images/$pro_image' style='width:auto; height:200px; margin-right:auto; margin-left:auto;display:block'/>
								</div>
								<div class='panel-heading'>₱.$pro_price.00
									<button pid='$pro_id' style='float:right;' id='product' class='btn btn-danger btn-xs'>AddToCart</button>
								</div>
							</div>
						</div>	
			";
		}
	}
}
if(isset($_POST["sendMsg"]) || isset($_POST['loadMsg'])){
	$uid = $_SESSION["uid"];
	if(isset($_POST["sendMsg"])){
		$msg = $_POST["msg"];
		$sql = "INSERT INTO user_msg
			(`user_id`, `msgText`, sender) 
			VALUES ('$uid','$msg', 'user')";
			if(mysqli_query($con,$sql)){
		
			}
	}
	$msg_query = "SELECT MAX(msg_id)
						FROM user_msg
						WHERE user_id=$uid";
	$run_query = mysqli_query($con,$msg_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$lastRow = $row[0];
		}
	}
	$msg_query = "SELECT um.msgText, um.msgTime, um.sender, ui.first_name, ui.last_name 
						FROM user_msg um
						INNER JOIN user_info ui
						ON um.user_id = ui.user_id 
						WHERE um.user_id=$uid";
	$run_query = mysqli_query($con,$msg_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$msg_text    = $row['msgText'];
			$msg_time   = $row['msgTime'];
			$msg_sender=  $row['sender'];
			$msg_first=  $row['first_name'];
			$msg_last = $row['last_name'];
			$time = array();
			$time = explode(" ", $msg_time);
			if($msg_sender == "user"){
						echo "
							<div class='outgoing_msg'>
              <div class='sent_msg'>
                <p id='msgTxt' lastMsg='$lastRow'>$msg_text</p>
                <span class='time_date'> $time[1]    |    $time[0]</span> </div>
            </div>";
            	}
            	else{
						echo "
            			<div class='incoming_msg'>
              			<div class='incoming_msg_img'> </div>
              			<div class='received_msg'>
                			<div class='received_withd_msg'>

                			<p><strong>Shop Hubb</strong></p>
                <p id='msgTxt' lastMsg='$lastRow'>$msg_text</p>
                <span class='time_date'> $time[1]    |    $time[0]</span> </div>
              			</div>
            			</div> ";
					}
            }
		}
		else{
				echo "<h2>No messages available</h2>
							<hr>";
		}
}
if(isset($_POST["sendAdminMsg"]) || isset($_POST['loadAdminMsg']) || isset($_POST['loadAdminMsgRe']) || isset($_POST['msgNameClick'])){
	if(!isset($_POST['uid']) && isset($_SESSION["firstAdminMsg"]))
		{$uid = $_SESSION["firstAdminMsg"];
	}
        else if(!isset($_POST['uid']) && !isset($_SESSION["firstAdminMsg"])){
                $uid = -1;
        }
	else
		{$uid = $_POST["uid"];
	}
	if(isset($_POST["sendAdminMsg"])){
		$msg = $_POST["msg"];
		$sql = "INSERT INTO user_msg
			(`user_id`, `msgText`, sender) 
			VALUES ('$uid','$msg', 'admin')";
			if(mysqli_query($con,$sql)){
		
			}
	}


	$msg_query = "SELECT MAX(msg_id) FROM user_msg WHERE user_id=$uid";
	$run_query = mysqli_query($con,$msg_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$lastRow = $row[0];
		}
	}

	$msg_query = "SELECT um.msg_id, um.user_id, um.msgText, um.msgTime, um.sender, ui.first_name, ui.last_name 
						FROM user_msg um
						INNER JOIN user_info ui
						ON um.user_id = ui.user_id 
						WHERE um.user_id=$uid";
	$run_query = mysqli_query($con,$msg_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$_SESSION['lastAdminChat'] = $row['msg_id'];
			$msg_uid = $row['user_id'];
			$msg_text    = $row['msgText'];
			$msg_time   = $row['msgTime'];
			$msg_sender=  $row['sender'];
			$msg_first=  $row['first_name'];
			$msg_last = $row['last_name'];
			$time = array();
			$time = explode(" ", $msg_time);
			if($msg_sender != "user"){
						echo "
							<div class='outgoing_msg'>
              <div class='sent_msg'>
                <p id='msgBody' uid='$uid' last='$lastRow'>$msg_text</p>
                <span class='time_date'> $time[1]    |    $time[0]</span> </div>
            </div>";
            	}
            	else{
						echo "
            			<div class='incoming_msg'>
              			<div class='incoming_msg_img'> </div>
              			<div class='received_msg'>
                			<div class='received_withd_msg'>
                			<p><strong>$msg_first $msg_last</strong></p>
                <p id='msgBody' uid='$uid' last='$lastRow'>$msg_text</p>
                <span class='time_date'> $time[1]    |    $time[0]</span> </div>
              			</div>
            			</div> ";
					}
            }
		}
		else{
				echo "<h2>No messages available</h2>
							<hr>";
		}
}

if(isset($_POST['msgNames'])){
	$uid = $_SESSION["uid"];
	$msg_query = "SELECT um.msgText, um.msgTime, um.sender, ui.first_name, ui.last_name 
						FROM user_msg um
						INNER JOIN user_info ui
						ON um.user_id = ui.user_id 
						WHERE um.user_id=$uid
						ORDER BY um.msg_id DESC
						LIMIT 1";
	$run_query = mysqli_query($con,$msg_query);
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$msg_text    = $row['msgText'];
			$msg_time   = $row['msgTime'];
			$msg_sender=  $row['sender'];
			$msg_first=  $row['first_name'];
			$msg_last = $row['last_name'];
			$time = array();
			$time = explode(" ", $msg_time);
			if($msg_sender == "user"){
						echo "
							 <div class='chat_list active_chat'>
              <div class='chat_people'>
                <div class='chat_ib'>
                  <h5><strong>$msg_first $msg_last </strong><span class='chat_date'>$time[0]</span></h5>
                  <p>$msg_text</p>
                </div>
              </div>
            </div>";
            	}
            	else{
						echo "
							 <div class='chat_list active_chat'>
              <div class='chat_people'>
                <div class='chat_ib'>
                  <h5><strong>Shob Hubb </strong><span class='chat_date'>$time[0]</span></h5>
                  <p>$msg_text</p>
                </div>
              </div>
            </div>";
					}
            }
		}
		else{
				echo "<h2> </h2>
							<hr>";
		}
}

if(isset($_POST["msgAdminNames"])){
	$i = 0;
	$msg_query = "SELECT user_id, MAX(msg_id)
						FROM user_msg
						GROUP BY user_id 
						ORDER BY MAX(msg_id) DESC";
	$run_query = mysqli_query($con,$msg_query);  
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$uid = $row['user_id'];
	$msg_query = "SELECT um.msgText, um.msgTime, um.sender, ui.first_name, ui.last_name
						FROM user_msg um
						INNER JOIN user_info ui
						ON um.user_id = ui.user_id 
						WHERE um.user_id = $uid
						ORDER BY um.msg_id DESC
						LIMIT 1";
	$run2_query = mysqli_query($con,$msg_query); 
	if(mysqli_num_rows($run2_query) > 0){
		while($row = mysqli_fetch_array($run2_query)){
				$msg_text    = $row['msgText'];
				$msg_time   = $row['msgTime'];
			$msg_sender=  $row['sender'];
			$msg_first=  $row['first_name'];
			$msg_last = $row['last_name'];
			} 
			$time = array();
			$time = explode(" ", $msg_time);
			if($i==0){
			$_SESSION['firstAdminMsg'] = $uid;
			if($msg_sender == "user"){
						echo "
							 <div class='chat_list active_chat' style='cursor:pointer' uid='$uid' id='msgNameClick'>
              <div class='chat_people'>
                <div class='chat_ib'>
                	<h4><strong>Conversation with $msg_first $msg_last </strong></h4>
                  <h5><strong>$msg_first $msg_last </strong><span class='chat_date'>$time[0] at $time[1]</span></h5>
                  <p>$msg_text</p>
                </div>
              </div>
            </div>";
            	}
            	else{
						echo "
							 <div class='chat_list active_chat' style='cursor:pointer' uid='$uid' id='msgNameClick'>
              <div class='chat_people'>
                <div class='chat_ib'>
                	<h4><strong>Conversation with $msg_first $msg_last </strong></h4>
                  <h5><strong>Shob Hubb </strong><span class='chat_date'>$time[0] at $time[1]</span></h5>
                  <p>$msg_text</p>
                </div>
              </div>
            </div>";
					}
					$i = 1;
				}
				else{
			if($msg_sender == "user"){
						echo "
							 <div class='chat_list' style='cursor:pointer' uid='$uid' id='msgNameClick'>
              <div class='chat_people'>
                <div class='chat_ib'>
                	<h4><strong>Conversation with $msg_first $msg_last </strong></h4>
                  <h5><strong>$msg_first $msg_last </strong><span class='chat_date'>$time[0] at $time[1]</span></h5>
                  <p>$msg_text</p>
                </div>
              </div>
            </div>";
            	}
            	else{
						echo "
							 <div class='chat_list' style='cursor:pointer' uid='$uid' id='msgNameClick'>
              <div class='chat_people'>
                <div class='chat_ib'>
                	<h4><strong>Conversation with $msg_first $msg_last </strong></h4>
                  <h5><strong>Shob Hubb </strong><span class='chat_date'>$time[0] at $time[1]</span></h5>
                  <p>$msg_text</p>
                </div>
              </div>
            </div>";
					}

				}
            }
        }
    }
		
		else{
				echo "<h2> </h2>
							<hr>";
		}
}
if(isset($_POST["getProductDetail"]) || isset($_POST["submitReview"])){
	$limit = 9;
	$reviewCount = 0;
	$stars = 0;
	$p_id = $_POST["proId"];
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
								<p><div class='fb-share-button' data-href='http://localhost/ecomm/index.php' data-layout='button' data-size='small'><a target='_blank' href='https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flocalhost%2Fecomm%2Findex.php?singleProd=$p_id&amp;src=sdkpreparse' class='fb-xfbml-parse-ignore' style='color:white;font-size:16px'><img src='images/6.png' style='width:15%'>  Share this on Facebook!</a></div></p>
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
if(isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])){
	if(isset($_POST["get_seleted_Category"])){
		$id = $_POST["cat_id"];
		$sql = "SELECT * FROM products WHERE product_cat = '$id'";
	}else if(isset($_POST["selectBrand"])){
		$id = $_POST["brand_id"];
		$sql = "SELECT * FROM products WHERE product_brand = '$id'";
	}else {
		$keyword = $_POST["keyword"];
		$sql = "SELECT * FROM products WHERE product_keywords LIKE '%$keyword%'";
	}
	
	$run_query = mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($run_query)){
			$pro_id    = $row['product_id'];
			$pro_cat   = $row['product_cat'];
			$pro_brand = $row['product_brand'];
			$pro_title = $row['product_title'];
			$pro_price = $row['product_price'];
			$pro_image = $row['product_image'];
			echo "
				<div class='col-md-4'>
							<div class='panel panel-info' style='overflow:hidden'>
								<div class='panel-heading'>$pro_title
									<button pid='$pro_id' style='float:right;' id='productinfo' class='btn btn-success btn-xs'>View Product</button></div>
								<div class='panel-body' style='width:290px; height:250px'>
									<img src='product_images/$pro_image' style='width:auto; height:200px;margin-right:auto; margin-left:auto;display:block'/>
								</div>
								<div class='panel-heading'>₱.$pro_price.00
									<button pid='$pro_id' style='float:right;' id='product' class='btn btn-danger btn-xs'>AddToCart</button>
								</div>
							</div>
						</div>	
			";
		}
	}
	


	if(isset($_POST["addToCart"])){
		

		$p_id = $_POST["proId"];
		

		if(isset($_SESSION["uid"])){

		$user_id = $_SESSION["uid"];

		$sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id'";
		$run_query = mysqli_query($con,$sql);
		$count = mysqli_num_rows($run_query);
		if($count > 0){
			echo "
				<div class='alert alert-warning'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is already added into the cart Continue Shopping..!</b>
				</div>
			";//not in video
		} else {
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','$user_id','1')";
			if(mysqli_query($con,$sql)){
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is Added..!</b>
					</div>
				";
			}
		}
		}else{
			$sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
			$query = mysqli_query($con,$sql);
			if (mysqli_num_rows($query) > 0) {
				echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>";
					exit();
			}
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','-1','1')";
			if (mysqli_query($con,$sql)) {
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Your product is Added Successfully..!</b>
					</div>
				";
				exit();
			}
			
		}
		
		
		
		
	}

//Count User cart item
if (isset($_POST["count_item"])) {
	//When user is logged in then we will count number of item in cart by using user session id
	if (isset($_SESSION["uid"])) {
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
	}else{
		//When user is not logged in then we will count number of item in cart by using users unique ip address
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
	}
	
	$query = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($query);
	echo $row["count_item"];
	exit();
}
//Count User cart item

//Get Cart Item From Database to Dropdown menu
if (isset($_POST["Common"])) {

	if (isset($_SESSION["uid"])) {
		//When user is logged in this query will execute
		$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
	}else{
		//When user is not logged in this query will execute
		$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.ip_add='$ip_add' AND b.user_id < 0";
	}
	$query = mysqli_query($con,$sql);
	if (isset($_POST["getCartItem"])) {
		//display cart item in dropdown menu
		if (mysqli_num_rows($query) > 0) {
			$n=0;
			while ($row=mysqli_fetch_array($query)) {
				$n++;
				$product_id = $row["product_id"];
				$product_title = $row["product_title"];
				$product_price = $row["product_price"];
				$product_image = $row["product_image"];
				$cart_item_id = $row["id"];
				$qty = $row["qty"];
				echo '
					<div class="row">
						<div class="col-md-3">'.$n.'</div>
						<div class="col-md-3"><img class="img-responsive" src="product_images/'.$product_image.'" /></div>
						<div class="col-md-3">'.$product_title.'</div>
						<div class="col-md-3">₱'.$product_price.'</div>
					</div>';
				
			}
			?>
				<a style="float:right;" href="cart.php" class="btn btn-warning">Checkout&nbsp;&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
			<?php
			exit();
		}
	}
	if (isset($_POST["checkOutDetails"])) {
		if (mysqli_num_rows($query) > 0) {
			//display user cart item with "Ready to checkout" button if user is not ©
			echo "<form method='post' action='login_form.php'>";
				$n=0;
				while ($row=mysqli_fetch_array($query)) {
					$n++;
					$product_id = $row["product_id"];
					$product_title = $row["product_title"];
					$product_price = $row["product_price"];
					$product_image = $row["product_image"];
					$cart_item_id = $row["id"];
					$qty = $row["qty"];

					echo 
						'<div class="row">
								<div class="col-md-2">
									<div class="btn-group">
										<a href="#" remove_id="'.$product_id.'" class="btn btn-danger remove"><span class="glyphicon glyphicon-trash"></span></a>
										<a href="#" update_id="'.$product_id.'" class="btn btn-primary update"><span class="glyphicon glyphicon-ok-sign"></span></a>
									</div>
								</div>
								<input type="hidden" name="product_id[]" value="'.$product_id.'"/>
								<input type="hidden" name="" value="'.$cart_item_id.'"/>
								<div class="col-md-2"><img class="img-responsive" src="product_images/'.$product_image.'"></div>
								<div class="col-md-2">'.$product_title.'</div>
								<div class="col-md-2"><input type="text" class="form-control qty" value="'.$qty.'" ></div>
								<div class="col-md-2"><input type="text" class="form-control price" value="'.$product_price.'" readonly="readonly"></div>
								<div class="col-md-2"><input type="text" class="form-control total" value="'.$product_price.'" readonly="readonly"></div>
							</div>';
				}
				
				echo '<div class="row">
							<div class="col-md-8"></div>
							<div class="col-md-4">
								<b class="net_total" style="font-size:20px;"> </b>
					</div>';
				if (!isset($_SESSION["uid"])) {
					echo '<input type="submit" style="float:right;" name="login_user_with_product" class="btn btn-info btn-lg" value="Ready to Checkout" >
							</form>';
					
				}else if(isset($_SESSION["uid"])){
					//Paypal checkout form
					echo '
						</form>
						<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_cart">
							<input type="hidden" name="business" value="shoppingcart@khanstore.com">
							<input type="hidden" name="upload" value="1">';
							  
							$x=0;
							$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
							$query = mysqli_query($con,$sql);
							while($row=mysqli_fetch_array($query)){
								$x++;
								echo  	
									'<input type="hidden" name="item_name_'.$x.'" value="'.$row["product_title"].'">
								  	 <input type="hidden" name="item_number_'.$x.'" value="'.$x.'">
								     <input type="hidden" name="amount_'.$x.'" value="'.$row["product_price"].'">
								     <input type="hidden" name="quantity_'.$x.'" value="'.$row["qty"].'">';
								}
							  
							echo   
								'<input type="hidden" name="return" value="http://localhost/Ecomm/payment_success.php"/>
					                <input type="hidden" name="notify_url" value="http://localhost/project1/payment_success.php">
									<input type="hidden" name="cancel_return" value="http://localhost/Ecomm/cancel.php"/>
									<input type="hidden" name="currency_code" value="USD"/>
									<input type="hidden" name="custom" value="'.$_SESSION["uid"].'"/>
									<input style="float:right;margin-right:80px;" type="image" name="submit"
										src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/blue-rect-paypalcheckout-60px.png" alt="PayPal Checkout"
										alt="PayPal - The safer, easier way to pay online">
								</form>';
				}
			}
	}
	
	
}

//Remove Item From cart
if (isset($_POST["removeItemFromCart"])) {
	$remove_id = $_POST["rid"];
	if (isset($_SESSION["uid"])) {
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
	}else{
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
	}
	if(mysqli_query($con,$sql)){
		echo "<div class='alert alert-danger'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is removed from cart</b>
				</div>";
		exit();
	}
}


//Update Item From cart
if (isset($_POST["updateCartItem"])) {
	$update_id = $_POST["update_id"];
	$qty = $_POST["qty"];
	if (isset($_SESSION["uid"])) {
		$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
	}else{
		$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
	}
	if(mysqli_query($con,$sql)){
		echo "<div class='alert alert-info'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is updated</b>
				</div>";
		exit();
	}
}




?>






