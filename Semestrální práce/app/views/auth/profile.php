<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-1 p-6 md:p-12 bg-black relative overflow-hidden">
    <!-- Dekorativní prvky -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/2"></div>

    <div class="container mx-auto max-w-5xl relative">
        <header class="mb-12">
            <h2 class="text-5xl font-black text-white tracking-tight mb-4">Tvůj Profil</h2>
            <div class="h-1 w-20 bg-blue-500 rounded-full"></div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Levý sloupec: Karta uživatele -->
            <div class="lg:col-span-1">
                <div class="bg-zinc-900/50 backdrop-blur-xl border border-zinc-800 rounded-[40px] p-8 text-center sticky top-32">
                    <div class="relative inline-block mb-6">
                        <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-5xl font-black shadow-2xl">
                            <?= strtoupper(substr(htmlspecialchars($user['username']), 0, 1)) ?>
                        </div>
                        <div class="absolute bottom-1 right-1 w-8 h-8 bg-green-500 border-4 border-zinc-900 rounded-full"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($user['nickname'] ?: $user['username']) ?></h3>
                    <p class="text-zinc-500 text-sm mb-6">@<?= htmlspecialchars($user['username']) ?></p>
                    
                    <div class="flex flex-col gap-3">
                        <button class="w-full py-3 bg-zinc-800 hover:bg-zinc-700 text-white rounded-2xl font-bold transition-all text-sm">Upravit profil</button>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="w-full py-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-2xl font-bold transition-all text-sm">Odhlásit se</a>
                    </div>
                </div>
            </div>

            <!-- Pravý sloupec: Detaily -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-zinc-900/30 backdrop-blur-md border border-zinc-800/50 rounded-[40px] overflow-hidden">
                    <div class="px-8 py-6 border-b border-zinc-800/50 flex items-center justify-between">
                        <h4 class="text-lg font-bold text-white">Osobní údaje</h4>
                        <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Celé jméno</span>
                            <p class="text-white font-medium italic"><?= htmlspecialchars(($user['first_name'] . ' ' . $user['last_name']) ?: 'Neuvedeno') ?></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Emailová adresa</span>
                            <p class="text-white font-medium"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Uživatelské jméno</span>
                            <p class="text-white font-medium"><?= htmlspecialchars($user['username']) ?></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Členem od</span>
                            <p class="text-white font-medium"><?= date('d. m. Y', strtotime($user['created_at'])) ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-zinc-900/30 backdrop-blur-md border border-zinc-800/50 rounded-[40px] p-8 flex items-center gap-6 group hover:border-blue-500/50 transition-colors cursor-pointer">
                    <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-lg">Tvoje Playlisty</h4>
                        <p class="text-zinc-500 text-sm">Spravuj své oblíbené skladby a kolekce</p>
                    </div>
                    <a href="<?= BASE_URL ?>/index.php?url=song/playlist" class="ml-auto w-10 h-10 bg-zinc-800 rounded-full flex items-center justify-center text-zinc-400 hover:text-white transition-colors" data-nav>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
