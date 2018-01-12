var currentlyPlaylist = [];
var audioElement;

function Audio() {
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.setTrack = function(src){
        // to play call audio.play() precedeing your var in header.php
        this.audio.src = src; // src of the file to play gets the value of the src param
    }
    this.play = function() {
        this.audio.play();
    }

    this.pause = function() {
        this.audio.pause();
    }
}