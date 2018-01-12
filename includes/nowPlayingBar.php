<?php
    $songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 15");

    $resultArray = array();

    while($row = mysqli_fetch_array($songQuery)){
        array_push($resultArray, $row['id']);
    }

    $jsonArray = json_encode($resultArray); // to convert into a js array use json
?>

<script>

    $(document).ready(function() {
        currentPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        setTrack(currentPlaylist[0], currentPlaylist, false);
    });

    // handles the track currently being played 
    function setTrack(trackId, newPlaylist, play){
        // song will be retrieved via ajax call
        $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId}, function (data){
                
                var track = JSON.parse(data);
                $(".trackName span").text(track.title); // places the name of the track in the span preceding class="trackName"

                // make a separate ajax call to get the artist name by using the artistId
                // call goes in here because we are using the track.artist (which returns artist ID) to get the artist name
                $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist}, function (data){
                    
                    //get the artist pertaining to the album currently playing
                    var artist = JSON.parse(data);
                    $(".artistName span").text(artist.name);
                });

                // get Album via ajax
                $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album}, function (data){
                   // slightly different since we are getting an img instead of span and using 'attr'
                    var album = JSON.parse(data);
                    $(".albumLink img").attr("src", album.artworkPath);
                });

                audioElement.setTrack(track.path);
                audioElement.play();
        });

        if(play == true){
            audioElement.play();
        }
    }

    function playSong() {
        // hide play button, show pause button
        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
        audioElement.play();
    }

    function pauseSong() {
        // hide pause button, show play button
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
        audioElement.pause();
    }

</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
    <div id="nowPlayingLeft">
        <div class="content">
            <span class="albumLink">
                <img src="" class="albumArtwork">
            </span>
            <div class="trackInfo">
                <span class="trackName">
                    <span></span>
                </span>
                <span class="artistName">
                    <span></span>
                </span>
            </div>
        </div>
    </div>
    <div id="nowPlayingCenter">
        <div class="content playerControls">
            <div class="buttons">
                <button class="controlButton shuffle" title="Shuffle button">
                    <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                </button>
                <button class="controlButton previous" title="Previous button">
                    <img src="assets/images/icons/previous.png" alt="Previous">
                </button>
                <button class="controlButton play" title="Play button">
                    <img src="assets/images/icons/play.png" alt="Play" onclick="playSong()">
                </button>
                <button class="controlButton pause" title="Pause button" style="display: none;">
                    <img src="assets/images/icons/pause.png" alt="Pause" onclick="pauseSong()">
                </button>
                <button class="controlButton next" title="Next button">
                    <img src="assets/images/icons/next.png" alt="Next">
                </button>
                <button class="controlButton repeat" title="Repeat button">
                    <img src="assets/images/icons/repeat.png" alt="Repeat">
                </button>
            </div>

            <div class="playbackBar">
                <span class="progressTime current">0.00</span>
                <div class="progressBar">
                    <div class="progressBarBackground">
                        <div class="progress"></div>
                    </div>
                </div>
                <span class="progressTime remaining">0.00</span>
            </div>

        </div>
    </div>
    <div id="nowPlayingRight">
        <div class="volumeBar">
            <button class="controlButton volume" title="Volume button">
                <img src="assets/images/icons/volume.png" alt="Volume">
            </button>

            <div class="progressBar">
                <div class="progressBarBackground">
                    <div class="progress"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>