<table width="795" align="center" style="background-color: rgb(223, 232, 247)"> 

	
	<tr align="center">
		<td colspan="6"><h2>View all orders here</h2></td>
	</tr>
	
	<tr align="center" bgcolor="skyblue">
		<th>S.N</th>
		<th>Customer Email</th>
		<th>Product (S)</th>
		<th>Quantity</th>
		<th>Invoice No</th>
		<th>Order Date</th>
	</tr>
	<?php 
	include("db.php");
	$get_order = "select * from orders";
	$run_order = mysqli_query($con, $get_order); 
	$i = 0;
	while ($row_order=mysqli_fetch_array($run_order)){
		$order_id = $row_order['order_id'];
		$qty = $row_order['qty'];
		$pro_id = $row_order['product_id'];
		$c_id = $row_order['user_id'];
		$invoice_no = $row_order['trx_id'];
		$order_date = $row_order['p_status'];
		$i++;
		
		$get_pro = "select * from products where product_id='$pro_id'";
		$run_pro = mysqli_query($con, $get_pro); 
		
		$row_pro=mysqli_fetch_array($run_pro); 
		
		$pro_image = $row_pro['product_image']; 
		$pro_title = $row_pro['product_title'];
		
		$get_c = "select * from user_info where user_id='$c_id'";
		$run_c = mysqli_query($con, $get_c); 
		
		$row_c=mysqli_fetch_array($run_c); 
		
		$c_email = $row_c['email'];
	
	?>
	<tr align="center">
		<td><?php echo $i;?></td>
		<td><?php echo $c_email; ?></td>
		<td>
		<?php echo $pro_title;?><br>
		<img src="product_images/<?php echo $pro_image;?>" width="50" height="50" />
		</td>
		<td><?php echo $qty;?></td>
		<td><?php echo $invoice_no;?></td>
		<td><?php echo $order_date;?></td>
	
	</tr>
	<?php } ?>
</table>