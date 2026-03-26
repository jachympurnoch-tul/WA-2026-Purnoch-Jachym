<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script-->
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <h2>Pridat novou knihu</h2>
            <p>Vyplnte udaje a ulozte knihu do databaze</p>
        </div>

        <div>
            <form action="../../controllers/BookController.php" method = "post" enctype="multipart/form-data"> 
                <div>
                    <div>
                        <label for="title">Nazev knihy <span>*</span></label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div>
                        <label for="author">Autor <span>*</span></label>
                        <input type="text" id="author" name="author" required placeholder = "Jméno Příjmení">
                    </div>
                    <div>
                        <label for="isbn">ISBN <span>*</span></label>
                        <input type="text" id="isbn" name="isbn">
                    </div>
                    <div>
                        <label for="category">Kategorie</label>
                        <input type="text" id="category" name="categoryr" >
                    </div>
                    <div>
                        <label for="subcategory">Podkategorie</label>
                        <input type="text" id="subcategory" name="subcategoryr" >
                    </div>
                    <div>
                        <label for="year">Rok vydani <span>*</span></label>
                        <input type="number" id="year" name="year"  required>
                    </div>
                    <div>
                        <label for="price">Cena Knihy <span>*</span></label>
                        <input type="number" id="price" name="price" step=0.5 >
                    </div>
                    <div>
                        <label for="link">Odkaz <span>*</span></label>
                        <input type="text" id="link" name="link">
                    </div>
                    <div>
                        <label for="desc">Popis knihy <span>*</span></label>
                        <textarea id="desc" name = "desc" rows = "5">Popis Knihy: </textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Obrázky (můžete nahrát více)</label>
                        <label class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition">
                            <span class="text-gray-600 font-medium">Klikni pro výběr souborů</span>
                            <span class="text-sm text-gray-400 mt-1">JPG / PNG / WebP – více souborů najednou</span>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                        </label>
                    </div>
                    <div>
                        <button type="submit">Ulozit knihu do databáze</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>