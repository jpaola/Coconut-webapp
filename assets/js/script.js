var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = []; // holds the songs on the current page
var audioElement;
var mouseDown = false;
var currentIndex = 0; // used to access song in array of id's
var repeat = false;
var shuffle = false;
var userLoggedIn;

function openPage(url) {
    if(url.indexOf("?") == -1){
        url = url + "?";
    }
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url);
}

function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60); // convert to minutes and rounds down
    var seconds = time -(minutes * 60);

    var extraZero = (seconds < 10) ? "0" : "";
    
    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio){
    $(".progressTime.current").text(formatTime(audio.currentTime)); // increments the time played
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime)); // reduces play time remaining

    // make the progressbar increase
    var progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio){
        // manually increase/decrease volume using the volumeBar
        var volume = audio.volume * 100;
        $(".volumeBar .progress").css("width", volume + "%");
}

function Audio() {
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    // play next song
    this.audio.addEventListener("ended", function(){
        nextSong();
    });

    this.audio.addEventListener("canplay", function(){
        // 'this' refers to the object that the event was called on
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
    });

    // update the progress bar as song plays
    this.audio.addEventListener("timeupdate", function (){
        // if there is a duration..
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });

    // manipulate audio volume with the volumeBar
    this.audio.addEventListener("volumechange", function (){
        updateVolumeProgressBar(this);
    });

    this.setTrack = function(track){
        this.currentlyPlaying = track;
        // to play call audio.play() precedeing your var in header.php
        this.audio.src = track.path; // src of the file to play gets the value of the src param
    }
    this.play = function() {
        this.audio.play();
    }

    this.pause = function() {
        this.audio.pause();
    }

    this.setTime = function(seconds) {
        this.audio.currentTime = seconds;
    }
}