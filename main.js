$(document).ready(function(){
	cat();
	brand();
	product();
	msgName();
	msg();
	msgAdminName();
	msgAdmin();
	setInterval(msgName,3000);
	setInterval(msgRe,3000);
	setInterval(msgAdminName,3000);
	setInterval(msgAdminRe,3000);
	function cat(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{category:1},
			success	:	function(data){
				//$("#get_category").html(data);
				var xmlstr = "<data>";
				xmlstr += "<catid>thisname</catid>";
				xmlstr += "<catname>thisname</catname>";
				xmlstr += "</data>";
				var parser = new DOMParser();
				var x = parser.parseFromString(xmlstr, "text/xml");
				var y = x.documentElement;
				var string = "<div class='nav nav-pills nav-stacked'><li class='active'><a href='#'><h4>Categories</h4></a></li>";
				var json = JSON.parse(data);
				for(var i=0;i<json.length;i++){
					y.childNodes[0].childNodes[0].nodeValue = json[i][0];
					y.childNodes[1].childNodes[0].nodeValue = json[i][1];
					string += "<li><a href='#' class='category' cid='"+y.childNodes[0].childNodes[0].nodeValue+"'>"+y.childNodes[1].childNodes[0].nodeValue+"</a></li>";
				}
				string += "</div>";
				$("#get_category").html(string);
			}
		})
	}
	function brand(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{brand:1},
			success	:	function(data){
				$("#get_brand").html(data);
			}
		})
	}
		function product(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{getProduct:1},
			success	:	function(data){
				$("#get_product").html(data);
			}
		})
	}
		function msgName(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{msgNames:1},
			success	:	function(data){
				$("#msg_names").html(data);
			}
		})
	}
		function msgAdminName(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{msgAdminNames:1},
			success	:	function(data){
				$("#msg_Anames").html(data);
			}
		})
	}
		function msg(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{loadMsg:1},
			success	:	function(data){
				$("#get_msgs").html(data);
				$("#get_msgs").scrollTop(function() { return this.scrollHeight; });
			}
		})
	}
		function msgRe(){
		var last1 = $("#msgTxt").attr("lastMsg");
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{loadMsg:1},
			success	:	function(data){
				$("#get_msgs").html(data);
				var last2 = $("#msgTxt").attr("lastMsg");
				if(last1!=last2)
				$("#get_msgs").scrollTop(function() { return this.scrollHeight; });
			}
		})
	}
		function msgAdmin(){
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{loadAdminMsg:1},
			success	:	function(data){
				$("#admin_msgs").html(data);
			}
		})
	}
		function msgAdminRe(){
		var uid = $("#msgBody").attr("uid");
		var last1 = $("#msgBody").attr("last");
		$.ajax({
			url	:	"action.php",
			method:	"POST",
			data	:	{loadAdminMsgRe:1,uid:uid},
			success	:	function(data){
				$("#admin_msgs").html(data);
				var last2 = $("#msgBody").attr("last");
				if(last1!=last2)
				$("#admin_msgs").scrollTop(function() { return this.scrollHeight; });
				
			}
		})
	}
	$("body").delegate("#msgNameClick","click",function(event){
		var uid = $(this).attr('uid');
		
			$.ajax({
			url		:	"action.php",
			method	:	"POST",
			data	:	{msgNameClick:1,uid:uid},
			success	:	function(data){
				$("#admin_msgs").html(data);
				$('#admin_msgs').scrollTop(function() { return this.scrollHeight; });
			}
		})
	
	})
	$("body").delegate(".category","click",function(event){
		$("#get_product").html("<h3>Loading...</h3>");
		event.preventDefault();
		var cid = $(this).attr('cid');
		
			$.ajax({
			url		:	"action.php",
			method	:	"POST",
			data	:	{get_seleted_Category:1,cat_id:cid},
			success	:	function(data){
				$("#get_product").html(data);
				if($("body").width() < 480){
					$("body").scrollTop(683);
				}
			}
		})
	
	})

	$("body").delegate(".selectBrand","click",function(event){
		event.preventDefault();
		$("#get_product").html("<h3>Loading...</h3>");
		var bid = $(this).attr('bid');
		
			$.ajax({
			url		:	"action.php",
			method	:	"POST",
			data	:	{selectBrand:1,brand_id:bid},
			success	:	function(data){
				$("#get_product").html(data);
				if($("body").width() < 480){
					$("body").scrollTop(683);
				}
			}
		})
	
	})

	$("#search_btn").click(function(){
		$("#get_product").html("<h3>Loading...</h3>");
		var keyword = $("#search").val();
		if(keyword != ""){
			$.ajax({
			url		:	"action.php",
			method	:	"POST",
			data	:	{search:1,keyword:keyword},
			success	:	function(data){ 
				$("#get_product").html(data);
				if($("body").width() < 480){
					$("body").scrollTop(683);
				}
			}
		})
		}
	})

	$("#login").on("submit",function(event){
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url	:	"login.php",
			method:	"POST",
			data	:$("#login").serialize(),
			success	:function(data){
				if(data == "login_success"){
					window.location.href = "profile.php";
				}else if(data == "cart_login"){
					window.location.href = "profile.php";
				}else{
					$("#e_msg").html(data);
					$(".overlay").hide();
				}
			}
		})
	})

	$("#signup_form").on("submit",function(event){
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url : "register.php",
			method : "POST",
			data : $("#signup_form").serialize(),
			success : function(data){
				$(".overlay").hide();
				if (data == "register_success") {
					window.location.href = "profile.php";
				}else{
					$("#signup_msg").html(data);
				}
				
			}
		})
	})

	$("body").delegate("#product","click",function(event){
		var pid = $(this).attr("pid");
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {addToCart:1,proId:pid},
			success : function(data){
				count_item();
				getCartItem();
				$('#product_msg').html(data);
				$('.overlay').hide();
			}
		})
	})
	$("body").delegate("#productinfo","click",function(event){
		var pid = $(this).attr("pid");
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {getProductDetail:1,proId:pid},
			success : function(data){
				count_item();
				getCartItem();
				$("#get_product").html(data);
				$('.overlay').hide();
			}
		})
	}) 
	$("body").delegate("#submitReview","click",function(event){
		var pid = $(this).attr("pid");
		var stars = $("input[name='selector']:checked").val();
		var review = $("#review").val();
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {submitReview:1,proId:pid,stars:stars,review:review},
			success : function(data){
				count_item();
				getCartItem();
				$("#get_product").html(data);
				$('.overlay').hide();
			}
		})
	})

	$("body").delegate("#sendMsg","click",function(event){
		var msg = $("#chatMsg").val();
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {sendMsg:1,msg:msg},
			success : function(data){
				$('#get_msgs').html(data);
				$("#get_msgs").scrollTop(function() { return this.scrollHeight; });
				$("#chatMsg").val("");
				msgName();
				$('.overlay').hide();
			}
		})
	})
	$("body").delegate("#sendAdminMsg","click",function(event){
		var msg = $("#chatMsg").val();
		var uid = $("#msgBody").attr("uid");
		event.preventDefault();
		$(".overlay").show();
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {sendAdminMsg:1,msg:msg, uid:uid},
			success : function(data){
				$('#admin_msgs').html(data);
				$("#admin_msgs").scrollTop(function() { return this.scrollHeight; });
				$("#chatMsg").val("");
				msgAdminName();
				$('.overlay').hide();
			}
		})
	})

	count_item();
	function count_item(){
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {count_item:1},
			success : function(data){
				$(".badge").html(data);
			}
		})
	}

	getCartItem();
	function getCartItem(){
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {Common:1,getCartItem:1},
			success : function(data){
				$("#cart_product").html(data);
			}
		})
	} 


	$("body").delegate(".qty","keyup",function(event){
		event.preventDefault();
		var row = $(this).parent().parent();
		var price = row.find('.price').val();
		var qty = row.find('.qty').val();
		if (isNaN(qty)) {
			qty = 1;
		};
		if (qty < 1) {
			qty = 1;
		};
		var total = price * qty;
		row.find('.total').val(total);
		var net_total=0;
		$('.total').each(function(){
			net_total += ($(this).val()-0);
		})
		$('.net_total').html("Total : $ " +net_total);

	})

	$("body").delegate(".remove","click",function(event){
		var remove = $(this).parent().parent().parent();
		var remove_id = remove.find(".remove").attr("remove_id");
		$.ajax({
			url	:	"action.php",
			method	:	"POST",
			data	:	{removeItemFromCart:1,rid:remove_id},
			success	:	function(data){
				$("#cart_msg").html(data);
				checkOutDetails();
			}
		})
	})

	$("body").delegate(".update","click",function(event){
		var update = $(this).parent().parent().parent();
		var update_id = update.find(".update").attr("update_id");
		var qty = update.find(".qty").val();
		$.ajax({
			url	:	"action.php",
			method	:	"POST",
			data	:	{updateCartItem:1,update_id:update_id,qty:qty},
			success	:	function(data){
				$("#cart_msg").html(data);
				checkOutDetails();
			}
		})


	})
	checkOutDetails();
	net_total();

	function checkOutDetails(){
	 $('.overlay').show();
		$.ajax({
			url : "action.php",
			method : "POST",
			data : {Common:1,checkOutDetails:1},
			success : function(data){
				$('.overlay').hide();
				$("#cart_checkout").html(data);
					net_total();
			}
		})
	}

	function net_total(){
		var net_total = 0;
		$('.qty').each(function(){
			var row = $(this).parent().parent();
			var price  = row.find('.price').val();
			var total = price * $(this).val()-0;
			row.find('.total').val(total);
		})
		$('.total').each(function(){
			net_total += ($(this).val()-0);
		})
		$('.net_total').html("Total : $ " +net_total);
	}

	

	page();
	function page(){
		$.ajax({
			url	:	"action.php",
			method	:	"POST",
			data	:	{page:1},
			success	:	function(data){
				$("#pageno").html(data);
			}
		})
	}
	$("body").delegate("#page","click",function(){
		var pn = $(this).attr("page");
		$.ajax({
			url	:	"action.php",
			method	:	"POST",
			data	:	{getProduct:1,setPage:1,pageNumber:pn},
			success	:	function(data){
				$("#get_product").html(data);
			}
		})
	})
})




















