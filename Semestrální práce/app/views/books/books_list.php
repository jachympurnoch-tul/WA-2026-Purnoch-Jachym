<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl border-t-4 border-orange-500 text-slate-800 mb-8">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <h2 class="text-3xl font-extrabold text-slate-900 uppercase tracking-wide">Dostupné knihy</h2>
            <!-- Tlačítko pro přidání knihy (navazuje na hlavičku) je volitelně i zde -->
        </div>
        
        <?php if (empty($books)): ?>
            <div class="bg-slate-50 p-8 rounded-lg text-center border-dashed border-2 border-slate-300">
                <p class="text-slate-500 italic text-lg">V databázi se zatím nenachází žádné knihy.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto rounded-lg shadow-sm border border-slate-200">
                <table class="w-full border-collapse bg-white text-left">
                    <thead class="bg-slate-900 text-white border-b-4 border-orange-500">
                        <tr>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase">ID</th>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase w-16">Obr.</th>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase">Název knihy</th>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase">Autor</th>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase">Rok</th>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase">Cena</th>
                            <th class="p-4 font-semibold tracking-wider text-sm uppercase text-right">Akce</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 divide-y divide-slate-100">
                        <?php foreach ($books as $book): ?>
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="p-4 font-medium text-slate-400"><?= htmlspecialchars($book['id']) ?></td>
                                <td class="p-4">
                                    <?php 
                                    $images = !empty($book['images']) ? json_decode($book['images'], true) : [];
                                    if (!empty($images) && is_array($images)): 
                                    ?>
                                        <div class="w-12 h-16 hover:w-28 hover:h-40 transition-all duration-500 ease-in-out rounded-md overflow-hidden shadow-sm border border-slate-200 bg-white">
                                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>" alt="Obrázek" class="w-full h-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-16 bg-slate-50 rounded border border-dashed border-slate-300 flex items-center justify-center">
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">Není</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 font-bold text-slate-900 group-hover:text-orange-600 transition-colors"><?= htmlspecialchars($book['title']) ?></td>
                                <td class="p-4 font-medium"><?= htmlspecialchars($book['author']) ?></td>
                                <td class="p-4 text-slate-500"><?= htmlspecialchars($book['year']) ?></td>
                                <td class="p-4 text-orange-600 font-extrabold"><?= htmlspecialchars($book['price']) ?> Kč</td>
                                <td class="p-4 space-x-3 text-sm font-medium text-right whitespace-nowrap">
                                    <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="text-orange-500 hover:text-orange-700 uppercase tracking-widest text-xs transition">Detail</a>
                                    <span class="text-slate-300">|</span> 
                                    <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="text-slate-500 hover:text-slate-900 uppercase tracking-widest text-xs transition">Upravit</a>
                                    <span class="text-slate-300">|</span> 
                                    <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" class="text-rose-500 hover:text-rose-700 uppercase tracking-widest text-xs transition" onclick="return confirm('Opravdu chcete tuto knihu smazat?')">Smazat</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>