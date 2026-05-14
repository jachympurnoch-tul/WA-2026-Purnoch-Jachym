<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-12 gap-6">
            <div>
                <h2 class="text-4xl font-black text-white tracking-tight uppercase italic">
                    Katalog <span class="text-indigo-500">Videoher</span>
                </h2>
                <p class="text-slate-400 mt-2 font-medium">Prozkoumejte nejnovější tituly v naší databázi.</p>
            </div>
            
            <form action="<?= BASE_URL ?>/index.php" method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <input type="hidden" name="url" value="game/index">
                
                <div class="relative flex-grow sm:max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Hledat hru..." 
                           class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm font-bold placeholder-slate-500">
                </div>
                
                <details class="w-full sm:w-auto relative z-30 group cursor-pointer">
                    <summary class="px-4 py-2 bg-slate-900 border border-slate-700 text-slate-300 font-bold rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm list-none flex justify-between items-center min-w-[160px] select-none">
                        <span>Vybrat tagy...</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="absolute left-0 sm:right-auto right-0 top-full mt-2 w-[280px] sm:w-[400px] p-4 bg-slate-950 border border-slate-700 rounded-xl shadow-2xl grid grid-cols-2 gap-2 max-h-64 overflow-y-auto custom-scrollbar">
                        <?php 
                        $selectedTags = isset($_GET['tags']) && is_array($_GET['tags']) ? $_GET['tags'] : [];
                        foreach ($tags as $t): 
                            $isChecked = in_array($t['id'], $selectedTags) ? 'checked' : '';
                        ?>
                            <label class="flex items-center space-x-2 cursor-pointer p-1.5 hover:bg-slate-800 rounded-lg transition-colors">
                                <input type="checkbox" name="tags[]" value="<?= $t['id'] ?>" <?= $isChecked ?> class="form-checkbox w-4 h-4 text-indigo-500 rounded bg-slate-900 border-slate-600 focus:ring-indigo-500 focus:ring-offset-slate-900">
                                <span class="text-xs font-bold text-slate-300"><?= htmlspecialchars($t['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </details>

                <select name="subcategory" class="w-full sm:w-auto px-4 py-2 bg-slate-900 border border-slate-700 text-slate-300 font-bold rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm appearance-none cursor-pointer">
                    <option value="">Všechny platformy</option>
                    <?php foreach ($subcategories as $subcat): ?>
                        <option value="<?= $subcat['id'] ?>" <?= (isset($_GET['subcategory']) && $_GET['subcategory'] == $subcat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subcat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all shadow-lg hover:shadow-[0_0_15px_rgba(79,70,229,0.4)] whitespace-nowrap">
                    Filtrovat
                </button>
            </form>
        </div>

        <!-- Games Grid -->
        <?php if (!empty($games)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($games as $game): ?>
                    <div class="group relative bg-slate-900 rounded-2xl overflow-hidden border border-slate-800 hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex flex-col">
                        <!-- Image Container -->
                        <div class="aspect-[4/3] overflow-hidden relative bg-slate-800">
                            <?php 
                                $images = json_decode($game['images'], true);
                                $firstImage = (!empty($images) && is_array($images)) ? $images[0] : null;
                            ?>
                            <?php if ($firstImage): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($firstImage) ?>" 
                                     alt="<?= htmlspecialchars($game['title']) ?>" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-600">
                                    <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Badges (Tags) -->
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2 pr-16 max-h-[60px] overflow-hidden">
                                <?php 
                                    $tagNames = !empty($game['tag_names']) ? explode('||', $game['tag_names']) : [];
                                    $displayTags = array_slice($tagNames, 0, 3); // Zobrazíme max 3 na kartě
                                    foreach ($displayTags as $tagName): 
                                ?>
                                    <span class="px-3 py-1 bg-black/60 backdrop-blur-md text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-500/30">
                                        <?= htmlspecialchars($tagName) ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if(count($tagNames) > 3): ?>
                                    <span class="px-2 py-1 bg-black/60 backdrop-blur-md text-slate-400 text-[10px] font-black rounded-lg border border-slate-700/50">
                                        +<?= count($tagNames) - 3 ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="absolute top-4 right-4">
                                <?php if ($game['price'] > 0): ?>
                                    <span class="px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-lg">
                                        <?= number_format($game['price'], 0, ',', ' ') ?> Kč
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                                        ZDARMA
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-grow flex flex-col">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition-colors line-clamp-1">
                                    <?= htmlspecialchars($game['title']) ?>
                                </h3>
                                <p class="text-slate-500 text-sm font-medium italic mt-1">
                                    by <?= htmlspecialchars($game['developer']) ?>
                                </p>
                            </div>

                            <div class="flex items-center justify-between text-slate-400 text-xs font-bold uppercase tracking-wider mb-6">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <?= $game['release_year'] ?>
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <?= htmlspecialchars($game['subcategory_name'] ?: 'Multiplatform') ?>
                                </span>
                            </div>

                            <div class="mt-auto flex gap-2">
                                <a href="<?= BASE_URL ?>/index.php?url=game/show/<?= $game['id'] ?>" class="flex-grow text-center bg-slate-800 hover:bg-slate-700 text-white py-3 rounded-xl font-bold text-sm transition-all border border-slate-700">
                                    DETAIL
                                </a>
                                
                                <?php if (isset($_SESSION['user_id']) && ($game['created_by'] == $_SESSION['user_id'] || !empty($_SESSION['is_admin']))): ?>
                                    <a href="<?= BASE_URL ?>/index.php?url=game/edit/<?= $game['id'] ?>" class="p-3 bg-amber-600/10 hover:bg-amber-600 text-amber-500 hover:text-white rounded-xl transition-all border border-amber-600/30">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-32 bg-black/40 rounded-[3rem] border-2 border-dashed border-slate-800/50 backdrop-blur-sm">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-600/10 rounded-full mb-8 text-indigo-500 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-white mb-3 uppercase tracking-tighter italic [word-spacing:0.2em]">Žádné hry k nalezení</h3>
                <p class="text-slate-500 max-w-sm mx-auto font-medium">Zatím nikdo do katalogu nic nepřidal. Buďte první a podělte se o svůj oblíbený titul!</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
