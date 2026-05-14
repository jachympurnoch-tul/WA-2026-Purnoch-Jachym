<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-1 flex items-center justify-center p-6 bg-black relative overflow-hidden">
    <!-- Dekorativní pozadí -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px] -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] translate-y-1/2"></div>

    <div class="w-full max-w-md relative">
        <div class="bg-zinc-900/40 backdrop-blur-2xl border border-zinc-800 p-10 rounded-[40px] shadow-2xl">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-2xl shadow-lg shadow-blue-500/20 mb-6 rotate-3">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"></path></svg>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight mb-2">Vítejte zpět!</h2>
                <p class="text-zinc-500 text-sm font-medium">Přihlaste se ke svému hudebnímu světu</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="POST" class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500 ml-4">Email nebo Jméno</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-zinc-600 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="login_id" required placeholder="Uživatelské jméno / email"
                               class="w-full pl-12 pr-6 py-4 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-4">
                        <label class="text-[11px] font-black uppercase tracking-widest text-zinc-500">Heslo</label>
                        <a href="#" class="text-[10px] font-bold text-blue-500 hover:text-blue-400">Zapomenuté heslo?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-zinc-600 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full pl-12 pr-6 py-4 bg-zinc-800/50 border border-zinc-800 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all placeholder-zinc-700 text-sm">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-400 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-blue-500/20 active:scale-[0.98] mt-4 flex items-center justify-center gap-2 group">
                    <span>Přihlásit se</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-zinc-500 text-xs font-medium">Nemáte ještě účet?</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="inline-block mt-2 text-white font-black hover:text-blue-400 transition-colors text-sm" data-nav>Vytvořte si ho zdarma</a>
            </div>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
