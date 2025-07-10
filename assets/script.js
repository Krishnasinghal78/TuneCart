let currentSongIndex = 0;
const audioPlayer = document.getElementById('audio-player');
const nowPlaying = document.getElementById('now-playing');
const playButton = document.getElementById('play');
const pauseButton = document.getElementById('pause');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');

// Properly passing the PHP array to JavaScript
const songs = <?php echo json_encode($songs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

// Play the song
function playSong(filePath, title) {
    audioPlayer.src = filePath;
    nowPlaying.textContent = `Now Playing: ${title}`;
    audioPlayer.play();
    playButton.style.display = 'none';
    pauseButton.style.display = 'inline';
}

// Add song to playlist (Placeholder function)
function addToPlaylist(songId) {
    alert('Added to Playlist (functionality to be implemented)');
}

// Play/Pause Button functionality
playButton.addEventListener('click', () => {
    if (audioPlayer.paused) {
        audioPlayer.play();
        playButton.style.display = 'none';
        pauseButton.style.display = 'inline';
    }
});

pauseButton.addEventListener('click', () => {
    if (!audioPlayer.paused) {
        audioPlayer.pause();
        playButton.style.display = 'inline';
        pauseButton.style.display = 'none';
    }
});

// Next/Previous Buttons
nextButton.addEventListener('click', () => {
    currentSongIndex = (currentSongIndex + 1) % songs.length;
    playSong(songs[currentSongIndex].file_path, songs[currentSongIndex].title);
});

prevButton.addEventListener('click', () => {
    currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
    playSong(songs[currentSongIndex].file_path, songs[currentSongIndex].title);
});

// Auto-play next song when current ends
audioPlayer.addEventListener('ended', () => {
    nextButton.click();
});

// Function to handle song item click (Play specific song)
document.querySelectorAll('.song-item').forEach(item => {
    const playButton = item.querySelector('.play-button');
    const songFilePath = item.getAttribute('data-file-path');
    const songTitle = item.querySelector('h4').textContent;

    playButton.addEventListener('click', () => {
        playSong(songFilePath, songTitle);
    });

    // Add to playlist button
    const addToPlaylistButton = item.querySelector('.add-to-playlist-button');
    const songId = item.getAttribute('data-song-id');

    addToPlaylistButton.addEventListener('click', () => {
        addToPlaylist(songId);
    });
});
