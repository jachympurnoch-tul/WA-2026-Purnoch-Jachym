<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-4 border-orange-500 text-slate-800 mb-8">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Přidat novou knihu</h2>
        <p class="text-slate-500">Vyplňte údaje a uložte knihu do databáze.</p>
    </div>
    
    <form action="<?= BASE_URL ?>/index.php?url=book/store" method="post" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Full width title -->
            <div class="md:col-span-3">
                <label for="title" class="block font-semibold mb-1 text-slate-700 text-sm">Název knihy <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" required class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            
            <!-- Author (2 cols) & ISBN (1 col) -->
            <div class="md:col-span-2">
                <label for="author" class="block font-semibold mb-1 text-slate-700 text-sm">Autor <span class="text-rose-500">*</span></label>
                <input type="text" id="author" name="author" placeholder="Příjmení Jméno" required class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            <div class="md:col-span-1">
                <label for="isbn" class="block font-semibold mb-1 text-slate-700 text-sm">ISBN</label>
                <input type="text" id="isbn" name="isbn" class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            
            <!-- Category & Subcategory -->
            <div class="md:col-span-2">
                <label for="category" class="block font-semibold mb-1 text-slate-700 text-sm">Kategorie</label>
                <input type="text" id="category" name="category" class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            <div class="md:col-span-1">
                <label for="subcategory" class="block font-semibold mb-1 text-slate-700 text-sm">Podkategorie</label>
                <input type="text" id="subcategory" name="subcategory" class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            
            <!-- Year, Price, Link -->
            <div class="md:col-span-1">
                <label for="year" class="block font-semibold mb-1 text-slate-700 text-sm">Rok vydání <span class="text-rose-500">*</span></label>
                <input type="number" id="year" name="year" required class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            <div class="md:col-span-1">
                <label for="price" class="block font-semibold mb-1 text-slate-700 text-sm">Cena knihy (Kč)</label>
                <input type="number" id="price" name="price" step="0.5" class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            <div class="md:col-span-1">
                <label for="link" class="block font-semibold mb-1 text-slate-700 text-sm">Odkaz / URL</label>
                <input type="text" id="link" name="link" class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-3">
                <label for="description" class="block font-semibold mb-1 text-slate-700 text-sm">Popis knihy</label>
                <textarea id="description" name="description" rows="2" class="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-orange-500 focus:outline-none transition">Popis knihy: </textarea>
            </div>    
            
            <!-- Images -->
            <div class="md:col-span-3">
                <label class="block font-semibold mb-1 text-slate-700 text-sm">Obrázky knihy</label>
                <label class="block border-2 border-dashed border-slate-300 p-4 text-center rounded hover:bg-slate-50 hover:border-orange-500 cursor-pointer transition">
                    <span id="file-title" class="block text-slate-700 font-semibold mb-1">Klikni pro výběr souborů</span>
                    <span id="file-info" class="block text-slate-500 text-xs mt-1">Žádné soubory nebyly vybrány</span>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                </label>
            </div>
            
            <!-- Button & Back link -->
            <div class="md:col-span-3 mt-2">
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow transition">
                    Uložit knihu do DB
                </button>
                <div class="text-center mt-4">
                    <a href="<?= BASE_URL ?>/index.php" class="text-slate-500 hover:text-orange-500 transition text-sm font-medium">
                        &larr; Zpět na seznam knih
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Najdeme naše HTML prvky podle ID
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    const fileInfo = document.getElementById('file-info');

    // Posloucháme událost 'change' (změna hodnoty v inputu)
    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        
        // 1. Zabezpečení proti špatným souborům na straně klienta
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        let hasInvalidFile = false;
        
        for (let i = 0; i < files.length; i++) {
            if (!allowedTypes.includes(files[i].type)) {
                hasInvalidFile = true;
                break;
            }
        }

        if (hasInvalidFile) {
            alert('Upozornění: Do tohoto pole lze nahrávat pouze skutečné obrázky (soubory s koncovkami JPG, PNG, WebP nebo GIF). Ostatní soubory byly odmítnuty.');
            fileInput.value = ''; // Vyresetujeme špatný výběr
            
            // Vrátíme styl do výchozího stavu
            fileTitle.textContent = 'Klikni pro výběr souborů';
            fileTitle.className = 'block text-slate-700 font-semibold mb-1';
            fileInfo.textContent = 'Žádné soubory nebyly vybrány';
            return; // Ukončíme okamžitě funkci
        }
        
        // 2. Soubory jsou v pořádku, změníme grafiku
        if (files.length === 0) {
            // Uživatel výběr zrušil prázdným klikem
            fileTitle.textContent = 'Klikni pro výběr souborů';
            fileTitle.className = 'block text-slate-700 font-semibold mb-1';
            fileInfo.textContent = 'Žádné soubory nebyly vybrány';
        } else if (files.length === 1) {
            // Vybrán 1 soubor - ukážeme jeho název
            fileTitle.textContent = 'Soubor připraven';
            fileTitle.className = 'block text-orange-500 font-bold mb-1';
            fileInfo.textContent = files[0].name;
        } else {
            // Vybráno více souborů - ukážeme počet
            fileTitle.textContent = 'Soubory připraveny';
            fileTitle.className = 'block text-orange-500 font-bold mb-1';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
        }
    });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>