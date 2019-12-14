<form action="" method="post" style="padding:80px;">
    <b>Insert New Brand:</b>
    <input type="text" name="new_brand" required/>
    <input type="submit" name="add_brand" value="Add Brand" />

</form>
<?php
    include("db.php");
    if(isset($_POST['add_brand'])){
        $new_brand = $_POST['new_brand'];
        $insert_brand = "insert into brands (brand_title) values ('$new_brand')";
        $run_brand = mysqli_query($con, $insert_brand);
        if($run_brand){
            echo "<script>alert('New Brand has been inserted!')</script>";
            echo "<script>window.open('admin0000.php?view_brands','_self')</script>";
           }
    }
    if(isset($_GET['test']))
        echo "this is it!";
?>
