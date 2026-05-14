<?php require_once '../app/views/layout/header.php'; ?>

<main class="w-full pb-32">
    <!-- Artist Header -->
    <div class="relative h-[40vh] md:h-[50vh] w-full flex items-end p-6 md:p-12 overflow-hidden">
        <!-- Background Banner -->
        <div class="absolute inset-0 z-0">
            <img src="<?= $artist['image'] ?>" class="w-full h-full object-cover filter brightness-50" alt="">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto">
            <div class="flex items-center gap-2 text-blue-500 mb-2">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
                <span class="text-xs font-black uppercase tracking-widest text-white">Ověřený umělec</span>
            </div>
            <h1 class="text-5xl md:text-8xl xl:text-9xl font-black text-white mb-6 tracking-tighter"><?= $artist['name'] ?></h1>
            <p class="text-white font-bold text-sm md:text-base"><?= number_format($artist['followers']) ?> posluchačů měsíčně</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Controls -->
        <div class="flex items-center gap-6 mb-10">
            <button id="play-popular-btn" class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center hover:scale-105 active:scale-95 transition-transform shadow-xl shadow-blue-600/20 group">
                <svg class="w-6 h-6 ml-1 group-hover:fill-current" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
            </button>
            <button class="px-6 py-2 border border-white/20 rounded-full text-white text-sm font-black uppercase tracking-widest hover:border-white hover:bg-white/5 transition-all">Sledovat</button>
            <button class="text-white/60 hover:text-white transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
            <!-- Popular Tracks -->
            <div class="xl:col-span-2">
                <h2 class="text-2xl font-black text-white mb-6">Populární</h2>
                <div id="popular-tracks">
                    <?php foreach ($topTracks as $index => $song): ?>
                        <div class="song-row group flex items-center gap-4 px-4 py-2.5 rounded-lg hover:bg-white/5 transition-colors cursor-pointer" 
                             data-song='<?= json_encode($song) ?>'>
                            <div class="w-8 text-center text-zinc-500 font-medium text-sm group-hover:text-blue-500">
                                <span class="group-hover:hidden"><?= $index + 1 ?></span>
                                <svg class="w-3 h-3 hidden group-hover:block mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                            </div>
                            <img src="<?= $song['image'] ?>" class="w-10 h-10 rounded shadow-lg" alt="">
                            <div class="flex-1 min-w-0">
                                <div class="text-white font-semibold text-sm truncate"><?= $song['title'] ?></div>
                            </div>
                            <div class="hidden md:block text-zinc-500 text-xs font-medium px-4">1,234,567</div>
                            <div class="flex items-center gap-4">
                                <button class="add-to-playlist-btn opacity-0 group-hover:opacity-100 text-zinc-500 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </button>
                                <span class="text-zinc-500 text-xs font-medium">3:30</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Artist Pick / Stats -->
            <div class="hidden xl:block">
                <h2 class="text-2xl font-black text-white mb-6">O umělci</h2>
                <div class="bg-zinc-900 rounded-2xl overflow-hidden group cursor-pointer relative aspect-square">
                    <img src="<?= $artist['image'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="">
                    <div class="absolute inset-0 bg-black/40 p-8 flex flex-col justify-end">
                        <div class="bg-blue-600 w-fit p-1.5 rounded-full mb-4">
                             <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-white font-bold line-clamp-4 text-sm leading-relaxed">
                            <?= $artist['name'] ?> patří mezi přední představitele svého žánru. S miliony streamů a vyprodanými koncerty neustále posouvá hranice hudební produkce.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Albums Section -->
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-black text-white tracking-tight">Diskografie</h2>
                <button class="text-xs font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">Zobrazit vše</button>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                <?php foreach ($albums as $album): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=album/show/<?= $album['id'] ?>" class="group bg-zinc-900/40 p-4 rounded-xl hover:bg-zinc-800/60 transition-all duration-500 border border-transparent hover:border-white/5 shadow-xl">
                        <div class="relative aspect-square mb-4 shadow-2xl rounded-lg overflow-hidden">
                            <img src="<?= $album['image'] ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-[2px]">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-xl translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                    <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-white font-bold text-sm truncate mb-1"><?= $album['name'] ?></h3>
                        <p class="text-zinc-500 text-xs font-black uppercase tracking-widest"><?= substr($album['release_date'], 0, 4) ?> • <?= ucfirst($album['type']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const popularTracks = <?= json_encode($topTracks) ?>;

    document.querySelectorAll('.song-row').forEach((row, idx) => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('button')) return;
            const song = JSON.parse(row.dataset.song);
            if (window.MusicPlayer) {
                window.MusicPlayer.playSong(song, popularTracks);
            }
        });
    });

    document.getElementById('play-popular-btn').onclick = () => {
        if (window.MusicPlayer && popularTracks.length > 0) {
            window.MusicPlayer.playSong(popularTracks[0], popularTracks);
        }
    };

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
