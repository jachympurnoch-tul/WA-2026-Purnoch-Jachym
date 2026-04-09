<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl border-t-4 border-orange-500 overflow-hidden text-slate-800 mb-8 mt-8">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8 pb-4 border-b-2 border-slate-100">
            <h2 class="text-3xl font-extrabold text-slate-900 uppercase tracking-wide">
                <span class="text-slate-400 font-light">Detail:</span> <?= htmlspecialchars($book['title'] ?? '') ?>
            </h2>
            <a href="<?= BASE_URL ?>/index.php" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-md transition font-medium text-sm tracking-wider uppercase shadow-md hover:shadow-lg">
                &larr; Zpět
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Název</span>
                    <span class="text-slate-900 font-bold text-lg leading-tight"><?= htmlspecialchars($book['title'] ?? '') ?></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Autor</span>
                    <span class="text-slate-800 font-medium"><?= htmlspecialchars($book['author'] ?? '') ?></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Kategorie</span>
                    <span class="text-slate-800"><?= htmlspecialchars($book['category'] ?? '') ?></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Podkateg.</span>
                    <span class="text-slate-800"><?= htmlspecialchars($book['subcategory'] ?? '') ?></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Rok vydání</span>
                    <span class="text-slate-800"><?= htmlspecialchars($book['year'] ?? '') ?></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Cena</span>
                    <span class="text-orange-600 font-extrabold text-xl"><?= htmlspecialchars($book['price'] ?? '') ?> Kč</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-end">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">ISBN</span>
                    <span class="text-slate-600 font-mono"><?= htmlspecialchars($book['isbn'] ?? '') ?></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-1 sm:gap-4 border-b border-slate-100 pb-3 sm:items-center">
                    <span class="font-semibold text-slate-400 uppercase text-xs tracking-widest">Odkaz</span>
                    <span>
                        <?php if (!empty($book['link'])): ?>
                            <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank" class="text-orange-500 hover:text-orange-600 font-bold transition inline-flex items-center group">
                                Otevřít odkaz
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        <?php else: ?>
                            <span class="text-slate-400 italic text-sm">Není k dispozici</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <div>
                <?php 
                $images = !empty($book['images']) ? json_decode($book['images'], true) : [];
                if (!empty($images) && is_array($images)): 
                ?>
                <div class="rounded-xl overflow-hidden shadow-lg border-2 border-slate-100 relative group bg-slate-50">
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>" alt="Obálka knihy" class="w-full max-h-[450px] object-contain transition-transform duration-700 group-hover:scale-105">
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Popis přes celou šířku -->
        <div class="mt-10 pt-8 border-t-2 border-slate-100">
            <h3 class="font-extrabold text-slate-900 uppercase tracking-widest text-sm mb-4">Popis knihy</h3>
            <?php if (!empty($book['description'])): ?>
                <div class="bg-slate-50 p-6 rounded-r-lg border-l-4 border-orange-500 text-slate-700 shadow-sm leading-relaxed text-sm">
                    <?= nl2br(htmlspecialchars($book['description'])) ?>
                </div>
            <?php else: ?>
                <p class="text-slate-400 italic bg-white border border-dashed border-slate-300 p-6 rounded-lg text-center font-light">U této knihy není uveden žádný popis.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
