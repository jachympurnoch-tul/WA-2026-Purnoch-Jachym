<!DOCTYPE html>
<html lang="cs" class="h-full bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Písničky - Výuková aplikace</title>
</head>
<body class="bg-gradient-to-b from-zinc-700 to-zinc-900 text-zinc-300 min-h-screen font-sans flex flex-col">

    <header class="bg-black border-b border-zinc-800 shadow-xl sticky top-0 z-[100] backdrop-blur-md bg-black/80">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center group select-none shrink-0" data-nav>
                <!-- Music SVG Logo -->
                <div class="relative min-w-[48px] h-12 flex items-center justify-center bg-zinc-900 rounded-full group-hover:bg-blue-500 transition-all duration-300 shadow-[0_0_20px_rgba(59,130,246,0.1)] group-hover:shadow-[0_0_20px_rgba(59,130,246,0.4)] mr-4">
                    <svg class="w-6 h-6 text-blue-500 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"></path>
                    </svg>
                </div>
                <!-- Nadpis -->
                <h1 class="flex flex-col justify-center">
                    <span class="text-2xl font-black tracking-tight text-white group-hover:text-blue-400 transition-colors">
                        MusicApp<span class="text-blue-500">.</span>
                    </span>
                </h1>
            </a>

            <!-- GLOBÁLNÍ VYHLEDÁVÁNÍ -->
            <div class="w-full max-w-xl relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-zinc-500 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="globalSearchInput" placeholder="Hledat interprety, skladby..." 
                       class="w-full pl-12 pr-4 py-2.5 bg-zinc-900/50 border border-zinc-800 rounded-full text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-500 text-sm">
                
                <!-- Indikátor načítání searchu -->
                <div id="searchLoader" class="absolute right-4 inset-y-0 flex items-center hidden">
                    <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
            
             <nav class="shrink-0">
                <ul class="flex items-center space-x-6">
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=song/playlist" class="text-zinc-400 hover:text-white transition-colors font-medium text-sm" data-nav>Playlisty</a>
                    </li>

                    <li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=song/create" class="bg-blue-500 hover:bg-blue-400 text-white px-5 py-2 rounded-full font-bold transition-transform hover:scale-105 active:scale-95 text-xs" data-nav title="Nahrát vlastní MP3 skladbu">
                                Nahrát
                            </a>
                        <?php else: ?>
                            <div class="relative group">
                                <div class="bg-blue-500/30 text-white/50 px-5 py-2 rounded-full font-bold text-xs cursor-not-allowed select-none">
                                    Nahrát
                                </div>
                                <div class="absolute top-full mt-3 right-0 px-4 py-2 bg-zinc-800 text-white text-xs rounded shadow-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none z-50">
                                    <span class="font-medium">Musíte se přihlásit</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="relative group">
                            <div class="flex items-center space-x-2 cursor-pointer py-2">
                                <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center text-blue-500 font-bold text-xs">
                                    <?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? 'U'), 0, 1)) ?>
                                </div>
                                <span class="text-white font-medium hover:text-blue-400 transition-colors text-sm hidden lg:inline">
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </span>
                            </div>

                            <div class="absolute right-0 top-full w-48 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="bg-zinc-800 border border-zinc-700 rounded-xl shadow-2xl overflow-hidden p-1">
                                    <a href="<?= BASE_URL ?>/index.php?url=auth/profile" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-700 hover:text-white rounded-lg transition-colors" data-nav>
                                        Profil
                                    </a>
                                    <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-700 hover:text-white rounded-lg transition-colors">
                                        Odhlásit
                                    </a>
                                </div>
                            </div>
                        </li>

                    <?php else: ?>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-zinc-400 hover:text-white transition-colors font-medium text-sm" data-nav>Přihlásit</a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="bg-white text-black px-5 py-2 rounded-full font-bold transition-transform hover:scale-105 active:scale-95 text-xs" data-nav>
                                Registrovat
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- SPA LOADING BAR -->
    <div id="spa-loader" class="fixed top-0 left-0 w-0 h-1 bg-blue-500 z-[1000] transition-all duration-300 opacity-0"></div>

    <div id="alert-container" class="container mx-auto px-6 pt-4">
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="space-y-3 mb-6">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php 
                        $styles = [
                            'success' => 'bg-green-500/20 text-green-400 border border-green-500/20',
                            'error'   => 'bg-red-500/20 text-red-400 border border-red-500/20',
                            'notice'  => 'bg-blue-500/20 text-blue-400 border border-blue-500/20',
                        ];
                        $style = $styles[$type] ?? 'bg-zinc-800 text-zinc-300';
                    ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="<?= $style ?> p-3 rounded-xl shadow-md text-sm font-medium animate-slide-down">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']); ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // SPA NAVIGACE LOGIKA
    const BASE_URL = '<?= BASE_URL ?>';
    
    document.addEventListener('click', e => {
        const link = e.target.closest('a[data-nav]');
        if (link && link.href.startsWith(window.location.origin)) {
            e.preventDefault();
            // Pokud jdeme domů, vyčistíme vyhledávání
            const url = new URL(link.href);
            if (!url.searchParams.has('url')) {
                const input = document.getElementById('globalSearchInput');
                if (input) input.value = '';
                window.dispatchEvent(new CustomEvent('searchclear'));
            }
            navigateTo(link.href);
        }
    });

    document.addEventListener('submit', e => {
        const form = e.target.closest('form[data-nav]');
        if (form && form.action.startsWith(window.location.origin)) {
            e.preventDefault();
            const url = new URL(form.action);
            const formData = new FormData(form);
            
            // Pro jednoduchost teď řešíme hlavně POST přes AJAX
            const options = {
                method: form.method.toUpperCase(),
                body: form.method.toUpperCase() === 'POST' ? formData : null
            };
            
            if (form.method.toUpperCase() === 'GET') {
                const params = new URLSearchParams(formData);
                url.search = params.toString();
                options.body = null;
            }
            
            loadPage(url.toString(), true, options);
        }
    });

    window.addEventListener('popstate', () => {
        loadPage(window.location.href, false);
    });

    async function navigateTo(url) {
        if (url === window.location.href) return;
        await loadPage(url, true);
    }

    async function loadPage(url, push = true, options = {}) {
        const loader = document.getElementById('spa-loader');
        loader.style.width = '30%';
        loader.style.opacity = '1';

        try {
            const response = await fetch(url, options);
            loader.style.width = '70%';
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Výměna obsahu <main>
            const newMain = doc.querySelector('main');
            const currentMain = document.querySelector('main');
            
            if (newMain && currentMain) {
                currentMain.innerHTML = newMain.innerHTML;
                // Přenést i třídy z main (např. padding)
                currentMain.className = newMain.className;
            }

            // Update titulku
            document.title = doc.title;

            // Update URL (použijeme response.url pro případ redirectu na serveru)
            if (push) history.pushState({}, '', response.url);

            // Re-spuštění skriptů z nové stránky
            const scripts = doc.querySelectorAll('main + script, main script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                document.body.appendChild(newScript);
                newScript.remove(); // Vyčistit po spuštění
            });

            // Vyvolat event pro re-inicializaci
            window.dispatchEvent(new CustomEvent('pagechange'));
            
            window.scrollTo(0, 0);
            loader.style.width = '100%';
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.width = '0', 300);
            }, 200);

        } catch (error) {
            console.error('SPA load failed:', error);
            window.location.href = url; // Fallback na klasické načtení
        }
    }

    // Globální vyhledávání event
    let searchDebounce;
    document.getElementById('globalSearchInput').addEventListener('input', e => {
        const query = e.target.value.trim();
        clearTimeout(searchDebounce);
        
        searchDebounce = setTimeout(() => {
            if (query.length >= 1) {
                const onSearchPage = window.location.search.includes('q=');
                const onMainPage = window.location.href.includes('index.php') && !window.location.search.includes('url=');

                if (onSearchPage || onMainPage) {
                    // Jsme na hlavní stránce nebo stránce s výsledky – vyhledáme lokálně
                    // a aktualizujeme URL bez znovunačtení stránky
                    history.replaceState({}, '', `${BASE_URL}/index.php?q=${encodeURIComponent(query)}`);
                    window.dispatchEvent(new CustomEvent('search', { detail: { query } }));
                } else {
                    // Jsme na jiné stránce – přejdeme na hlavní
                    navigateTo(`${BASE_URL}/index.php?q=${encodeURIComponent(query)}`);
                }
            } else if (query.length === 0) {
                history.replaceState({}, '', `${BASE_URL}/index.php`);
                window.dispatchEvent(new CustomEvent('searchclear'));
            }
        }, 300);
    });
    </script>

    <style>
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-down {
        animation: slideDown 0.3s ease-out forwards;
    }
    </style>