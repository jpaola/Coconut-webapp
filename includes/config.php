<?php
    ob_start();
    session_start();    // keep track of values from user logged in
    $timezone = date_default_timezone_set("Europe/London");

    // connect to the 'Coconut' database
    $con = mysqli_connect("localhost", "root", "", "Coconut");

    // handle connection issues
    if(mysqli_connect_errno()){
        echo "Failed to connect: " . mysqli_connect_errno();
    }
?>