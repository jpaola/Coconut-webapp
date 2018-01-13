var currentlyPlaylist = [];
var audioElement;

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

function Audio() {
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

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
}