<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-slate-900 rounded-[3rem] p-8 md:p-12 shadow-2xl border border-slate-800">
            <h2 class="text-3xl md:text-4xl font-black text-white leading-none uppercase italic tracking-tighter mb-8 text-center">
                Úprava profilu
            </h2>

            <form action="<?= BASE_URL ?>/index.php?url=user/update/<?= $user['id'] ?>" method="POST" class="space-y-6">
                
                <div class="bg-slate-800/40 rounded-2xl p-6 border border-white/5 space-y-6">
                    <div>
                        <label for="first_name" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Jméno</label>
                        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" 
                               class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-all"
                               placeholder="Vaše jméno">
                    </div>

                    <div>
                        <label for="last_name" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Příjmení</label>
                        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" 
                               class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-all"
                               placeholder="Vaše příjmení">
                    </div>

                    <div>
                        <label for="nickname" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Přezdívka (zobrazovaná)</label>
                        <input type="text" id="nickname" name="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>" 
                               class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-all"
                               placeholder="Vaše přezdívka">
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $user['id'] ?>" class="flex-1 text-center py-4 bg-slate-800 hover:bg-slate-700 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all border border-slate-700">
                        Zrušit
                    </a>
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-[10px] py-4 rounded-xl transition-all shadow-lg hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                        Uložit změny
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-12 border-t border-slate-800">
                <h3 class="text-2xl font-black text-white leading-none uppercase italic tracking-tighter mb-8 text-center">
                    Změna hesla
                </h3>
                
                <form action="<?= BASE_URL ?>/index.php?url=user/updatePassword/<?= $user['id'] ?>" method="POST" class="space-y-6">
                    <div class="bg-slate-800/40 rounded-2xl p-6 border border-white/5 space-y-6">
                        <div>
                            <label for="current_password" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Současné heslo</label>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-4 transition-all">
                        </div>

                        <div>
                            <label for="new_password" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nové heslo</label>
                            <input type="password" id="new_password" name="new_password" required
                                   class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-4 transition-all">
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Potvrzení nového hesla</label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-4 transition-all">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-black uppercase tracking-widest text-[10px] py-4 rounded-xl transition-all shadow-lg hover:shadow-[0_0_20px_rgba(217,119,6,0.4)]">
                        Změnit heslo
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
