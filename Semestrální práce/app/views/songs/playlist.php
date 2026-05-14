<?php require_once '../app/views/layout/header.php'; ?>

<?php
// $playlist and $songs are passed in from SongController::viewPlaylist()
$playlistName = htmlspecialchars($playlist['name'] ?? 'Playlist');
$isDefault = !empty($playlist['is_default']);

// Build gradient cover from first songs
$covers = [];
foreach ($songs as $s) {
    $imgs = json_decode($s['images'] ?? '[]', true);
    if (!empty($imgs[0])) {
        $img = $imgs[0];
        $covers[] = (strpos($img, 'http') === 0) ? $img : BASE_URL . '/uploads/' . $img;
    }
    if (count($covers) >= 4) break;
}

$totalDuration = 0;
foreach ($songs as $s) {
    // Parse duration like "3:45" → seconds
    $parts = explode(':', $s['duration'] ?? '0:00');
    $totalDuration += (int)($parts[0] ?? 0) * 60 + (int)($parts[1] ?? 0);
}
$hours = floor($totalDuration / 3600);
$mins  = floor(($totalDuration % 3600) / 60);
$durationStr = $hours > 0 ? "{$hours} h {$mins} min" : "{$mins} min";

$gradients = ['from-violet-700 to-purple-900','from-rose-700 to-pink-900','from-cyan-700 to-blue-900','from-amber-700 to-orange-900','from-emerald-700 to-teal-900'];
$grad = $gradients[($playlist['id'] ?? 0) % count($gradients)];
?>

<style>
.song-row { transition: background 0.15s ease; }
.song-row:hover { background: rgba(255,255,255,0.05); }
.song-row:hover .row-num { display: none; }
.song-row:hover .row-play { display: flex; }
.row-play { display: none; }
</style>

