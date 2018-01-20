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

        $(".searchInput").keyup(function() {
            clearTimeout(timer); // clear timer each time user stops typing

            timer = setTimeout(function() {
                var val = $(".searchInput").val(); // save the user input
                openPage("search.php?term=" + val);
            }, 1000); // 1 second
        })
    })
</script>

<!-- If no search input, display no results as opposed to displaying all albums, songs, and artists on db -->
<?php if($term == "") exit(); ?>


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

<div class="artistsContainer borderBottom">
        <h2>ARTISTS</h2>

        <?php
            $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

            if(mysqli_num_rows($artistsQuery) == 0){
                echo "<span class='noResults'>No artists found matching \"" . $term . "\"</span>";
            }

            while($row = mysqli_fetch_array($artistsQuery)) {
                $artistFound = new Artist($con, $row['id']);

                echo "<div class='searchResultRow'>
                        <div class='artistName'>
                            <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() ."\")'>
                            " 
                            . $artistFound->getName() .
                            "
                            </span>
                        </div>
                    </div>";
            }
        ?>
</div>

<div class="gridViewContainer">
    <h2>ALBUMS</h2>

    <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

        
        if(mysqli_num_rows($albumQuery) == 0){
            echo "<span class='noResults'>No albums found matching \"" . $term . "\"</span>";
        }

        // get album data from db
        while($row = mysqli_fetch_array($albumQuery)){

            // in php the '.' is used to concatinate strings like '+' in Java and C#
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