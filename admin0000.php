<?php
session_start();
if(!isset($_SESSION['user_email'])){
    echo "<script>window.open('adminlogin.php?not_admin=You are not an Admin!','_self')</script>";
}else{

?>

<!DOCTYPE>
<html>
    <head>
        <title>This is Admin Panel</title>
        <link rel="stylesheet" href="styles/style.css" media="all" />
        <link rel="stylesheet" type="text/css" href="1.css">
        <link rel="stylesheet" type="text/css" href="modal.css">
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <script src="js/jquery2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="main.js"></script>
    </head>
    
    <body>
        <?php
            include("view_messages.php");
        ?>
        <div class="main_wrapper">
            <div id="header"></div>
            <div id="right">
                <h2 style="text-align:center;color:white;padding-top: 10px">Manage Content</h2>
                    <a href="admin0000.php?insert_product">Insert New Product</a>
                    <a href="admin0000.php?view_products">View All Products</a>
                    <a href="admin0000.php?insert_cat">Insert New Category</a>
                    <a href="admin0000.php?view_cats">View All Categories</a>
                    <a href="admin0000.php?insert_brand">Insert New Brand</a>
                    <a href="admin0000.php?view_brands">View All Brands</a>
                    <a href="admin0000.php?view_customers">View Customers</a>
                    <a href="admin0000.php?view_orders">View Orders</a>
                    <a href="#" id="openMsg" onclick = "modal.style.display = 'block';
                $('#admin_msgs').scrollTop(function() { return this.scrollHeight; });">View Messages</a>
                    <a href="adminlogout.php">Admin Logout</a>

            </div>
            <div id="left">

               
                <h2 style="color:red; text-align:center;padding-top: 10px"><?php echo @$_GET['logged_in'];?></h2>
                <?php
                    if(isset($_GET['insert_product'])){
                        include("insert_product.php");
                    }
                    if(isset($_GET['view_products'])){
                        include("view_products.php");
                    }
                    if(isset($_GET['edit_pro'])){
                        include("edit_pro.php");
                    }
                    if(isset($_GET['insert_cat'])){
                        include("insert_cat.php");
                    }
                    if(isset($_GET['view_cats'])){
                        include("view_cats.php");
                    }
                    if(isset($_GET['edit_cat'])){
                        include("edit_cat.php");
                    }
                    if(isset($_GET['insert_brand'])){
                        include("insert_brand.php");
                    }
                    if(isset($_GET['view_brands'])){
                        include("view_brands.php");
                    }
                    if(isset($_GET['edit_brand'])){
                        include("edit_brand.php");
                    }
                    if(isset($_GET['view_customers'])){
                        include("view_customers.php");
                    }
                    if(isset($_GET['view_orders'])){
                        include("view_orders.php");
                    }
                ?>
            </div>
        </div>
        <script type="text/javascript">
        var modal = document.getElementById("myModal");

        var btn = document.getElementById("openMsg");

        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
        modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}   </script>
    </body>
</html>
<?php
}
?>