<main class="w-full pb-32">

    <!-- Header sekce playlistu -->
    <div class="bg-gradient-to-b <?= $grad ?> to-zinc-900 pt-8 pb-6">
        <div class="container mx-auto px-6">
            <div class="flex items-end gap-8">

                <!-- Cover art -->
                <div class="flex-shrink-0 w-48 h-48 rounded-xl shadow-2xl overflow-hidden">
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
                        <div class="w-full h-full bg-zinc-800 flex items-center justify-center">
                            <svg class="w-20 h-20 text-zinc-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="flex flex-col gap-2 pb-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-white/70">Playlist</span>
                    <h2 class="text-5xl font-black text-white tracking-tight"><?= $playlistName ?></h2>
                    <p class="text-white/60 text-sm mt-1">
                        <?= count($songs) ?> <?= count($songs) === 1 ? 'skladba' : (count($songs) < 5 ? 'skladby' : 'skladeb') ?>
                        <?php if ($totalDuration > 0): ?> · <?= $durationStr ?><?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Akční tlačítka -->
            <div class="flex items-center gap-4 mt-8">
                <?php if (!empty($songs)): ?>
                <button id="play-all-btn" onclick="playAll()"
                    class="w-14 h-14 bg-blue-500 hover:bg-blue-400 rounded-full flex items-center justify-center shadow-xl hover:scale-105 active:scale-95 transition-all">
                    <svg class="w-7 h-7 ml-1 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/index.php?url=song/playlist" data-nav
                   class="text-zinc-400 hover:text-white transition-colors text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Zpět na playlisty
                </a>
            </div>
        </div>
    </div>

    <!-- Seznam skladeb -->
    <div class="container mx-auto px-6 mt-6">

        <?php if (empty($songs)): ?>
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 rounded-full bg-zinc-800 flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-zinc-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Tento playlist je prázdný</h3>
                <p class="text-zinc-500 text-sm">Přidej skladby z hlavní stránky nebo je nahraj.</p>
                <a href="<?= BASE_URL ?>/index.php" data-nav
                   class="mt-6 bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2.5 rounded-full text-sm transition-colors">
                    Procházet hudbu
                </a>
            </div>

        <?php else: ?>

            <!-- Hlavička tabulky -->
            <div class="grid grid-cols-[32px_minmax(0,1fr)_minmax(0,1fr)_140px] gap-4 px-4 py-2 text-zinc-500 text-xs font-bold uppercase tracking-widest border-b border-zinc-800 mb-2">
                <span class="text-center">#</span>
                <span>Název</span>
                <span>Album</span>
                <span class="text-right pr-2">Délka</span>
            </div>

            <!-- Řádky skladeb -->
            <div id="playlist-songs">
                <?php foreach ($songs as $i => $song):
                    $imgs = json_decode($song['images'] ?? '[]', true);
                    $cover = null;
                    if (!empty($imgs[0])) {
                        $cover = (strpos($imgs[0], 'http') === 0) ? $imgs[0] : BASE_URL . '/public/uploads/' . $imgs[0];
                    }
                    $audioSrc = $song['audio_file'] ? BASE_URL . '/public/audio/' . $song['audio_file'] : ($song['link'] ?? '');
                    $isFull = !empty($song['audio_file']);
                    $isExternal = !empty($song['is_external']);
                ?>
                <div class="song-row group grid grid-cols-[32px_minmax(0,1fr)_minmax(0,1fr)_140px] gap-4 items-center px-4 py-3 rounded-xl cursor-pointer"
                     data-song='<?= htmlspecialchars(json_encode([
                        'title'      => $song['title'],
                        'artist'     => $song['artist'],
                        'artistId'   => $song['artist_id'] ?? '',
                        'album'      => $song['album'],
                        'albumId'    => $song['album_id'] ?? '',
                        'image'      => $cover ?? BASE_URL . '/images/placeholder.png',
                        'previewUrl' => $audioSrc,
                        'isFull'     => $isFull,
                        'isLocal'    => $isFull,
                     ])) ?>'>

                    <!-- Číslo / Play -->
                    <div class="w-8 flex items-center justify-center relative">
                        <span class="row-num text-zinc-500 text-sm"><?= $i + 1 ?></span>
                        <button class="row-play w-8 h-8 items-center justify-center text-white">
                            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                    </div>

                    <!-- Název + interpret -->
                    <div class="flex items-center gap-3 overflow-hidden">
                        <?php if ($cover): ?>
                            <img src="<?= htmlspecialchars($cover) ?>" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-lg bg-zinc-800 flex-shrink-0 flex items-center justify-center">
                                <svg class="w-5 h-5 text-zinc-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                            </div>
                        <?php endif; ?>
                        <div class="overflow-hidden">
                            <p class="text-white font-semibold text-sm truncate hover:underline">
                                <a href="<?= BASE_URL ?>/index.php?url=album/show/<?= $song['album_id'] ?>" data-nav><?= htmlspecialchars($song['title']) ?></a>
                            </p>
                            <p class="text-zinc-400 text-xs truncate hover:underline">
                                <a href="<?= BASE_URL ?>/index.php?url=artist/show/<?= $song['artist_id'] ?>" data-nav><?= htmlspecialchars($song['artist']) ?></a>
                            </p>
                        </div>
                        <?php if ($isFull): ?>
                            <span class="flex-shrink-0 bg-blue-600 text-white text-[8px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider">Full</span>
                        <?php endif; ?>
                    </div>

                    <!-- Album -->
                    <div class="overflow-hidden">
                        <p class="text-zinc-400 text-sm truncate hover:underline">
                            <a href="<?= BASE_URL ?>/index.php?url=album/show/<?= $song['album_id'] ?>" data-nav><?= htmlspecialchars($song['album'] ?: '—') ?></a>
                        </p>
                    </div>

                    <!-- Délka + akce -->
                    <div class="flex items-center justify-end gap-3 w-full">
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick='event.stopPropagation(); openPlaylistModal(<?= htmlspecialchars(json_encode([
                                "title" => $song["title"],
                                "artist" => $song["artist"],
                                "artistId" => $song["artist_id"] ?? "",
                                "album" => $song["album"],
                                "albumId" => $song["album_id"] ?? "",
                                "image" => json_decode($song["images"], true)[0] ?? "",
                                "previewUrl" => $song["link"] ?? $song["audio_file"]
                            ]), ENT_QUOTES, "UTF-8") ?>)' class="text-zinc-500 hover:text-white transition-colors" title="Přidat do jiného playlistu">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                            <?php if (!$isExternal): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=song/edit/<?= $song['id'] ?>" data-nav
                                   onclick="event.stopPropagation()"
                                   class="text-zinc-500 hover:text-white transition-colors" title="Upravit skladbu">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?url=song/delete/<?= $song['id'] ?>"
                                   onclick="event.stopPropagation(); return confirm('Smazat tuto skladbu?')"
                                   class="text-zinc-500 hover:text-red-400 transition-colors" title="Smazat skladbu">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/index.php?url=song/removeExternal&playlist_id=<?= $playlist['id'] ?>&title=<?= urlencode($song['title']) ?>&artist=<?= urlencode($song['artist']) ?>"
                                   onclick="event.stopPropagation(); return confirm('Odebrat z playlistu?')"
                                   class="text-zinc-500 hover:text-red-400 transition-colors" title="Odebrat z playlistu">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                        <span class="text-zinc-500 text-sm font-mono text-right w-12"><?= htmlspecialchars($song['duration'] ?: '—') ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</main>

