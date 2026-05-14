<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                    Vítejte <span class="text-indigo-500">Zpět</span>
                </h2>
                <p class="text-slate-500 mt-2 font-medium italic">Přihlaste se ke svému GameHub účtu.</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="POST" class="space-y-5">
                <div>
                    <label for="login_id" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Email nebo Jméno</label>
                    <input type="text" id="login_id" name="login_id" required 
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-3.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-600">
                </div>

                <div>
                    <label for="password" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Heslo</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-3.5 pr-12 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-600">
                        <button type="button" onclick="togglePassword('password', 'eye-login')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-indigo-400 transition-colors focus:outline-none"
                                title="Zobrazit/skrýt heslo">
                            <svg id="eye-login" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest py-4 rounded-2xl transition-all shadow-xl hover:shadow-[0_10px_40px_rgba(79,70,229,0.4)]">
                        PŘIHLÁSIT SE
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-800 text-center">
                <p class="text-slate-500 text-sm font-medium">Nemáte ještě účet?</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="text-indigo-400 hover:text-white font-bold transition-colors">Vytvořit nový účet</a>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <a href="<?= BASE_URL ?>/index.php" class="text-slate-600 hover:text-slate-400 font-bold uppercase tracking-widest text-[10px] transition-colors">
                Zpět na katalog her
            </a>
        </div>
    </div>
</main>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    // Switch between eye and eye-off icons
    icon.innerHTML = isPassword
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}
</script>

<?php require_once '../app/views/layout/footer.php'; ?>