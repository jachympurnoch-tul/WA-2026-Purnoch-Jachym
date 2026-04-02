<style>
    :root {
        --primary: #2c3e50;
        --accent: #e67e22;
        --bg: #f8f9fa;
    }

    body {
        background-color: #fdfaf6;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        display: flex; /* Zapne centrování */
        flex-direction: column;
        align-items: center; /* Horizontální centrování */
        justify-content: center; /* Vertikální centrování */
        min-height: 100vh; /* Roztáhne tělo na celou výšku okna */
    }

    /* Kontejner pro nadpis a formulář */
    .form-wrapper {
        width: 100%;
        max-width: 500px;
        text-align: center;
    }

    h1 {
        color: var(--primary);
        margin-bottom: 5px;
    }

    p {
        color: #7f8c8d;
        margin-bottom: 25px;
    }

    /* Samotná "tabulka" formuláře */
    form {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-top: 6px solid var(--accent); /* Designový "hřbet" knihy */
        text-align: left; /* Text uvnitř formuláře bude vlevo */
    }

    label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary);
    }

    input, select, textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box; /* Aby padding nerozšiřoval input */
        font-size: 1rem;
    }

    input:focus {
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
    }

    /* Tlačítko pod formulářem */
    button[type="submit"], input[type="submit"] {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 15px;
        width: 100%;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: var(--accent);
    }

    /* Odkaz zpět */
    .back-link {
        display: block;
        margin-top: 20px;
        color: #95a5a6;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .back-link:hover { color: var(--primary); }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <h2>Přidat novou knihu</h2>
            <p>Vyplňte údaje a uložte knihu do databáze.</p>
        </div>
        
        <div>
            <form action="<?= BASE_URL ?>/index.php?url=book/store" method="post" enctype="multipart/form-data">
                <div>
                    <div>
                        <label for="title">Název knihy <span>*</span></label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div>
                        <label for="author">Autor <span>*</span></label>
                        <input type="text" id="author" name="author" placeholder="Příjmení Jméno" required>
                    </div>
                    <div>
                        <label for="isbn">ISBN <span>*</span></label>
                        <input type="text" id="isbn" name="isbn">
                    </div>
                    <div>
                        <label for="category">Kategorie </label>
                        <input type="text" id="category" name="category">
                    </div>
                    <div>
                        <label for="subcategory">Podkategorie </label>
                        <input type="text" id="subcategory" name="subcategory">
                    </div>
                    <div>
                        <label for="year">Rok vydání  <span>*</span></label>
                        <input type="number" id="year" name="year" required>
                    </div>
                    <div>
                        <label for="price">Cena knihy</label>
                        <input type="number" id="price" name="price" step="0.5">
                    </div>
                    <div>
                        <label for="link">Odkaz</label>
                        <input type="text" id="link" name="link">
                    </div>
                    <div>
                        <label for="description">Popis knihy</label>
                        <textarea id="description" name="description" rows="5">Popis knihy: </textarea>
                    </div>    
                    <div>
                        <label >Obrázky (můžete nahrát více)</label>
                        <label>
                            <span>Klikni pro výběr souborů</span>
                            <span>JPG / PNG / WebP – více souborů najednou</span>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                        </label>
                    </div>
                    <div>
                        <button type="submit">Uložit knihu do DB</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>
</html>