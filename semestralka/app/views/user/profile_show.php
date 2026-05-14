<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-slate-900 rounded-[3rem] p-8 md:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 mb-10">
                <div class="w-32 h-32 rounded-full bg-indigo-600/20 border-2 border-indigo-500/50 flex items-center justify-center text-indigo-400 font-black text-5xl flex-shrink-0 shadow-[0_0_30px_rgba(79,70,229,0.2)]">
                    <?= strtoupper(substr($user['nickname'] ?: $user['username'], 0, 1)) ?>
                </div>
                
                <div class="text-center md:text-left flex-grow">
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-none uppercase italic tracking-tighter mb-2">
                        <?= htmlspecialchars($user['nickname'] ?: $user['username']) ?>
                    </h2>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                        Členem od <?= date('d. m. Y', strtotime($user['created_at'])) ?>
                    </p>
                    <?php if ($user['username'] === 'admin'): ?>
                        <span class="inline-block mt-2 px-3 py-1 bg-amber-600/20 text-amber-500 border border-amber-500/50 rounded-lg text-[9px] font-black uppercase tracking-widest">
                            Administrátor
                        </span>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $user['id'] || !empty($_SESSION['is_admin']))): ?>
                    <div class="flex-shrink-0">
                        <a href="<?= BASE_URL ?>/index.php?url=user/edit/<?= $user['id'] ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all shadow-lg hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Upravit profil
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-slate-800/40 rounded-2xl p-6 md:p-8 border border-white/5 mb-8">
                <h3 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-6 flex items-center">
                    <span class="w-8 h-[2px] bg-indigo-500 mr-3"></span>
                    Osobní údaje
                </h3>
                
                <?php $isOwnerOrAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $user['id'] || !empty($_SESSION['is_admin'])); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Uživatelské jméno</p>
                        <p class="text-lg <?= $isOwnerOrAdmin ? 'text-white' : 'text-slate-500 italic' ?> font-bold">
                            <?= $isOwnerOrAdmin ? htmlspecialchars($user['username']) : 'Skryto pro veřejnost' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">E-mail</p>
                        <p class="text-lg <?= $isOwnerOrAdmin ? 'text-white' : 'text-slate-500 italic' ?> font-bold">
                            <?= $isOwnerOrAdmin ? htmlspecialchars($user['email']) : 'Skryto pro veřejnost' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Jméno</p>
                        <p class="text-lg <?= $isOwnerOrAdmin ? 'text-white' : 'text-slate-500 italic' ?> font-bold">
                            <?= $isOwnerOrAdmin ? htmlspecialchars($user['first_name'] ?: '-') : 'Skryto pro veřejnost' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Příjmení</p>
                        <p class="text-lg <?= $isOwnerOrAdmin ? 'text-white' : 'text-slate-500 italic' ?> font-bold">
                            <?= $isOwnerOrAdmin ? htmlspecialchars($user['last_name'] ?: '-') : 'Skryto pro veřejnost' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/40 rounded-2xl p-6 md:p-8 border border-white/5 mb-8">
                <h3 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-6 flex items-center">
                    <span class="w-8 h-[2px] bg-indigo-500 mr-3"></span>
                    Statistiky
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50 text-center">
                        <div class="text-3xl font-black text-indigo-400 mb-1"><?= $gamesCount ?? 0 ?></div>
                        <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Přidané hry</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-4 border border-slate-700/50 text-center">
                        <div class="text-3xl font-black text-amber-500 mb-1"><?= $commentsCount ?? 0 ?></div>
                        <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Komentáře</div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/40 rounded-2xl p-6 md:p-8 border border-white/5">
                <h3 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-6 flex items-center">
                    <span class="w-8 h-[2px] bg-indigo-500 mr-3"></span>
                    Aktivita uživatele
                </h3>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left: Games -->
                        <div>
                            <h4 class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-4">Přidané hry</h4>
                            <?php if (!empty($userGames)): ?>
                                <ul class="space-y-3">
                                    <?php foreach ($userGames as $g): ?>
                                        <li>
                                            <a href="<?= BASE_URL ?>/index.php?url=game/show/<?= $g['id'] ?>" class="block p-3 bg-slate-900/50 hover:bg-slate-800 rounded-xl border border-slate-700/50 transition-colors group">
                                                <div class="text-white font-bold text-sm group-hover:text-indigo-400 transition-colors"><?= htmlspecialchars($g['title']) ?></div>
                                                <div class="text-slate-500 text-[10px] uppercase tracking-widest mt-1"><?= date('d.m.Y', strtotime($g['created_at'])) ?></div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-slate-500 italic text-sm">Zatím nebyly přidány žádné hry.</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Right: Comments -->
                        <div>
                            <h4 class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-4">Poslední komentáře</h4>
                            <?php if (!empty($userComments)): ?>
                                <ul class="space-y-3">
                                    <?php foreach ($userComments as $c): ?>
                                        <li class="p-4 bg-slate-900/50 rounded-xl border border-slate-700/50">
                                            <a href="<?= BASE_URL ?>/index.php?url=game/show/<?= $c['game_id'] ?>" class="text-indigo-400 hover:text-indigo-300 font-bold text-sm mb-2 block transition-colors">
                                                <?= htmlspecialchars($c['game_title']) ?>
                                            </a>
                                            <div class="flex text-amber-500 text-[10px] mb-2">
                                                <?php for($i=1; $i<=5; $i++) echo $i <= $c['rating'] ? '★' : '<span class="text-slate-700">★</span>'; ?>
                                            </div>
                                            <p class="text-slate-300 text-sm italic leading-relaxed line-clamp-3">"<?= htmlspecialchars($c['content']) ?>"</p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-slate-500 italic text-sm">Zatím nebyly přidány žádné komentáře.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-slate-900/80 rounded-2xl p-8 text-center border border-slate-800 backdrop-blur-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-800 rounded-full mb-4 text-slate-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h4 class="text-white font-bold mb-2">Skrytá sekce</h4>
                        <p class="text-slate-500 text-sm mb-6 max-w-sm mx-auto">Pro zobrazení detailní aktivity uživatele (seznam přidaných her a přesné znění komentářů) se musíte přihlásit.</p>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all shadow-lg hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                            Přihlásit se
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>

        <div class="mt-8 text-center">
            <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center text-slate-500 hover:text-indigo-400 font-bold transition-colors uppercase tracking-widest text-xs">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Zpět na katalog her
            </a>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
