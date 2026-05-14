<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-slate-900 rounded-[3rem] p-8 md:p-12 shadow-2xl border border-slate-800">
            <h2 class="text-3xl md:text-4xl font-black text-white leading-none uppercase italic tracking-tighter mb-8 text-center">
                Úprava <span class="text-indigo-500">komentáře</span>
            </h2>

            <form action="<?= BASE_URL ?>/index.php?url=comment/update/<?= $comment['id'] ?>" method="POST">
                <div class="mb-6">
                    <label for="rating" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Hodnocení</label>
                    <select id="rating" name="rating" class="w-full bg-slate-950 border border-slate-800 text-amber-500 font-bold text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-4 transition-all appearance-none cursor-pointer">
                        <?php $currentRating = isset($comment['rating']) ? (int)$comment['rating'] : 5; ?>
                        <option value="5" <?= $currentRating == 5 ? 'selected' : '' ?>>★★★★★ (5/5)</option>
                        <option value="4" <?= $currentRating == 4 ? 'selected' : '' ?>>★★★★☆ (4/5)</option>
                        <option value="3" <?= $currentRating == 3 ? 'selected' : '' ?>>★★★☆☆ (3/5)</option>
                        <option value="2" <?= $currentRating == 2 ? 'selected' : '' ?>>★★☆☆☆ (2/5)</option>
                        <option value="1" <?= $currentRating == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1/5)</option>
                    </select>
                </div>
                <div class="mb-8">
                    <label for="content" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Komentář</label>
                    <textarea id="content" name="content" rows="6" required
                              class="w-full bg-slate-950 border border-slate-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-all"
                              ><?= htmlspecialchars($comment['content']) ?></textarea>
                </div>
                
                <div class="flex gap-4">
                    <a href="<?= BASE_URL ?>/index.php?url=game/show/<?= $comment['game_id'] ?>" class="flex-1 text-center py-4 bg-slate-800 hover:bg-slate-700 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all border border-slate-700">
                        Zrušit
                    </a>
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-[10px] py-4 rounded-xl transition-all shadow-lg hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
