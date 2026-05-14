    <!-- GLOBÁLNÍ PŘEHRÁVAČ -->
    <div id="persistent-player" class="fixed bottom-0 left-0 right-0 bg-zinc-900/95 backdrop-blur-xl border-t border-zinc-800 z-[1000] transform translate-y-full transition-transform duration-500 shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
        <div class="container mx-auto px-6 h-24 flex items-center justify-between gap-6">
            
            <!-- Info o skladbě -->
            <div class="flex items-center space-x-4 min-w-[200px] w-1/3">
                <div class="relative group">
                    <img id="player-cover" src="" alt="Cover" class="w-14 h-14 rounded-lg object-cover shadow-lg border border-white/10">
                    <div class="absolute inset-0 bg-blue-500/20 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <div class="overflow-hidden flex flex-col">
                    <h4 id="player-title" class="text-white font-bold truncate text-sm">Název skladby</h4>
                    <p id="player-artist" class="text-zinc-400 text-xs truncate">Interpret</p>
                </div>
            </div>

            <!-- Ovládání -->
            <div class="flex flex-col items-center w-1/3">
                <div class="flex items-center space-x-6 mb-2">
                    <!-- Náhodně (Shuffle) -->
                    <button id="player-shuffle" class="text-zinc-500 hover:text-white transition-colors" title="Náhodné přehrávání">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10.59 9.17L5.41 4 4 5.41l5.17 5.17 1.42-1.41zM14.5 4l2.04 2.04L4 18.59 5.41 20 17.96 7.46 20 9.5V4h-5.5zm.33 9.41l-1.41 1.41 3.13 3.13L14.5 20H20v-5.5l-2.04 2.04-3.13-3.13z"/></svg>
                    </button>
                    <!-- Předchozí -->
                    <button id="player-prev" class="text-zinc-500 hover:text-white transition-colors" title="Předchozí">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18 6L9.5 12 18 18V6zM8 6v12h2V6H8z"/></svg>
                    </button>
                    <!-- Play/Pause -->
                    <button id="player-play-pause" class="w-12 h-12 bg-white text-black rounded-full flex items-center justify-center hover:scale-105 active:scale-95 transition-transform shadow-xl">
                        <svg id="player-play-icon" class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                        <svg id="player-pause-icon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path></svg>
                    </button>
                    <!-- Další -->
                    <button id="player-next" class="text-zinc-500 hover:text-white transition-colors" title="Další">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"></path></svg>
                    </button>
                    <!-- Opakovat -->
                    <button id="player-repeat" class="text-zinc-500 hover:text-white transition-colors relative" title="Opakovat">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M7 7h10v3l4-4-4-4v3H5v6h2V7zm10 10H7v-3l-4 4 4 4v-3h12v-6h-2v4z"/></svg>
                        <!-- Malá 1 pro "Repeat One" -->
                        <span id="player-repeat-one-indicator" class="hidden absolute top-0 right-0 text-[8px] font-bold bg-zinc-900 rounded-full w-3 h-3 flex items-center justify-center" style="transform: translate(50%, -50%);">1</span>
                    </button>
                </div>
                
                <!-- Progress bar -->
                <div class="w-full flex items-center space-x-3">
                    <span id="player-current-time" class="text-[10px] text-zinc-500 font-mono w-8 text-right">0:00</span>
                    <div id="player-progress-container" class="flex-grow h-1 bg-zinc-800 rounded-full cursor-pointer group relative">
                        <div id="player-progress-bar" class="absolute h-full bg-blue-500 rounded-full w-0 group-hover:bg-blue-400 transition-colors"></div>
                        <div id="player-progress-handle" class="absolute h-3 w-3 bg-white rounded-full -top-1 -ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" style="left: 0%"></div>
                    </div>
                    <span id="player-duration" class="text-[10px] text-zinc-500 font-mono whitespace-nowrap w-auto min-w-[2rem]">0:00</span>
                </div>
            </div>

            <!-- Volume / Extra -->
            <div class="flex items-center justify-end w-1/3 space-x-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                    <input type="range" id="player-volume" min="0" max="1" step="0.01" value="0.5" class="w-24 h-1 bg-zinc-800 rounded-full appearance-none cursor-pointer accent-blue-500">
                </div>
            </div>
        </div>
    </div>

    <audio id="audio-element"></audio>
    <!-- Hidden YT Player (1x1 px to prevent browser blocking) -->
    <div id="yt-player-container" style="position: fixed; bottom: 0; left: 0; width: 1px; height: 1px; opacity: 0.01; pointer-events: none; z-index: -1;">
        <div id="youtube-player"></div>
    </div>

    <footer class="mt-auto border-t border-zinc-800 py-12 pb-32 text-center bg-black">
        <p class="text-zinc-500 text-sm tracking-widest uppercase font-semibold">&copy; WA 2026 - MusicApp</p>
    </footer>

    <script>
    let ytPlayer = null;
    let ytReady = false;

    window.onYouTubeIframeAPIReady = function() {
        console.log('YouTube API Ready');
        ytPlayer = new YT.Player('youtube-player', {
            height: '0',
            width: '0',
            playerVars: { 
                'autoplay': 1, 
                'controls': 0, 
                'disablekb': 1,
                'origin': window.location.origin
            },
            events: {
                'onReady': () => { 
                    ytReady = true; 
                    console.log('YouTube Player Ready');
                },
                'onStateChange': (event) => {
                    if (event.data === YT.PlayerState.ENDED) MusicPlayer.onTrackEnd();
                },
                'onError': (e) => {
                    console.error('YouTube Player Error:', e.data);
                    // Fallback na audio pokud YT selže
                    if (MusicPlayer.currentSong) {
                        MusicPlayer.isPlayingYT = false;
                        MusicPlayer.audio.src = MusicPlayer.currentSong.previewUrl;
                        MusicPlayer.audio.play();
                    }
                }
            }
        });
    };
    </script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
    const MusicPlayer = {
        audio: document.getElementById('audio-element'),
        bar: document.getElementById('persistent-player'),
        playPauseBtn: document.getElementById('player-play-pause'),
        playIcon: document.getElementById('player-play-icon'),
        pauseIcon: document.getElementById('player-pause-icon'),
        progress: document.getElementById('player-progress-bar'),
        handle: document.getElementById('player-progress-handle'),
        currentTimeEl: document.getElementById('player-current-time'),
        durationEl: document.getElementById('player-duration'),
        volume: document.getElementById('player-volume'),
        
        currentSong: null,
        queue: [],
        originalQueue: [],
        currentIndex: -1,
        isPlayingYT: false,
        updateInterval: null,
        repeatMode: 0, // 0 = off, 1 = playlist, 2 = song
        isShuffle: false,

        init() {
            this.audio.addEventListener('timeupdate', () => { if (!this.isPlayingYT) this.updateProgress(); });
            this.audio.addEventListener('loadedmetadata', () => {
                if (!this.isPlayingYT) this.durationEl.textContent = this.formatTime(this.audio.duration);
            });
            this.audio.addEventListener('ended', () => this.onTrackEnd());

            this.playPauseBtn.onclick = () => this.toggle();
            document.getElementById('player-shuffle').onclick = () => this.toggleShuffle();
            document.getElementById('player-prev').onclick = () => this.prev();
            document.getElementById('player-next').onclick = () => this.next(true);
            document.getElementById('player-repeat').onclick = () => this.toggleRepeat();
            
            document.getElementById('player-progress-container').onclick = (e) => {
                const rect = e.currentTarget.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                if (this.isPlayingYT) ytPlayer.seekTo(pos * ytPlayer.getDuration());
                else this.audio.currentTime = pos * this.audio.duration;
            };

            this.volume.oninput = (e) => {
                const vol = e.target.value;
                this.audio.volume = vol;
                if (ytReady) ytPlayer.setVolume(vol * 100);
            };

            this.updateInterval = setInterval(() => {
                if (this.isPlayingYT && ytReady && ytPlayer) {
                    try {
                        const state = ytPlayer.getPlayerState();
                        if (state === 1) { // 1 is YT.PlayerState.PLAYING
                            this.updateProgress();
                        }
                    } catch(e) {}
                }
            }, 500);
        },

        toggleShuffle() {
            this.isShuffle = !this.isShuffle;
            const btn = document.getElementById('player-shuffle');
            if (this.isShuffle) {
                btn.classList.add('text-blue-500');
                btn.classList.remove('text-zinc-500');
                if (this.queue.length > 0 && this.currentSong) {
                    const currentId = this.currentSong.spotifyId || this.currentSong.previewUrl || (this.currentSong.title + this.currentSong.artist);
                    const remaining = this.originalQueue.filter(s => {
                        const sid = s.spotifyId || s.previewUrl || (s.title + s.artist);
                        return sid !== currentId;
                    });
                    for (let i = remaining.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [remaining[i], remaining[j]] = [remaining[j], remaining[i]];
                    }
                    this.queue = [this.currentSong, ...remaining];
                    this.currentIndex = 0;
                }
            } else {
                btn.classList.add('text-zinc-500');
                btn.classList.remove('text-blue-500');
                if (this.currentSong) {
                    const currentId = this.currentSong.spotifyId || this.currentSong.previewUrl || (this.currentSong.title + this.currentSong.artist);
                    this.queue = [...this.originalQueue];
                    this.currentIndex = this.queue.findIndex(s => {
                        const sid = s.spotifyId || s.previewUrl || (s.title + s.artist);
                        return sid === currentId;
                    });
                }
            }
        },

        toggleRepeat() {
            this.repeatMode = (this.repeatMode + 1) % 3;
            const btn = document.getElementById('player-repeat');
            const ind = document.getElementById('player-repeat-one-indicator');
            if (this.repeatMode === 0) {
                btn.classList.add('text-zinc-500');
                btn.classList.remove('text-blue-500');
                ind.classList.add('hidden');
            } else if (this.repeatMode === 1) {
                btn.classList.remove('text-zinc-500');
                btn.classList.add('text-blue-500');
                ind.classList.add('hidden');
            } else if (this.repeatMode === 2) {
                btn.classList.remove('text-zinc-500');
                btn.classList.add('text-blue-500');
                ind.classList.remove('hidden');
            }
        },

        playSong(song, list = null) {
            let listUpdated = false;
            if (list && list.length > 0) {
                let isSame = (this.originalQueue.length === list.length) && this.originalQueue.every((s, i) => s.previewUrl === list[i].previewUrl);
                if (!isSame) {
                    this.originalQueue = [...list];
                    listUpdated = true;
                }
            }

            if (listUpdated) {
                if (this.isShuffle) {
                    const currentId = song.spotifyId || song.previewUrl || (song.title + song.artist);
                    const remaining = [...this.originalQueue].filter(s => {
                        const sid = s.spotifyId || s.previewUrl || (s.title + s.artist);
                        return sid !== currentId;
                    });
                    for (let i = remaining.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [remaining[i], remaining[j]] = [remaining[j], remaining[i]];
                    }
                    this.queue = [song, ...remaining];
                } else {
                    this.queue = [...this.originalQueue];
                }
            }

            this.currentSong = song;
            const currentId = song.spotifyId || song.previewUrl || (song.title + song.artist);
            this.currentIndex = this.queue.findIndex(s => {
                const sid = s.spotifyId || s.previewUrl || (s.title + s.artist);
                return sid === currentId;
            });

            this.audio.pause();
            this.audio.src = '';
            if (ytReady) ytPlayer.stopVideo();

            // Pokud je to Spotify nebo iTunes preview a nemáme lokální soubor
            const isExternal = song.spotifyId || (song.previewUrl && song.previewUrl.includes('apple.com'));
            
            if (isExternal && !song.isFull) {
                this.isPlayingYT = true;
                this.durationEl.textContent = 'Hledám celou verzi...';
                
                fetch(`<?= BASE_URL ?>/index.php?url=song/getFullVersion&title=${encodeURIComponent(song.title)}&artist=${encodeURIComponent(song.artist)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.videoId && ytReady) {
                            ytPlayer.loadVideoById(data.videoId);
                            this.play();
                        } else {
                            this.isPlayingYT = false;
                            this.audio.src = song.previewUrl || '';
                            if (this.audio.src) this.play();
                        }
                    })
                    .catch(() => {
                        this.isPlayingYT = false;
                        this.audio.src = song.previewUrl || '';
                        if (this.audio.src) this.play();
                    });
            } else {
                this.isPlayingYT = false;
                this.audio.src = song.previewUrl || '';
                if (this.audio.src) this.play();
            }

            // Update UI
            const titleEl = document.getElementById('player-title');
            titleEl.textContent = song.title;
            document.getElementById('player-artist').textContent = song.artist;
            document.getElementById('player-cover').src = song.image || 'https://via.placeholder.com/100?text=No+Cover';
            
            const existingBadge = document.getElementById('player-full-badge');
            if (existingBadge) existingBadge.remove();
            
            const badge = document.createElement('span');
            badge.id = 'player-full-badge';
            badge.className = 'ml-2 bg-blue-600 text-[9px] font-black px-1.5 py-0.5 rounded uppercase tracking-tighter text-white inline-block';
            badge.textContent = 'Full';
            titleEl.appendChild(badge);

            this.bar.classList.remove('translate-y-full');
            document.body.classList.add('player-active');
        },

        toggle() {
            if (this.isPlayingYT && ytReady && ytPlayer) {
                try {
                    const state = ytPlayer.getPlayerState();
                    if (state === 1) this.pause(); // 1 = PLAYING
                    else this.play();
                } catch(e) { this.play(); }
            } else {
                if (this.audio.paused) this.play();
                else this.pause();
            }
        },

        play() {
            if (this.isPlayingYT) ytPlayer.playVideo();
            else this.audio.play();
            this.playIcon.classList.add('hidden');
            this.pauseIcon.classList.remove('hidden');
        },

        pause() {
            if (this.isPlayingYT) ytPlayer.pauseVideo();
            else this.audio.pause();
            this.playIcon.classList.remove('hidden');
            this.pauseIcon.classList.add('hidden');
        },

        onTrackEnd() {
            if (this.repeatMode === 2) {
                if (this.isPlayingYT) ytPlayer.seekTo(0);
                else this.audio.currentTime = 0;
                this.play();
            } else {
                this.next(false);
            }
        },

        next(forceNext = true) {
            if (!forceNext && this.repeatMode === 2) return;
            
            if (this.queue.length > 0) {
                if (this.currentIndex < this.queue.length - 1) {
                    this.playSong(this.queue[this.currentIndex + 1]);
                } else if (this.repeatMode === 1 || forceNext) {
                    // if forced and no repeat, we still jump to first? Or stop?
                    // Usually Spotify stops at the end if no repeat.
                    if (this.repeatMode === 1) {
                        this.playSong(this.queue[0]);
                    } else if (forceNext) {
                        this.playSong(this.queue[0]);
                        this.pause();
                    }
                }
            }
        },

        prev() {
            let curr = this.isPlayingYT && ytReady && ytPlayer.getCurrentTime ? ytPlayer.getCurrentTime() : this.audio.currentTime;
            if (curr > 3 || this.currentIndex === 0) {
                if (this.isPlayingYT && ytReady && ytPlayer.seekTo) ytPlayer.seekTo(0);
                else this.audio.currentTime = 0;
                this.play();
                return;
            }

            if (this.queue.length > 0 && this.currentIndex > 0) {
                this.playSong(this.queue[this.currentIndex - 1]);
            } else if (this.queue.length > 0 && this.repeatMode === 1) {
                this.playSong(this.queue[this.queue.length - 1]);
            }
        },

        updateProgress() {
            let curr, dur;
            if (this.isPlayingYT && ytReady) {
                curr = ytPlayer.getCurrentTime();
                dur = ytPlayer.getDuration();
            } else {
                curr = this.audio.currentTime;
                dur = this.audio.duration;
            }

            if (!dur || dur === 0) {
                if (this.isPlayingYT) this.durationEl.textContent = 'Full Track';
                return;
            }
            
            const perc = (curr / dur) * 100;
            this.progress.style.width = perc + '%';
            this.handle.style.left = perc + '%';
            this.currentTimeEl.textContent = this.formatTime(curr);
            this.durationEl.textContent = this.formatTime(dur);
        },

        formatTime(seconds) {
            if (isNaN(seconds) || seconds < 0) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        }
    };

    MusicPlayer.init();
    window.MusicPlayer = MusicPlayer;
    </script>

</body>
</html>