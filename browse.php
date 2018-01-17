<?php 
    include("includes/includedFiles.php");
?>

<h1 id="pageHeadingBig">You May Like</h1>

<div id="albumTitles">
    <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 15");
        
        // get album data from db
        while($row = mysqli_fetch_array($albumQuery)){

            // in php the '.' is used to contatinate strings like '+' in Java and C#
            echo "<div class='gridViewItem'>
                <span role='link' tabindex= '0' onclick='openPage(\"album.php?id=" . $row['id'] ."\")'>
                    <img src='" . $row['artworkPath'] . "'>
                    <div class='gridViewInfo'>"
                        . $row['title'] .
                    "</div>
                </span>
            </div>";
        }
    ?>
</div>