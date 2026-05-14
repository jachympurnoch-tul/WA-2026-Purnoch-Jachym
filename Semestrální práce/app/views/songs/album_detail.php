<?php require_once '../app/views/layout/header.php'; ?>

<main class="w-full pb-32">
    <!-- Album Header -->
    <div class="relative h-64 md:h-80 w-full flex items-end p-6 md:p-10 bg-gradient-to-t from-zinc-950 to-zinc-900/50">
        <div class="absolute inset-0 z-0 overflow-hidden">
            <img src="<?= $album['image'] ?>" class="w-full h-full object-cover blur-3xl opacity-20" alt="">
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-end w-full max-w-7xl mx-auto">
            <div class="w-48 h-48 md:w-60 md:h-60 flex-shrink-0 shadow-2xl rounded-lg overflow-hidden group">
                <img src="<?= $album['image'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?= $album['name'] ?>">
            </div>
            
            <div class="flex-1 text-center md:text-left">
                <p class="text-white/60 text-xs font-black uppercase tracking-widest mb-2">Album</p>
                <h1 class="text-4xl md:text-6xl xl:text-7xl font-black text-white mb-4 tracking-tighter"><?= $album['name'] ?></h1>
                
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-4 gap-y-2 text-sm font-bold">
                    <a href="<?= BASE_URL ?>/index.php?url=artist/show/<?= $album['artistId'] ?>" class="text-white hover:underline flex items-center gap-2">
                        <span><?= $album['artist'] ?></span>
                    </a>
                    <span class="text-white/40">•</span>
                    <span class="text-white/60"><?= substr($album['release_date'], 0, 4) ?></span>
                    <span class="text-white/40">•</span>
                    <span class="text-white/60"><?= count($album['tracks']) ?> skladeb</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="max-w-7xl mx-auto px-6 py-8 flex items-center gap-6">
        <button id="play-all-btn" class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center hover:scale-105 active:scale-95 transition-transform shadow-xl shadow-blue-600/20 group">
            <svg class="w-6 h-6 ml-1 group-hover:fill-current" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
        </button>
        <button class="text-white/60 hover:text-white transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
        </button>
    </div>

    <!-- Tracklist -->
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="grid grid-cols-[16px_1fr_48px] md:grid-cols-[40px_1fr_200px_48px] gap-4 px-4 py-2 text-zinc-500 font-bold text-[10px] uppercase tracking-widest border-b border-white/5 mb-2">
            <div class="text-center">#</div>
            <div>Název</div>
            <div class="hidden md:block">Album</div>
            <div class="flex justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        </div>

        <div id="tracks-container">
            <?php foreach ($album['tracks'] as $index => $song): ?>
                <div class="song-row group grid grid-cols-[16px_1fr_48px] md:grid-cols-[40px_1fr_200px_48px] gap-4 px-4 py-3 rounded-lg hover:bg-white/5 transition-colors cursor-pointer items-center" 
                     data-song='<?= json_encode($song) ?>'>
                    <div class="text-zinc-500 font-medium text-sm group-hover:text-blue-500 text-center transition-colors">
                        <span class="group-hover:hidden"><?= $index + 1 ?></span>
                        <svg class="w-3 h-3 hidden group-hover:block mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-white font-semibold text-sm truncate"><?= $song['title'] ?></div>
                        <div class="text-zinc-500 text-xs font-medium hover:underline inline-block">
                            <a href="<?= BASE_URL ?>/index.php?url=artist/show/<?= $song['artistId'] ?>"><?= $song['artist'] ?></a>
                        </div>
                    </div>
                    <div class="hidden md:block text-zinc-400 text-xs font-medium truncate"><?= $song['album'] ?></div>
                    <div class="flex items-center justify-center gap-4">
                        <button class="add-to-playlist-btn opacity-0 group-hover:opacity-100 text-zinc-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </button>
                        <span class="text-zinc-500 text-xs font-medium">3:30</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const albumTracks = <?= json_encode($album['tracks']) ?>;
    
    // Play song on row click
    document.querySelectorAll('.song-row').forEach((row, idx) => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const song = JSON.parse(row.dataset.song);
            if (window.MusicPlayer) {
                window.MusicPlayer.playSong(song, albumTracks);
            }
        });
    });

    // Play all button
    document.getElementById('play-all-btn').onclick = () => {
        if (window.MusicPlayer && albumTracks.length > 0) {
            window.MusicPlayer.playSong(albumTracks[0], albumTracks);
        }
    };

    // Add to playlist buttons
    document.querySelectorAll('.add-to-playlist-btn').forEach(btn => {
        btn.onclick = (e) => {
            e.stopPropagation();
            const row = btn.closest('.song-row');
            const song = JSON.parse(row.dataset.song);
            if (window.showPlaylistToast) {
                window.showPlaylistToast(song);
            }
        };
    });
});
</script>

<?php require_once '../app/views/layout/footer.php'; ?>
