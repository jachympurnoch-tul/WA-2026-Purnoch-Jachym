<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="max-w-4xl mx-auto bg-zinc-900 rounded-xl shadow-2xl overflow-hidden text-zinc-300">
        
        <!-- Header sekce s přechodem -->
        <div class="bg-gradient-to-b from-blue-900/40 to-zinc-900 p-8 border-b border-zinc-800 relative">
            <a href="<?= BASE_URL ?>/index.php" class="absolute top-8 right-8 text-zinc-500 hover:text-white transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
            
            <div class="flex flex-col md:flex-row items-center md:items-end gap-8">
                <!-- Cover Alba -->
                <div class="w-48 h-48 md:w-56 md:h-56 flex-shrink-0 bg-black rounded shadow-2xl overflow-hidden border border-zinc-700/50">
                    <?php 
                    $images = !empty($song['images']) ? json_decode($song['images'], true) : [];
                    if (!empty($images) && is_array($images)): 
                    ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>" alt="Cover" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center text-zinc-600 bg-zinc-800">
                            <svg class="w-16 h-16 mb-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"></path></svg>
                            <span class="text-xs uppercase tracking-widest font-bold">Bez coveru</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Informace o písničce -->
                <div class="text-center md:text-left flex-grow">
                    <p class="text-xs uppercase tracking-widest font-bold text-zinc-400 mb-2">Písnička</p>
                    <h1 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight leading-tight"><?= htmlspecialchars($song['title']) ?></h1>
                    
                    <div class="flex items-center justify-center md:justify-start space-x-2 text-sm font-medium">
                        <div class="w-6 h-6 rounded-full bg-zinc-700 flex items-center justify-center text-white text-xs mr-1">
                            <?= strtoupper(substr(htmlspecialchars($song['artist']), 0, 1)) ?>
                        </div>
                        <span class="text-white hover:underline cursor-pointer"><?= htmlspecialchars($song['artist']) ?></span>
                        <span class="text-zinc-600">•</span>
                        <span class="text-zinc-400"><?= htmlspecialchars($song['release_year'] ?: 'Neznámý rok') ?></span>
                        <span class="text-zinc-600">•</span>
                        <span class="text-zinc-400"><?= htmlspecialchars($song['duration'] ?: 'Neznámá délka') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Akční tlačítka a obsah -->
        <div class="p-8">
            <div class="flex items-center space-x-4 mb-8">
                <?php if (!empty($song['link'])): ?>
                    <a href="<?= htmlspecialchars($song['link']) ?>" target="_blank" rel="noopener noreferrer" 
                       class="w-14 h-14 bg-blue-500 hover:bg-blue-400 text-white rounded-full flex items-center justify-center transition-transform hover:scale-105 active:scale-95 shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $song['created_by'] || !empty($_SESSION['is_admin']))): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=song/edit/<?= $song['id'] ?>" 
                       class="px-6 py-2 rounded-full border border-zinc-600 text-white hover:border-white font-bold text-sm tracking-widest transition-colors">
                        UPRAVIT
                    </a>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Hlavní sloupec (Popis) -->
                <div class="md:col-span-2">
                    <h3 class="text-xl font-bold text-white mb-4">Popis</h3>
                    <div class="text-zinc-400 leading-relaxed whitespace-pre-line bg-zinc-800/30 p-6 rounded-lg border border-zinc-800">
                        <?= !empty($song['description']) ? htmlspecialchars($song['description']) : '<span class="italic text-zinc-600">Interpret nepřidal žádný popis k této písničce.</span>' ?>
                    </div>
                </div>

                <!-- Boční sloupec (Detaily) -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Detaily</h3>
                    <div class="bg-zinc-800/30 p-6 rounded-lg border border-zinc-800 space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-zinc-500 mb-1">Album</p>
                            <p class="font-medium text-zinc-300"><?= htmlspecialchars($song['album'] ?: 'Singl / Neznámé') ?></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-zinc-500 mb-1">Žánr</p>
                            <p class="font-medium text-zinc-300">
                                <?php if (!empty($song['genre'])): ?>
                                    <span class="inline-block px-3 py-1 bg-zinc-800 rounded-full text-xs text-blue-400 border border-zinc-700">
                                        <?= htmlspecialchars($song['genre']) ?>
                                    </span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
