<?php
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        include("includes/config.php");
        include("includes/classes/Artist.php"); // the Artist class must be passed before Album to avoid errors
        include("includes/classes/Album.php"); // the Album class calls the artist class
        include("includes/classes/Song.php");
    }else{
        include("includes/header.php");
        include("includes/footer.php");

        $url = $_SERVER['REQUEST_URI'];
        echo "<script>openPage('$url')</script>";
        exit();
    }
?>