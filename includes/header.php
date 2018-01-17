<?php
    include("includes/config.php");
    include("includes/classes/Artist.php"); // the Artist class must be passed before Album to avoid errors
    include("includes/classes/Album.php"); // the Album class calls the artist class
    include("includes/classes/Song.php");

    // only include session_destroy(); when you want to LOGOUT manually

    if(isset($_SESSION['userLoggedIn'])){
        $userLoggedIn = $_SESSION['userLoggedIn'];
        echo "<script>userLoggedIn = '$userLoggedIn';</script>";
    }else{
        header("Location: register.php");
    }
?>
<html>
    <head>
        <title>Coconut</title>

        <!-- stylesheets -->
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">

        <!-- scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> <!-- get this link from https://developers.google.com/speed/libraries/-->
        <script src="assets/js/script.js"></script>

    </head>
    <body id="background">

        <script>
            var audioElement = new Audio();
        </script>

        <div id="mainContainer"> 
            <div id="topContainer">

                <?php include("includes/navbarContainer.php"); ?>

                <div id="mainViewContainer">
                    <div id="mainContent">