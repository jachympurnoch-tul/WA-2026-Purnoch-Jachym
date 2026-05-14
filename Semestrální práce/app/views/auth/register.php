<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-1 flex items-center justify-center p-6 bg-black relative overflow-hidden">
    <!-- Dekorativní pozadí -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px] translate-y-1/2"></div>

    <div class="w-full max-w-lg relative">
        <div class="bg-zinc-900/40 backdrop-blur-2xl border border-zinc-800 p-8 md:p-10 rounded-[40px] shadow-2xl">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-2xl shadow-lg shadow-blue-500/20 mb-6 -rotate-3">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"></path></svg>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight mb-2">Vytvořit účet</h2>
                <p class="text-zinc-500 text-sm font-medium">Začněte objevovat neomezenou hudbu</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Uživatelské jméno -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Uživatelské jméno *</label>
                    <input type="text" name="username" required placeholder="např. hudebnik123"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Emailová adresa *</label>
                    <input type="email" name="email" required placeholder="jmeno@priklad.cz"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <!-- Jméno -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Křestní jméno</label>
                    <input type="text" name="first_name" placeholder="Jan"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <!-- Příjmení -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Příjmení</label>
                    <input type="text" name="last_name" placeholder="Novák"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <!-- Nickname -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Přezdívka (Nickname)</label>
                    <input type="text" name="nickname" placeholder="např. DJ Jindra"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <!-- Heslo -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Heslo *</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <!-- Potvrzení hesla -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Potvrzení hesla *</label>
                    <input type="password" name="password_confirm" required placeholder="••••••••"
                           class="w-full px-6 py-3.5 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                </div>

                <div class="md:col-span-2 mt-4">
                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-400 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-blue-500/20 active:scale-[0.98] flex items-center justify-center gap-2 group">
                        <span>Zaregistrovat se</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-zinc-800/50 pt-8">
                <p class="text-zinc-500 text-xs font-medium">Už máte účet?</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="inline-block mt-2 text-white font-black hover:text-blue-400 transition-colors text-sm" data-nav>Přihlaste se zde</a>
            </div>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
