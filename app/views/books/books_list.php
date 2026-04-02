<style>
    :root {
        --primary: #2c3e50;    /* Tmavě modrá hřbetu knihy */
        --accent: #e67e22;     /* Výrazná oranžová pro akce */
        --bg: #f8f9fa;         /* Světlé pozadí */
        --card-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    body {
        font-family: 'Segoe UI', Roboto, sans-serif;
        background-color: var(--bg);
        margin: 0;
        padding: 40px;
        color: #333;
    }

    h1 {
        color: var(--primary);
        font-size: 2.5rem;
        margin-bottom: 30px;
    }

    /* Navigace jako menu */
    ul {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 15px;
        margin-bottom: 40px;
    }

    /* Vzhled tlačítek v menu */
    ul li a {
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }

    /* Odkaz Domů */
    ul li a:not([href*="create"]) {
        background-color: #fff;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    /* Tlačítko Přidat novou knihu - Teď bude pořádně vidět! */
    ul li a[href*="create"] {
        background-color: var(--accent);
        color: white !important; /* Vynucení bílé barvy */
        box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);
    }

    ul li a:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* Tabulka jako čistá karta */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden; /* Aby rohy zůstaly zaoblené */
        box-shadow: var(--card-shadow);
    }

    th {
        background-color: var(--primary);
        color: white;
        text-align: left;
        padding: 18px;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    td {
        padding: 15px 18px;
        border-bottom: 1px solid #eee;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #fcfcfc;
    }

    /* Odkazy v tabulce (Akce) */
    td a {
        text-decoration: none;
        font-weight: bold;
        margin-right: 10px;
        color: var(--primary);
    }

    td a[href*="delete"] { color: #e74c3c; } /* Červená pro smazat */
    td a[href*="edit"] { color: #3498db; }   /* Modrá pro upravit */

    footer {
        margin-top: 40px;
        font-size: 0.9rem;
        color: #95a5a6;
    }
</style>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <title>Knihovna - Seznam knih</title>
</head>
<body>
    <header>
        <h1>Aplikace Knihovna</h1>
        
        <nav>
            <ul>
                <!-- Poznámka: do odkazu href se musí dát reálná absolutní/úplná cesta k souboru index.php -->
                <li><a href="<?= BASE_URL ?>/index.php">Seznam knih (Domů)</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?url=book/create">Přidat novou knihu</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Dostupné knihy</h2>
        
        <?php if (empty($books)): ?>
            <p>V databázi se zatím nenachází žádné knihy.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Název knihy</th>
                        <th>Autor</th>
                        <th>Rok vydání</th>
                        <th>Cena</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['id']) ?></td>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['year']) ?></td>
                            <td><?= htmlspecialchars($book['price']) ?> Kč</td>
                            <td>
                                <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>">Detail</a> | 
                                <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>">Upravit</a> | 
                                <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" onclick="return confirm('Opravdu chcete tuto knihu smazat?')">Smazat</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; WA 2026 - Výukový projekt</p>
    </footer>
</body>
</html>