<script>
(function() {
    // Sestavení fronty všech skladeb v playlistu
    const allSongs = Array.from(document.querySelectorAll('#playlist-songs .song-row')).map(row => {
        try { return JSON.parse(row.dataset.song); } catch(e) { return null; }
    }).filter(Boolean);

    document.querySelectorAll('#playlist-songs .song-row').forEach((row, i) => {
        row.addEventListener('click', () => {
            if (window.MusicPlayer) window.MusicPlayer.playSong(allSongs[i], allSongs);
        });
    });

    window.playAll = function() {
        if (allSongs.length && window.MusicPlayer) {
            window.MusicPlayer.playSong(allSongs[0], allSongs);
        }
    };
})();
</script>

    <!-- Modal: výběr playlistů -->
    <div id="playlist-select-modal" class="hidden fixed inset-0 z-[300] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-zinc-900 border border-zinc-800 w-full max-w-sm rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-zinc-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Uložit do playlistu</h3>
                    <p id="modal-song-name" class="text-zinc-500 text-xs mt-0.5 truncate"></p>
                </div>
                <button onclick="document.getElementById('playlist-select-modal').classList.add('hidden')" class="text-zinc-600 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="playlist-checkboxes" class="max-h-72 overflow-y-auto divide-y divide-zinc-800">
                <div class="p-4 text-zinc-500 text-sm">Načítám...</div>
            </div>
            <div class="p-4 flex gap-3 justify-between items-center bg-zinc-800/50">
                <span id="modal-status" class="text-zinc-500 text-xs"></span>
                <button id="playlist-modal-done" class="bg-white text-black font-black px-7 py-2.5 rounded-full hover:scale-105 active:scale-95 transition-transform text-sm">
                    Hotovo
                </button>
            </div>
        </div>
    </div>

<script>
let _currentModalSong = null;
const BASE = '<?= BASE_URL ?>';

async function openPlaylistModal(song) {
    _currentModalSong = song;
    const modal    = document.getElementById('playlist-select-modal');
    const listEl   = document.getElementById('playlist-checkboxes');
    const nameEl   = document.getElementById('modal-song-name');
    const statusEl = document.getElementById('modal-status');

    nameEl.textContent = `${song.artist} — ${song.title}`;
    listEl.innerHTML   = '<div class="p-4 text-zinc-500 text-sm">Načítám...</div>';
    statusEl.textContent = '';
    modal.classList.remove('hidden');

    try {
        const res  = await fetch(`${BASE}/index.php?url=song/getPlaylistsForSong&title=${encodeURIComponent(song.title)}&artist=${encodeURIComponent(song.artist)}`);
        const data = await res.json();

        if (data.error === 'not_logged_in') {
            listEl.innerHTML = '<div class="p-4 text-zinc-500 text-sm">Přihlaste se pro správu playlistů.</div>';
            return;
        }

        listEl.innerHTML = data.map(p => `
            <label class="flex items-center gap-4 px-5 py-4 hover:bg-zinc-800 transition-colors cursor-pointer">
                <input type="checkbox" class="playlist-checkbox w-5 h-5 rounded accent-blue-500 cursor-pointer" 
                       value="${p.id}" ${p.has_song ? 'checked' : ''}>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-semibold text-sm truncate">${escHtml(p.name)}</p>
                    <p class="text-zinc-500 text-xs">${p.song_count || 0} skladeb</p>
                </div>
                ${p.is_default ? '<span class="text-[9px] bg-blue-600 text-white px-1.5 py-0.5 rounded-full font-black uppercase">Výchozí</span>' : ''}
            </label>
        `).join('');

    } catch(e) {
        listEl.innerHTML = '<div class="p-4 text-red-400 text-sm">Chyba při načítání.</div>';
    }
}

document.getElementById('playlist-modal-done').onclick = async () => {
    if (!_currentModalSong) return;
    const statusEl = document.getElementById('modal-status');
    const selected = Array.from(document.querySelectorAll('.playlist-checkbox:checked')).map(cb => parseInt(cb.value));

    statusEl.textContent = 'Ukládám...';

    try {
        const res  = await fetch(`${BASE}/index.php?url=song/syncSongPlaylists`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title:      _currentModalSong.title,
                artist:     _currentModalSong.artist,
                album:      _currentModalSong.album,
                image:      _currentModalSong.image,
                previewUrl: _currentModalSong.previewUrl,
                playlistIds: selected
            })
        });
        const data = await res.json();

        document.getElementById('playlist-select-modal').classList.add('hidden');
        window.location.reload();
    } catch(e) {
        statusEl.textContent = 'Chyba.';
    }
};

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<?php require_once '../app/views/layout/footer.php'; ?>
