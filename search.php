<?php
    include("includes/includedFiles.php");

    if(isset($_GET['term'])){
        $term = urldecode($_GET['term']);
    }else{
        $term = "";
    }
?>

<div class="searchContainer">
    <h4>Search for an artist, album or song</h4>
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Search..." onfocus="this.value = this.value">
</div>

<script>

    $(".searchInput").focus();

    $(function(){
        var timer;

        $(".searchInput").keyup(function() {
            clearTimeout(timer); // clear timer each time user stops typing

            timer = setTimeout(function() {
                var val = $(".searchInput").val(); // save the user input
                openPage("search.php?term=" + val);
            }, 1000); // 1 second
        })
    })
</script>

<div class="tracklistContainer borderBottom">
    <h2>SONGS</h2>
    <ul class="tracklist">
        <?php 
            
             // the % after $term means any combinatin of characters starting with $term
             // if searching for %$term% you will get a search result containing $term
            $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");

            if(mysqli_num_rows($songsQuery) == 0){
                echo "<span class='noResults'>No songs found matching \"" . $term . "\"</span>";
            }

            $songIdArray = array(); 

            // initialize row count to use in loop
            // this will be the trackNumber
            $i = 1;

            // loop through the list to get the song IDs
            while($row = mysqli_fetch_array($songsQuery)){
                
                if($i >10){
                    break;
                }

                array_push($songIdArray, $row['id']);

                $albumSong = new Song($con,  $row['id']);
                $albumArtist = $albumSong->getArtist();
                
                echo "<li class='tracklistRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/play-dark.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <span class='trackName'>" . $albumSong->getTitle() . "</span>
                            <span class='artistName'>" . $albumArtist->getName() . "</span>
                        </div>

                        <div class='trackOptions'>
                            <img class='optionButton' src='assets/images/icons/more-dark.png'>
                        </div>

                        <div class='trackDuration'>
                            <span class='duration'>" . $albumSong->getDuration() . "</span>
                        </div>
                </li>";
                $i++;
            }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>