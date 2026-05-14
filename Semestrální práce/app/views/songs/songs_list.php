<?php require_once '../app/views/layout/header.php'; ?>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none !important; }
.scrollbar-hide { 
    -ms-overflow-style: none !important; 
    scrollbar-width: none !important;
}

#playlist-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translate(-50%, 100px);
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55), bottom 0.3s ease;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(24, 24, 27, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    padding: 12px 24px;
    border-radius: 9999px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    min-width: 300px;
    max-width: 90vw;
    pointer-events: none;
    gap: 16px;
}
#playlist-toast.visible { transform: translate(-50%, 0); opacity: 1; pointer-events: auto; }

/* Posun toastu nahoru, když je aktivní přehrávač */
body.player-active #playlist-toast {
    bottom: 110px;
}

/* Šipky pro posun kategorií */
.category-row-container:hover .scroll-arrow { opacity: 1; }
.scroll-arrow { 
    opacity: 0; 
    transition: all 0.3s ease;
    z-index: 20;
}
.scroll-arrow:disabled { 
    opacity: 0.3 !important; 
    cursor: default;
    background: rgba(24, 24, 27, 0.5) !important;
}
</style>

<main class="w-full pb-28 pt-3 overflow-hidden">


    <!-- Kategorie -->
    <div id="categories-container" class="space-y-4 container mx-auto px-4"></div>

    <!-- Výsledky hledání -->
    <div id="search-results-container" class="hidden container mx-auto px-4"></div>

    <!-- Toast -->
    <div id="playlist-toast" role="alert">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-1.5 rounded-full flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="font-semibold text-sm">Uloženo do <span id="toast-playlist-name" class="underline font-black">Oblíbené</span></p>
        </div>
        <button id="toast-change-btn" class="text-xs font-black uppercase tracking-widest hover:scale-110 transition-transform bg-white/10 px-3 py-1.5 rounded-lg border border-white/20 flex-shrink-0">Změnit</button>
    </div>

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

    <!-- Template pro kartu skladby -->
    <template id="song-card-template">
        <div class="flex-shrink-0 w-32 md:w-36 xl:w-40 group snap-start relative">
            <div class="relative aspect-square mb-2">
                <img class="song-cover w-full h-full object-cover rounded-lg shadow-lg group-hover:shadow-blue-500/20 transition-all duration-500 bg-zinc-800" src="" alt="Cover">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-500 rounded-lg flex items-center justify-center backdrop-blur-[2px]">
                    <button class="play-btn w-10 h-10 bg-white text-black rounded-full flex items-center justify-center hover:scale-110 transition-transform shadow-xl active:scale-95">
                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                    </button>
                </div>
            </div>
            <div class="px-0.5 pr-7 relative">
                <a href="" class="song-album-link block group-hover:underline" data-nav>
                    <h4 class="song-title text-white font-semibold text-xs truncate mb-0.5"></h4>
                </a>
                <a href="" class="song-artist-link block hover:underline" data-nav>
                    <p class="song-artist text-zinc-400 font-medium text-[11px] truncate"></p>
                </a>
                <button class="add-btn absolute top-1 right-0 w-6 h-6 rounded-full bg-white/10 hover:bg-blue-500 text-zinc-400 hover:text-white border border-white/5 transition-all flex items-center justify-center active:scale-90 z-20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>
        </div>
    </template>

    <script>
    (function() {
        const BASE_URL_LOCAL = '<?= BASE_URL ?>';

        // Statické kategorie
        const categories = [
            { id: 'new',        title: 'Novinky',           query: 'new release' },
            { id: 'trending',   title: 'Trendy',            query: 'top hits' },
            { id: 'pop',        title: 'Pop',               query: 'pop hits' },
            { id: 'rap',        title: 'Rap & Hip-Hop',     query: 'hip hop rap 2024' },
            { id: 'rock',       title: 'Rock',              query: 'rock hits' },
            { id: 'y2k',        title: '00s Nostalgie',     query: '2000s hits' },
            { id: '90s',        title: '90s',               query: '90s hits' },
            { id: '80s',        title: '80s',               query: '80s hits' },
        ];

        const container       = document.getElementById('categories-container');
        const searchContainer = document.getElementById('search-results-container');
        const template        = document.getElementById('song-card-template');

        // --- Správa historie přehraných skladeb ---
        function getPlayHistory() {
            try { return JSON.parse(localStorage.getItem('playHistory') || '[]'); } catch(e) { return []; }
        }
        function savePlayedSong(song) {
            let hist = getPlayHistory().filter(s => s.artist !== song.artist || s.title !== song.title);
            hist.unshift({ title: song.title, artist: song.artist });
            hist = hist.slice(0, 20);
            localStorage.setItem('playHistory', JSON.stringify(hist));
        }

        function initPage() {
            if (!container) return;
            container.innerHTML = '';
            searchContainer.classList.add('hidden');
            container.classList.remove('hidden');

            // Personalizovaná sekce podle autorů, které posloucháte
            const history = getPlayHistory();
            if (history.length > 0) {
                const artists = [...new Set(history.map(s => s.artist))];
                const topArtist = artists[0]; 
                const activityCat = { id: 'activity', title: 'Na základě vaší aktivity', query: topArtist };
                renderCategoryRow(activityCat);
            }

            // NOVÁ SEKCE: Moje nahrané písničky
            renderCategoryRow({ id: 'my-local', title: 'Moje nahrané písničky', query: 'local' });

            // Statické kategorie ze Spotify
            categories.forEach(cat => renderCategoryRow(cat));

            // Search query z URL
            const q = new URLSearchParams(window.location.search).get('q');
            if (q) {
                const inp = document.getElementById('globalSearchInput');
                if (inp) inp.value = q;
                performSearch(q);
            }
        }

        function renderCategoryRow(category) {
            container.insertAdjacentHTML('beforeend', `
                <div class="mb-4 category-row-container relative group/row">
                    <h3 class="text-base font-black text-white tracking-wide mb-3">${category.title}</h3>
                    
                    <div class="relative">
                        <!-- Šipka vlevo -->
                        <button onclick="scrollRow('row-${category.id}', -1)" id="arrow-left-row-${category.id}" 
                                class="scroll-arrow absolute -left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-zinc-900/90 border border-zinc-800 text-white rounded-full flex items-center justify-center hover:bg-zinc-800 hover:scale-110 shadow-2xl disabled:opacity-0 transition-all">
                            <svg class="w-5 h-5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                        </button>

                        <div id="row-${category.id}" 
                             class="flex space-x-3 overflow-x-auto scrollbar-hide pb-3 pt-1 snap-x snap-mandatory scroll-smooth"
                             onscroll="updateArrowStates('row-${category.id}')">
                            ${Array(8).fill('<div class="flex-shrink-0 w-36 h-48 bg-zinc-800/50 rounded-lg animate-pulse"></div>').join('')}
                        </div>

                        <!-- Šipka vpravo -->
                        <button onclick="scrollRow('row-${category.id}', 1)" id="arrow-right-row-${category.id}" 
                                class="scroll-arrow absolute -right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-zinc-900/90 border border-zinc-800 text-white rounded-full flex items-center justify-center hover:bg-zinc-800 hover:scale-110 shadow-2xl transition-all">
                            <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            `);
            loadCategory(category);
            // Inicializace stavu šipek po renderu (počkejte chvíli na vykreslení)
            setTimeout(() => updateArrowStates(`row-${category.id}`), 100);
        }

        // --- Logika posunu ---
        window.scrollRow = (rowId, direction) => {
            const row = document.getElementById(rowId);
            if (!row) return;
            const scrollAmount = row.clientWidth * 0.8;
            row.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
        };

        window.updateArrowStates = (rowId) => {
            const row = document.getElementById(rowId);
            const leftBtn = document.getElementById(`arrow-left-${rowId}`);
            const rightBtn = document.getElementById(`arrow-right-${rowId}`);
            if (!row || !leftBtn || !rightBtn) return;

            // Vlevo: pokud jsme na nule
            leftBtn.disabled = row.scrollLeft <= 5;
            
            // Vpravo: pokud jsme na konci (šířka obsahu - šířka kontejneru)
            const isAtEnd = row.scrollLeft + row.clientWidth >= row.scrollWidth - 5;
            rightBtn.disabled = isAtEnd;
        };

        function loadCategory(category) {
            let fetchUrl = category.query === 'local'
                ? `${BASE_URL_LOCAL}/index.php?url=song/getLocalSongs`
                : `${BASE_URL_LOCAL}/index.php?url=song/spotifySearch&q=${encodeURIComponent(category.query)}`;

            fetch(fetchUrl)
                .then(res => res.json())
                .then(data => {
                    const row = document.getElementById(`row-${category.id}`);
                    if (!row) return;
                    row.innerHTML = '';
                    const songs = data;

                    if (songs.length === 0 && category.query === 'local') {
                        row.innerHTML = '<p class="text-zinc-600 italic pl-2">Zatím jste nic nenahráli.</p>';
                        return;
                    }

                    const mapped = songs.map(s => mapSongData(s, category.query === 'local'));
                    mapped.forEach(song => row.appendChild(createSongCard(song, mapped)));
                });
        }

        function mapSongData(song, isLocal) {
            if (isLocal) {
                const imgs = song.images ? (JSON.parse(song.images)[0] || '') : '';
                return {
                    title: song.title,
                    artist: song.artist,
                    album: song.album || '',
                    image: imgs ? `${BASE_URL_LOCAL}/uploads/${imgs}` : 'https://via.placeholder.com/400?text=No+Cover',
                    previewUrl: song.audio_file ? `${BASE_URL_LOCAL}/public/audio/${song.audio_file}` : song.link,
                    isFull: !!song.audio_file,
                    isLocal: true
                };
            }
            // Pokud už data přišla ze SpotifyService, jsou víceméně připravená
            return {
                title: song.title,
                artist: song.artist,
                artistId: song.artistId || '',
                album: song.album || '',
                albumId: song.albumId || '',
                image: song.image,
                previewUrl: song.previewUrl,
                isFull: false,
                isLocal: false,
                spotifyId: song.spotifyId
            };
        }

        function createSongCard(song, list) {
            const card = template.content.cloneNode(true);
            const cardDiv = card.querySelector('div');

            card.querySelector('.song-title').textContent = song.title;
            card.querySelector('.song-artist').textContent = song.artist;
            
            if (song.albumId) {
                card.querySelector('.song-album-link').href = `${BASE_URL_LOCAL}/index.php?url=album/show/${song.albumId}`;
            }
            if (song.artistId) {
                card.querySelector('.song-artist-link').href = `${BASE_URL_LOCAL}/index.php?url=artist/show/${song.artistId}`;
            }

            const img = card.querySelector('.song-cover');
            img.src = song.image;
            img.loading = 'lazy';

            if (song.isFull) {
                const badge = document.createElement('div');
                badge.className = 'absolute top-2 left-2 bg-blue-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-lg z-10 uppercase';
                badge.textContent = 'Full';
                cardDiv.querySelector('.relative').appendChild(badge);
            }

            card.querySelector('.play-btn').onclick = () => {
                if (window.MusicPlayer) {
                    savePlayedSong(song);
                    refreshActivityRow(song.artist);
                    window.MusicPlayer.playSong(song, list);
                }
            };
            card.querySelector('.add-btn').onclick = e => { e.stopPropagation(); window.showPlaylistToast && window.showPlaylistToast(song); };

            return card;
        }

        function refreshActivityRow(query) {
            const existingRow = document.getElementById('row-activity');
            if (existingRow) {
                existingRow.innerHTML = Array(8).fill('<div class="flex-shrink-0 w-36 h-48 bg-zinc-800/50 rounded-lg animate-pulse"></div>').join('');
                loadCategory({ id: 'activity', query });
            }
            else if (container && !container.querySelector('#row-activity')) {
                const activityCat = { id: 'activity', title: 'Na základě vaší aktivity', query };
                container.insertAdjacentHTML('afterbegin', `
                    <div class="mb-4">
                        <h3 class="text-base font-black text-white tracking-wide mb-3">Na základě vaší aktivity</h3>
                        <div id="row-activity" class="flex space-x-3 overflow-x-auto scrollbar-hide pb-3 pt-1 snap-x snap-mandatory scroll-smooth">
                            ${Array(8).fill('<div class="flex-shrink-0 w-36 h-48 bg-zinc-800/50 rounded-lg animate-pulse"></div>').join('')}
                        </div>
                    </div>
                `);
                loadCategory(activityCat);
            }
        }

        function performSearch(query) {
            container.classList.add('hidden');
            searchContainer.classList.remove('hidden');
            searchContainer.innerHTML = `<h3 class="text-2xl font-black text-white mb-8">Výsledky pro "${query}"</h3><div id="row-search" class="flex flex-wrap gap-8"></div>`;

            fetch(`${BASE_URL_LOCAL}/index.php?url=song/spotifySearch&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    const row = document.getElementById('row-search');
                    const songs = data;
                    const mapped = songs.map(s => mapSongData(s, false));
                    mapped.forEach(song => row.appendChild(createSongCard(song, mapped)));
                });
        }

        // ── Playlist Toast + Modal ────────────────────────────
        let _currentModalSong = null;
        let _toastTimer       = null;
        const BASE = '<?= BASE_URL ?>';

        // Okamžité uložení do Oblíbených + zobrazení toastu
        window.showPlaylistToast = async (song) => {
            _currentModalSong = song;

            // Načteme playlisty a uložíme do prvního (Oblíbené) pokud tam ještě není
            try {
                const res  = await fetch(`${BASE}/index.php?url=song/getPlaylistsForSong&title=${encodeURIComponent(song.title)}&artist=${encodeURIComponent(song.artist)}`);
                const data = await res.json();

                if (data.error) {
                    showToastMsg('Přihlaste se pro ukládání do playlistu');
                    return;
                }

                // Automaticky uloží do výchozího playlistu (Oblíbené)
                const def = data.find(p => p.is_default);
                const defaultName = def ? def.name : 'Oblíbené';

                if (def && !def.has_song) {
                    await fetch(`${BASE}/index.php?url=song/syncSongPlaylists`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            title:      song.title,
                            artist:     song.artist,
                            album:      song.album,
                            image:      song.image,
                            previewUrl: song.previewUrl,
                            albumId:    song.albumId || '',
                            artistId:   song.artistId || '',
                            playlistIds: data.filter(p => p.has_song || p.is_default).map(p => p.id)
                        })
                    });
                }

                showToastMsg(defaultName);
            } catch(e) {
                showToastMsg('Oblíbené');
            }
        };

        function showToastMsg(playlistName) {
            const toast = document.getElementById('playlist-toast');
            document.getElementById('toast-playlist-name').textContent = playlistName;
            toast.classList.add('visible');
            clearTimeout(_toastTimer);
            _toastTimer = setTimeout(() => toast.classList.remove('visible'), 5000);
        }

        // Tlačítko Změnit → otevře modal s checkboxy
        document.getElementById('toast-change-btn').onclick = () => openPlaylistModal();

        async function openPlaylistModal() {
            if (!_currentModalSong) return;
            const modal    = document.getElementById('playlist-select-modal');
            const listEl   = document.getElementById('playlist-checkboxes');
            const nameEl   = document.getElementById('modal-song-name');
            const statusEl = document.getElementById('modal-status');

            nameEl.textContent = `${_currentModalSong.artist} — ${_currentModalSong.title}`;
            listEl.innerHTML   = '<div class="p-4 text-zinc-500 text-sm">Načítám...</div>';
            statusEl.textContent = '';
            modal.classList.remove('hidden');

            try {
                const res  = await fetch(`${BASE}/index.php?url=song/getPlaylistsForSong&title=${encodeURIComponent(_currentModalSong.title)}&artist=${encodeURIComponent(_currentModalSong.artist)}`);
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

        // Hotovo → odeslat výběr
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
                        albumId:    _currentModalSong.albumId || '',
                        artistId:   _currentModalSong.artistId || '',
                        playlistIds: selected
                    })
                });
                const data = await res.json();

                document.getElementById('playlist-select-modal').classList.add('hidden');
                document.getElementById('playlist-toast').classList.remove('visible');
                showToastMsg(data.message || 'Uloženo');

            } catch(e) {
                statusEl.textContent = 'Chyba.';
            }
        };

        function escHtml(s) {
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // Poslouchame eventy ze search baru v headeru
        window._homeSearchHandler = e => performSearch(e.detail.query);
        window._homeSearchClearHandler = () => initPage();
        window._homePageChangeHandler = () => {
            window.removeEventListener('search', window._homeSearchHandler);
            window.removeEventListener('searchclear', window._homeSearchClearHandler);
            window.removeEventListener('pagechange', window._homePageChangeHandler);
        };

        window.addEventListener('search', window._homeSearchHandler);
        window.addEventListener('searchclear', window._homeSearchClearHandler);
        window.addEventListener('pagechange', window._homePageChangeHandler);

        initPage();
    })();
    </script>

</main>

<?php require_once '../app/views/layout/footer.php'; ?>
