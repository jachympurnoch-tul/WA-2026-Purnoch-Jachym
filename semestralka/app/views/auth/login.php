<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-slate-900 rounded-3xl p-10 border border-slate-800 shadow-2xl">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                    Vítejte <span class="text-indigo-500">Zpět</span>
                </h2>
                <p class="text-slate-500 mt-2 font-medium italic">Přihlaste se ke svému GameHub účtu.</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="POST" class="space-y-6">
                <div>
                    <label for="login_id" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Email nebo Jméno</label>
                    <input type="text" id="login_id" name="login_id" required 
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-600">
                </div>

                <div>
                    <label for="password" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Heslo</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-600">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest py-5 rounded-2xl transition-all shadow-xl hover:shadow-[0_10px_40px_rgba(79,70,229,0.4)]">
                        PŘIHLÁSIT SE
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-800 text-center">
                <p class="text-slate-500 text-sm font-medium">Nemáte ještě účet?</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="text-indigo-400 hover:text-white font-bold transition-colors">Vytvořit nový účet</a>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="<?= BASE_URL ?>/index.php" class="text-slate-600 hover:text-slate-400 font-bold uppercase tracking-widest text-[10px] transition-colors">
                Zpět na katalog her
            </a>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>