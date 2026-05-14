<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-12">
            <h2 class="text-4xl font-black text-white tracking-tight uppercase italic">
                Upravit <span class="text-indigo-500">Hru</span>
            </h2>
            <p class="text-slate-400 mt-2 font-medium">Provádíte změny u hry: <?= htmlspecialchars($game['title']) ?></p>
        </div>

        <form action="<?= BASE_URL ?>/index.php?url=game/update/<?= $game['id'] ?>" method="POST" enctype="multipart/form-data" class="bg-slate-900 rounded-3xl p-8 lg:p-12 border border-slate-800 shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Název hry *</label>
                    <input type="text" id="title" name="title" required value="<?= htmlspecialchars($game['title']) ?>"
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Developer -->
                <div>
                    <label for="developer" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Vývojář *</label>
                    <input type="text" id="developer" name="developer" required value="<?= htmlspecialchars($game['developer']) ?>"
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Publisher -->
                <div>
                    <label for="publisher" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Vydavatel *</label>
                    <input type="text" id="publisher" name="publisher" required value="<?= htmlspecialchars($game['publisher']) ?>"
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <?php $currentTags = !empty($game['tag_ids']) ? explode('||', $game['tag_ids']) : []; ?>
                <!-- Tags (from DB) - Checkboxes -->
                <div class="md:col-span-2">
                    <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Tagy (Vyberte 1 až 5) *</label>
                    <div class="bg-slate-950 border border-slate-800 rounded-xl p-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 max-h-48 overflow-y-auto custom-scrollbar">
                            <?php foreach ($tags as $t): ?>
                                <?php $isChecked = in_array($t['id'], $currentTags) ? 'checked' : ''; ?>
                                <label class="flex items-center space-x-3 cursor-pointer group p-2 rounded-lg hover:bg-slate-800/50 transition-colors">
                                    <input type="checkbox" name="tags[]" value="<?= $t['id'] ?>" <?= $isChecked ?> class="tag-checkbox w-4 h-4 text-indigo-500 border-slate-600 bg-slate-900 rounded focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all">
                                    <span class="text-sm font-bold text-slate-300 group-hover:text-indigo-400 transition-colors select-none"><?= htmlspecialchars($t['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p id="tags-counter" class="text-xs text-indigo-400 mt-3 font-bold border-t border-slate-800 pt-3">Vybráno: 0 / 5</p>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const checkboxes = document.querySelectorAll('.tag-checkbox');
                        const counter = document.getElementById('tags-counter');
                        
                        function updateTags() {
                            const checkedCount = document.querySelectorAll('.tag-checkbox:checked').length;
                            counter.textContent = `Vybráno: ${checkedCount} / 5`;
                            
                            if (checkedCount >= 5) {
                                checkboxes.forEach(cb => {
                                    if (!cb.checked) cb.disabled = true;
                                });
                            } else {
                                checkboxes.forEach(cb => cb.disabled = false);
                            }
                        }
                        
                        checkboxes.forEach(cb => cb.addEventListener('change', updateTags));
                        updateTags();
                    });
                </script>


                <!-- Subcategory (Platform from DB) -->
                <div class="md:col-span-2">
                    <label for="subcategory" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Platforma (Subkategorie) *</label>
                    <select id="subcategory" name="subcategory" required
                            class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="">-- Vyberte platformu --</option>
                        <?php foreach ($subcategories as $sub): ?>
                            <?php 
                            $isSelected = ($game['subcategory'] == $sub['id']) ? 'selected' : '';
                            ?>
                            <option value="<?= htmlspecialchars($sub['id']) ?>" <?= $isSelected ?>>
                                <?= htmlspecialchars($sub['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Year -->
                <div>
                    <label for="year" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Rok vydání *</label>
                    <input type="number" id="year" name="year" required value="<?= $game['release_year'] ?>" min="1970" max="2035"
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Cena (Kč) *</label>
                    <input type="number" id="price" name="price" required step="0.01" value="<?= $game['price'] ?>" min="0"
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Link -->
                <div class="md:col-span-2">
                    <label for="link" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Odkaz do obchodu</label>
                    <input type="url" id="link" name="link" value="<?= htmlspecialchars($game['link']) ?>"
                           class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Popis hry</label>
                    <textarea id="description" name="description" rows="5"
                              class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"><?= htmlspecialchars($game['description']) ?></textarea>
                </div>

                <!-- Current Images -->
                <div class="md:col-span-2">
                    <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Aktuální obrázky</label>
                    <div class="grid grid-cols-4 gap-4">
                        <?php 
                            $images = json_decode($game['images'], true);
                            if (!empty($images) && is_array($images)):
                                foreach ($images as $img):
                        ?>
                                    <div class="aspect-square rounded-xl overflow-hidden border border-slate-700 relative group">
                                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-[10px] text-white font-black uppercase">Stávající</span>
                                        </div>
                                    </div>
                        <?php 
                                endforeach;
                            endif;
                        ?>
                    </div>
                </div>

                <!-- New Images -->
                <div class="md:col-span-2">
                    <label class="block text-slate-400 text-xs font-black uppercase tracking-widest mb-3">Nahrát nové obrázky (nahradí ty staré)</label>
                    <div class="relative group">
                        <input type="file" id="images" name="images[]" multiple accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="w-full bg-slate-800 border-2 border-dashed border-slate-700 group-hover:border-indigo-500 transition-colors p-8 rounded-xl text-center">
                            <svg class="w-10 h-10 text-slate-600 group-hover:text-indigo-400 transition-colors mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-slate-400 font-bold">Vyberte soubory nebo je přetáhněte sem</p>
                            <p class="text-slate-600 text-[10px] uppercase font-black tracking-widest mt-1">Podporuje JPG, PNG, WEBP, AVIF, SVG a další</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col md:flex-row gap-4">
                <button type="submit" class="flex-grow bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest py-5 rounded-2xl transition-all shadow-2xl">
                    ULOŽIT ZMĚNY
                </button>
                <a href="<?= BASE_URL ?>/index.php?url=game/show/<?= $game['id'] ?>" class="bg-slate-800 hover:bg-slate-700 text-white font-black uppercase tracking-widest px-8 py-5 rounded-2xl transition-all border border-slate-700">
                    ZRUŠIT
                </a>
            </div>
        </form>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
