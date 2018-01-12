var currentlyPlaylist = [];
var audioElement;

function Audio() {
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.audio.addEventListener("canplay", function(){
        // 'this' refers to the object that the event was called on
        $(".progressTime.remaining").text(this.duration);
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