var currentlyPlaylist = [];
var audioElement;

function Audio() {
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

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