<?php require_once '../app/views/layout/header.php'; ?>

<?php
require_once '../app/models/Database.php';
require_once '../app/models/Playlist.php';
require_once '../app/models/Song.php';
$db = (new Database())->getConnection();
$playlistModel = new Playlist($db);
$songModel = new Song($db);

$playlists = [];
if (isset($_SESSION['user_id'])) {
    $playlists = $playlistModel->getByUser($_SESSION['user_id']);
    if (empty($playlists)) {
        $playlistModel->createDefault($_SESSION['user_id']);
        $playlists = $playlistModel->getByUser($_SESSION['user_id']);
    }
}
?>



<main class="w-full pb-32 pt-8">
    <div class="container mx-auto px-6">

        <!-- Nadpis -->
        <div class="mb-10">
            <h2 class="text-4xl font-black text-white tracking-tight mb-1">Vaše knihovna</h2>
            <p class="text-zinc-400 text-sm">Playlisty a sbírky skladeb</p>
        </div>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <div class="w-24 h-24 rounded-full bg-zinc-800 flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Začněte svou hudební cestu</h3>
                <p class="text-zinc-400 mb-8 max-w-xs">Přihlaste se a vytvořte si vlastní playlisty se svými oblíbenými skladbami.</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="bg-white text-black font-black px-8 py-3 rounded-full hover:scale-105 transition-transform" data-nav>
                    Přihlásit se
                </a>
            </div>

        <?php else: ?>

            <!-- Grid playlistů -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

                <?php
                $gradients = [
                    'bg-gradient-to-br from-indigo-500 to-violet-500',
                    'bg-gradient-to-br from-pink-500 to-rose-500',
                    'bg-gradient-to-br from-teal-500 to-cyan-500',
                    'bg-gradient-to-br from-amber-500 to-red-500',
                    'bg-gradient-to-br from-green-500 to-emerald-500',
                    'bg-gradient-to-br from-blue-500 to-indigo-500'
                ];
                $icons = [
                    '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                    '<path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>',
                    '<path d="M5 3l14 9-14 9V3z"/>',
                    '<path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>',
                ];
                foreach ($playlists as $i => $playlist):
                    // Počet skladeb už máme v $playlist['song_count'] z modelu
                    $count = (int)$playlist['song_count'];
                    $grad = $gradients[$i % count($gradients)];
                    $icon = $icons[$i % count($icons)];

                    // Pro koláž potřebujeme aspoň pár coverů (interních i externích)
                    require_once '../app/models/ExternalSong.php';
                    $internal = $songModel->getAllByPlaylist($playlist['id']);
                    $external = (new ExternalSong($db))->getByPlaylist($playlist['id']);
                    
                    $covers = [];
                    // Přidáme interní
                    foreach ($internal as $s) {
                        $imgs = json_decode($s['images'] ?? '[]', true);
                        if (!empty($imgs[0])) $covers[] = BASE_URL . '/uploads/' . $imgs[0];
                    }
                    // Přidáme externí
                    foreach ($external as $s) {
                        if (!empty($s['image'])) $covers[] = $s['image'];
                    }
                    shuffle($covers); // Náhodný mix pro koláž
                ?>
                <div onclick="window.location.href='<?= BASE_URL ?>/index.php?url=song/viewPlaylist/<?= $playlist['id'] ?>'"
                   class="bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-800 hover:border-zinc-600 hover:shadow-2xl transition-all duration-200 hover:-translate-y-1 group cursor-pointer flex flex-col">

                    <!-- Cover -->
                    <div class="relative aspect-square overflow-hidden">
                        <?php if (!empty($playlist['image'])): ?>
                            <img src="<?= BASE_URL . '/uploads/' . htmlspecialchars($playlist['image']) ?>" class="w-full h-full object-cover">
                        <?php elseif (count($covers) >= 4): ?>
                            <div class="grid grid-cols-2 w-full h-full">
                                <?php foreach (array_slice($covers, 0, 4) as $c): ?>
                                    <img src="<?= htmlspecialchars($c) ?>" class="w-full h-full object-cover">
                                <?php endforeach; ?>
                            </div>
                        <?php elseif (count($covers) === 1): ?>
                            <img src="<?= htmlspecialchars($covers[0]) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="<?= $grad ?> w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/80" fill="currentColor" viewBox="0 0 24 24">
                                    <?= $icon ?>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <!-- Play overlay -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-xl transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                <svg class="w-7 h-7 ml-1 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                        <?php if (!empty($playlist['is_default'])): ?>
                            <div class="absolute top-2 left-2 bg-blue-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider shadow-lg">
                                Výchozí
                            </div>
                        <?php else: ?>
                            <div class="absolute top-2 right-2 z-20" onclick="event.stopPropagation()">
                                <button type="button" onclick="toggleMenu('menu-<?= $playlist['id'] ?>')" class="w-8 h-8 flex items-center justify-center bg-black/40 hover:bg-black/60 rounded-full text-white backdrop-blur-md transition-colors shadow-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </button>
                                <div id="menu-<?= $playlist['id'] ?>" class="hidden absolute right-0 mt-2 w-48 bg-zinc-800 rounded-lg shadow-xl overflow-hidden border border-zinc-700 py-1 origin-top-right">
                                    <button type="button" onclick="openImageModal(<?= $playlist['id'] ?>)" class="w-full text-left px-4 py-2 text-sm text-white hover:bg-zinc-700 transition-colors">Upravit úvodní fotku</button>
                                    <a href="<?= BASE_URL ?>/index.php?url=playlist/delete/<?= $playlist['id'] ?>" onclick="return confirm('Opravdu odstranit celý playlist?')" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-zinc-700 transition-colors">Odstranit playlist</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="text-white font-bold text-sm truncate mb-1"><?= htmlspecialchars($playlist['name']) ?></h3>
                        <p class="text-zinc-500 text-xs"><?= $count ?> <?= $count === 1 ? 'skladba' : ($count < 5 ? 'skladby' : 'skladeb') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Nový playlist -->
                <div class="bg-zinc-900/50 rounded-2xl border border-dashed border-zinc-700 hover:border-zinc-500 cursor-pointer flex flex-col items-center justify-center aspect-square gap-3 text-zinc-500 hover:text-zinc-300 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl"
                     onclick="document.getElementById('new-playlist-modal').classList.remove('hidden')">
                    <div class="w-14 h-14 rounded-full bg-zinc-800 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider">Nový playlist</span>
                </div>
            </div>

        <?php endif; ?>

        <!-- Modal pro nový playlist -->
        <div id="new-playlist-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-700 w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl">
                <form method="POST" action="<?= BASE_URL ?>/index.php?url=playlist/create">
                    <div class="p-6 border-b border-zinc-800">
                        <h3 class="text-xl font-bold text-white mb-4">Nový playlist</h3>
                        <input type="text" name="name" placeholder="Název playlistu..."
                               class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                               autofocus required>
                    </div>
                    <div class="p-4 flex gap-3 justify-end bg-zinc-800/50">
                        <button type="button" onclick="document.getElementById('new-playlist-modal').classList.add('hidden')"
                                class="px-5 py-2 text-sm text-zinc-400 hover:text-white transition-colors font-medium">
                            Zrušit
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2 rounded-full text-sm transition-colors">
                            Vytvořit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal pro fotku playlistu -->
        <div id="image-playlist-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-700 w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl">
                <form id="image-form" method="POST" action="" enctype="multipart/form-data">
                    <div class="p-6 border-b border-zinc-800">
                        <h3 class="text-xl font-bold text-white mb-4">Upravit fotku playlistu</h3>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer" required>
                    </div>
                    <div class="p-4 flex gap-3 bg-zinc-800/50 justify-between items-center">
                        <!-- Obnovit výchozí -->
                        <a id="image-reset-link" href="#"
                           onclick="return confirm('Obnovit výchozí cover?')"
                           class="px-4 py-2 text-xs text-zinc-500 hover:text-red-400 transition-colors font-medium border border-zinc-700 rounded-full">
                            Obnovit výchozí
                        </a>
                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('image-playlist-modal').classList.add('hidden')"
                                    class="px-5 py-2 text-sm text-zinc-400 hover:text-white transition-colors font-medium">
                                Zrušit
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2 rounded-full text-sm transition-colors">
                                Nahrát
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
        window.toggleMenu = function(id) {
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                if (el.id !== id) el.classList.add('hidden');
            });
            document.getElementById(id).classList.toggle('hidden');
        };

        window.openImageModal = function(id) {
            document.getElementById('menu-' + id).classList.add('hidden');
            document.getElementById('image-form').action = '<?= BASE_URL ?>/index.php?url=playlist/updateImage/' + id;
            document.getElementById('image-reset-link').href = '<?= BASE_URL ?>/index.php?url=playlist/resetImage/' + id;
            document.getElementById('image-playlist-modal').classList.remove('hidden');
        };

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.z-20')) {
                document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
            }
        });
        </script>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
