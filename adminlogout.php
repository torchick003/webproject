<?php 
    session_start(); 
    session_destroy(); 
    echo "<script>window.open('adminlogin.php?logged_out=You have logged out, come back soon!','_self')</script>";
?> 