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
        var newPlaylist = <?php echo $jsonArray; ?>; // contains the array of id's
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false);
        updateVolumeProgressBar(audioElement.audio); // show current volume (progressBar) when page loads

        // on any of these events "mousedown touchstart mousedown touchmove" do the following...
        $("#nowPlayingBarContainer").on("mousedown touchstart mousedown touchmove", function(e){
            e.preventDefault(); // prevents default behaviour (prevent controls from highlighting on the above events)
        });
        
        $(".playbackBar .progressBar").mousedown(function () {
            mouseDown = true;
        });

        $(".playbackBar .progressBar").mousedown(function (e) {
            if(mouseDown = true){
                // Set time of the song, depending on position of mouse
                timeFromOffset(e, this);
            }
        });

        $(".playbackBar .progressBar").mouseup(function (e) {
            timeFromOffset(e, this);
        });


        $(".volumeBar .progressBar").mousedown(function () {
            mouseDown = true;
        });

        $(".volumeBar .progressBar").mousedown(function (e) {
            if(mouseDown = true){

                var percentage = e.offsetX / $(this).width();
                
                if(percentage >= 0  && percentage <= 1) {
                    audioElement.audio.volume = percentage;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function (e) {
                var percentage = e.offsetX / $(this).width();
                
                if(percentage >= 0  && percentage <= 1) {
                    audioElement.audio.volume = percentage;
                }
        });

        // here 'document' is refering to ".playbackBar .progressBar" above
        $(document).mouseup(function() {
            // enables you to drag the progress bar if 
            //you wish to jump to a section in the currently playing song
            mouseDown = false; 
        });
    });

    function timeFromOffset(mouse, progressBar) {
        // here 'this' refers to ".playbackBar .progressBar" from above
        var percentage = mouse.offsetX / $(progressBar).width() * 100;
        var seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds);
    }

    function prevSong() {
        if(audioElement.audio.currentTime >= 3 || currentIndex == 0){
            audioElement.setTime(0);
        }else{
            currentIndex = currentIndex - 1;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        }
    }

    function nextSong(){
        if(repeat == true){
            audioElement.setTime(0);
            playSong();
            return;
        }

        if(currentIndex == currentPlaylist.length - 1){
            currentIndex = 0;
        }else{
            currentIndex++;
        }
        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(trackToPlay, currentPlaylist, true);
    }

    function setRepeat(){
        repeat = !repeat;
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    }

    function setMute(){
        audioElement.audio.muted = !audioElement.audio.muted;
        var imageName = audioElement.audio.muted ? "mute.png" : "volume.png";
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    function setShuffle(){
        shuffle = !shuffle;
        var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

        // shuffle playlist
        if(shuffle == true){
            // randomize playlist
            shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        }else{
            // shuffle has been deactivated
            // go back to regular playlist
        }
    }

    function shuffleArray(a) {
        var j, x, i;
        for (i = a.length; i; i--){
            j = Math.floor(Math.random() * i);
            x = a[i-1];
            a[i-1] = a[j];
            a[j] = x;
        }
    }

    // handles the track currently being played 
    function setTrack(trackId, newPlaylist, play){

        if(newPlaylist != currentPlaylist){
            currentPlaylist = newPlaylist; // current and unrandomized playlist
            shufflePlaylist = currentPlaylist.slice();  // currentPlaylist.slice() returns a copy of the currentPlaylist
            shuffleArray(shufflePlaylist);
        }
        
        if(shuffle == true){
            currentIndex = shufflePlaylist.indexOf(trackId);
        }else{
            currentIndex = currentPlaylist.indexOf(trackId); // point to index of current track
        }
            pauseSong();

        // song will be retrieved via ajax call
        $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId}, function (data){

                var track = JSON.parse(data);
                $(".trackName span").text(track.title); // places the name of the track in the span preceding class="trackName"

                // make a separate ajax call to get the artist name by using the artistId
                // call goes in here because we are using the track.artist (which returns artist ID) to get the artist name
                $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist}, function (data){
                    
                    //get the artist pertaining to the album currently playing
                    var artist = JSON.parse(data);
                    $(".trackInfo .artistName span").text(artist.name);
                    $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
                });

                // get Album via ajax
                $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album}, function (data){
                   // slightly different since we are getting an img instead of span and using 'attr'
                    var album = JSON.parse(data);
                    $(".content .albumLink img").attr("src", album.artworkPath);
                    $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
                    $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
                });

                audioElement.setTrack(track);

                if(play == true){
                    playSong();
                }
        });
    }

    function playSong() {

        if(audioElement.audio.currentTime == 0){
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        }

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
                <img role="link" tabIndex="0" src="" class="albumArtwork">
            </span>
            <div class="trackInfo">
                <span class="trackName">
                    <span role="link" tabIndex="0"></span>
                </span>
                <span class="artistName">
                    <span role="link" tabIndex="0"></span>
                </span>
            </div>
        </div>
    </div>
    <div id="nowPlayingCenter">
        <div class="content playerControls">
            <div class="buttons">
                <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                    <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                </button>
                <button class="controlButton previous" title="Previous button" onclick="prevSong()">
                    <img src="assets/images/icons/previous.png" alt="Previous">
                </button>
                <button class="controlButton play" title="Play button" onclick="playSong()">
                    <img src="assets/images/icons/play.png" alt="Play">
                </button>
                <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                    <img src="assets/images/icons/pause.png" alt="Pause">
                </button>
                <button class="controlButton next" title="Next button" onclick="nextSong()">
                    <img src="assets/images/icons/next.png" alt="Next">
                </button>
                <button class="controlButton repeat" title="Repeat button">
                    <img src="assets/images/icons/repeat.png" alt="Repeat" onclick="setRepeat()">
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
            <button class="controlButton volume" title="Volume button" onclick="setMute()">
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