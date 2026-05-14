<?php require_once '../app/views/layout/header.php'; ?>

<main class="w-full pb-32">
    <div class="container mx-auto px-6 pt-12">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4 mb-8">
                <a href="<?= BASE_URL ?>/index.php" data-nav class="w-10 h-10 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:text-white hover:bg-zinc-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h2 class="text-3xl font-black text-white tracking-tight">Nahrát novou skladbu</h2>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=song/store" method="POST" enctype="multipart/form-data" class="bg-zinc-900/50 border border-zinc-800 rounded-3xl p-8 shadow-2xl backdrop-blur-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Základní info -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Název skladby</label>
                            <input type="text" name="title" required placeholder="Např. Feel Invincible"
                                   class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Interpret</label>
                            <input type="text" name="artist" required placeholder="Např. Skillet"
                                   class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Album</label>
                                <input type="text" name="album" placeholder="Např. Unleashed"
                                       class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Žánr</label>
                                <input type="text" name="genre" placeholder="Např. Rock"
                                       class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Rok vydání</label>
                                <input type="number" name="release_year" placeholder="2024"
                                       class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Délka (m:ss)</label>
                                <input type="text" name="duration" placeholder="3:45"
                                       class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Soubory a Odkazy -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Audio soubor (MP3)</label>
                            <div class="relative group">
                                <input type="file" name="audio_file" accept="audio/mpeg" required
                                       class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 border-dashed rounded-2xl text-zinc-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Obrázek (Cover)</label>
                            <input type="file" name="images[]" accept="image/*" multiple
                                   class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 border-dashed rounded-2xl text-zinc-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-zinc-700 file:text-white hover:file:bg-zinc-600 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Odkaz (např. YouTube/Spotify)</label>
                            <input type="url" name="link" placeholder="https://..."
                                   class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ml-1">Popis / Text písně</label>
                            <textarea name="description" rows="3" placeholder="O čem je tato skladba..."
                                      class="w-full px-5 py-3.5 bg-zinc-800/50 border border-zinc-700 rounded-2xl text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-zinc-800 flex items-center justify-between">
                    <p class="text-xs text-zinc-500 max-w-sm">
                        Nahráním skladby potvrzujete, že máte práva k jejímu šíření. Povolené formáty: MP3 pro audio, JPG/PNG pro obrázky.
                    </p>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-black px-10 py-4 rounded-full shadow-lg shadow-blue-500/20 transition-all hover:scale-105 active:scale-95 uppercase tracking-widest text-sm">
                        Uložit skladbu
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
