<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Main Card -->
        <div class="bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl border border-slate-800">
            
            <!-- Hero Banner Section -->
            <div class="relative h-[500px] w-full bg-slate-800">
                <?php 
                    $images = json_decode($game['images'], true);
                    $firstImage = (!empty($images) && is_array($images)) ? $images[0] : null;
                ?>
                <?php if ($firstImage): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($firstImage) ?>" 
                         alt="<?= htmlspecialchars($game['title']) ?>" 
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-slate-700">
                        <svg class="w-24 h-24 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                <?php endif; ?>
                
                <!-- Hero Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                
                <!-- Hero Content (Floating) -->
                <div class="absolute bottom-0 left-0 w-full p-8 lg:p-12">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <?php 
                                    $tagNames = !empty($game['tag_names']) ? explode('||', $game['tag_names']) : ['Bez tagů'];
                                    foreach ($tagNames as $tagName): 
                                ?>
                                    <span class="px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg">
                                        <?= htmlspecialchars($tagName) ?>
                                    </span>
                                <?php endforeach; ?>
                                <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-white/10">
                                    <?= htmlspecialchars($game['subcategory_name'] ?: 'Všechny platformy') ?>
                                </span>
                            </div>
                            <h2 class="text-6xl md:text-7xl font-black text-white leading-none uppercase italic tracking-tighter mb-2 drop-shadow-2xl break-words whitespace-normal">
                                <?= htmlspecialchars($game['title']) ?>
                            </h2>
                            <p class="text-2xl text-indigo-400 font-bold italic opacity-90 truncate">by <?= htmlspecialchars($game['developer']) ?></p>
                        </div>
                        
                        <div class="flex items-center gap-6 flex-shrink-0">
                            <div class="text-right hidden sm:block">
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Cena hry</p>
                                <div class="text-5xl font-black text-white leading-none">
                                    <?php if ($game['price'] > 0): ?>
                                        <?= number_format($game['price'], 0, ',', ' ') ?> <span class="text-xl text-indigo-500">Kč</span>
                                    <?php else: ?>
                                        <span class="text-emerald-500 tracking-tighter">ZDARMA</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($game['link'])): ?>
                                <?php 
                                    $btnClass = $game['price'] > 0 
                                        ? 'bg-indigo-600 hover:bg-indigo-500 shadow-[0_10px_40px_rgba(79,70,229,0.4)]' 
                                        : 'bg-emerald-600 hover:bg-emerald-500 shadow-[0_10px_40px_rgba(16,185,129,0.4)]';
                                    $btnText = $game['price'] > 0 ? 'Koupit hru' : 'Získat hru';
                                ?>
                                <a href="<?= htmlspecialchars($game['link']) ?>" target="_blank" rel="noopener noreferrer" class="px-8 py-4 sm:px-10 sm:py-5 <?= $btnClass ?> text-white font-black uppercase tracking-widest rounded-2xl transition-all transform hover:scale-105 active:scale-95 text-center whitespace-nowrap">
                                    <?= $btnText ?>
                                </a>
                            <?php else: ?>
                                <div class="px-8 py-4 sm:px-10 sm:py-5 bg-slate-800 text-slate-500 font-black uppercase tracking-widest rounded-2xl border border-slate-700 cursor-not-allowed text-center whitespace-nowrap" title="Odkaz není k dispozici">
                                    Nedostupné
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8 lg:p-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Left: Description -->
                <div class="lg:col-span-2">
                    <div class="mb-10">
                        <h4 class="text-white font-black uppercase tracking-[0.3em] text-xs mb-8 flex items-center">
                            <span class="w-12 h-[2px] bg-indigo-500 mr-4"></span>
                            O hře
                        </h4>
                        <div class="text-slate-400 leading-relaxed font-medium text-lg space-y-4">
                            <?= nl2br(htmlspecialchars($game['description'])) ?>
                        </div>
                    </div>
                </div>

                <!-- Right: Meta Info & Actions -->
                <div class="space-y-8">
                    <div class="bg-slate-800/40 rounded-3xl p-8 border border-slate-800">
                        <h4 class="text-white font-black uppercase tracking-widest text-[10px] mb-6 opacity-50">Technické detaily</h4>
                        
                        <div class="space-y-6">
                            <div>
                                <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Rok vydání</p>
                                <p class="text-xl text-white font-bold tracking-tight"><?= $game['release_year'] ?></p>
                            </div>
                            <div class="h-px bg-slate-800"></div>
                            <div>
                                <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Vydavatel</p>
                                <p class="text-xl text-white font-bold tracking-tight"><?= htmlspecialchars($game['publisher']) ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['user_id']) && ($game['created_by'] == $_SESSION['user_id'] || !empty($_SESSION['is_admin']))): ?>
                        <div class="flex flex-col gap-3">
                            <a href="<?= BASE_URL ?>/index.php?url=game/edit/<?= $game['id'] ?>" class="w-full px-6 py-4 bg-amber-600/10 hover:bg-amber-600 text-amber-500 hover:text-white font-black uppercase tracking-widest text-[10px] rounded-2xl transition-all border border-amber-600/30 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Upravit záznam
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>/index.php?url=game/delete/<?= $game['id'] ?>" onsubmit="return confirm('Opravdu chcete tuto hru trvale smazat?')">
                                <button type="submit" class="w-full px-6 py-4 bg-rose-600/10 hover:bg-rose-600 text-rose-500 hover:text-white font-black uppercase tracking-widest text-[10px] rounded-2xl transition-all border border-rose-600/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Odstranit z databáze
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="mt-12">
            <h3 class="text-2xl font-black text-white uppercase italic tracking-tighter mb-8 flex items-center">
                <span class="w-8 h-[2px] bg-indigo-500 mr-4"></span>
                Diskuze ke hře
            </h3>

            <!-- Comment Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="bg-slate-900 rounded-3xl p-6 md:p-8 shadow-xl border border-slate-800 mb-10">
                    <form action="<?= BASE_URL ?>/index.php?url=comment/store/<?= $game['id'] ?>" method="POST">
                        <div class="mb-4 flex flex-col sm:flex-row gap-4">
                            <div class="sm:w-1/4">
                                <label for="rating" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Hodnocení</label>
                                <select id="rating" name="rating" class="w-full bg-slate-950 border border-slate-800 text-amber-500 font-bold text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-4 transition-all appearance-none cursor-pointer">
                                    <option value="5">★★★★★ (5/5)</option>
                                    <option value="4">★★★★☆ (4/5)</option>
                                    <option value="3">★★★☆☆ (3/5)</option>
                                    <option value="2">★★☆☆☆ (2/5)</option>
                                    <option value="1">★☆☆☆☆ (1/5)</option>
                                </select>
                            </div>
                            <div class="sm:w-3/4">
                                <label for="content" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Váš komentář</label>
                                <textarea id="content" name="content" rows="3" required
                                          class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-all"
                                          placeholder="Napište svůj názor na hru..."></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all shadow-lg hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                                Přidat komentář
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-slate-800/30 rounded-2xl p-6 text-center border border-white/5 mb-10">
                    <p class="text-slate-400 text-sm mb-4">Pro zapojení do diskuze se musíte přihlásit.</p>
                    <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="inline-flex items-center px-6 py-2 bg-slate-700 hover:bg-indigo-600 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all">
                        Přihlásit se
                    </a>
                </div>
            <?php endif; ?>

            <!-- Comments List -->
            <div class="space-y-6">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-slate-800/40 rounded-2xl p-6 border border-white/5">
                            <div class="flex gap-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600/20 border border-indigo-500/50 flex items-center justify-center text-indigo-400 font-black text-sm">
                                        <?= strtoupper(substr($comment['nickname'] ?: $comment['username'], 0, 1)) ?>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-grow">
                                    <div class="flex flex-wrap items-center gap-3 mb-1">
                                        <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $comment['user_id'] ?>" class="text-white hover:text-indigo-400 transition-colors font-bold tracking-tight">
                                            <?= htmlspecialchars($comment['nickname'] ?: $comment['username']) ?>
                                        </a>
                                        <span class="flex text-amber-500 text-[10px]">
                                            <?php 
                                                $rating = isset($comment['rating']) ? (int)$comment['rating'] : 5;
                                                for($i=1; $i<=5; $i++) {
                                                    echo $i <= $rating ? '★' : '<span class="text-slate-700">★</span>';
                                                }
                                            ?>
                                        </span>
                                        <span class="text-[9px] uppercase tracking-widest text-slate-500 mt-0.5">
                                            <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                                        </span>
                                    </div>
                                    <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars(trim($comment['content']))) ?></div>
                                </div>

                                <!-- Actions -->
                                <?php if (isset($_SESSION['user_id']) && ($comment['user_id'] == $_SESSION['user_id'] || !empty($_SESSION['is_admin']))): ?>
                                    <div class="flex gap-2 flex-shrink-0 ml-4">
                                        <a href="<?= BASE_URL ?>/index.php?url=comment/edit/<?= $comment['id'] ?>" class="text-slate-500 hover:text-amber-500 transition-colors p-1" title="Upravit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>/index.php?url=comment/delete/<?= $comment['id'] ?>" onsubmit="return confirm('Opravdu chcete smazat tento komentář?')" style="display:inline">
                                            <button type="submit" class="text-slate-500 hover:text-rose-500 transition-colors p-1" title="Smazat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-slate-500 text-center italic py-8">Zatím zde nejsou žádné komentáře. Buďte první!</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-12 text-center">
            <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center text-slate-500 hover:text-indigo-400 font-bold transition-colors uppercase tracking-widest text-xs">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Zpět na katalog
            </a>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
