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

    /* Tlačítka jako menu / zpět */
    .btn-back {
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        background-color: #fff;
        color: var(--primary);
        border: 2px solid var(--primary);
        margin-bottom: 20px;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* Detail knihy - Karta */
    .book-detail-wrapper {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
    }

    .book-detail-wrapper h2 {
        border-bottom: 2px solid var(--primary);
        padding-bottom: 10px;
        margin-top: 0;
        color: var(--primary);
    }

    table.detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table.detail-table th {
        text-align: left;
        width: 30%;
        padding: 12px 0;
        color: var(--primary);
        border-bottom: 1px solid #eee;
    }

    table.detail-table td {
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .description-box {
        background-color: #f9f9f9;
        padding: 15px;
        border-left: 4px solid var(--accent);
        border-radius: 4px;
        margin-top: 20px;
        font-style: italic;
        line-height: 1.6;
    }

    footer {
        margin-top: 40px;
        font-size: 0.9rem;
        color: #95a5a6;
        text-align: center;
    }
</style>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail knihy - <?= htmlspecialchars($book['title'] ?? 'Neznámá kniha') ?></title>
</head>
<body>
    <header>
        <h1>Aplikace Knihovna</h1>
    </header>

    <main>
        <div style="max-width: 800px; margin: 0 auto; text-align: left;">
            <a href="<?= BASE_URL ?>/index.php" class="btn-back">&larr; Zpět</a>
        </div>

        <div class="book-detail-wrapper">
            <h2>Detail: <?= htmlspecialchars($book['title'] ?? '') ?></h2>

            <table class="detail-table">
                <tr>
                    <th>Název:</th>
                    <td><?= htmlspecialchars($book['title'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Autor:</th>
                    <td><?= htmlspecialchars($book['author'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Kategorie:</th>
                    <td><?= htmlspecialchars($book['category'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Podkategorie:</th>
                    <td><?= htmlspecialchars($book['subcategory'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Rok vydání:</th>
                    <td><?= htmlspecialchars($book['year'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Cena:</th>
                    <td><?= htmlspecialchars($book['price'] ?? '') ?> Kč</td>
                </tr>
                <tr>
                    <th>ISBN:</th>
                    <td><?= htmlspecialchars($book['isbn'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Odkaz:</th>
                    <td>
                        <?php if (!empty($book['link'])): ?>
                            <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank"><?= htmlspecialchars($book['link']) ?></a>
                        <?php else: ?>
                            Není k dispozici
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <?php if (!empty($book['description'])): ?>
                <h3 style="color: var(--primary); margin-top: 30px;">Popis:</h3>
                <div class="description-box">
                    <?= nl2br(htmlspecialchars($book['description'])) ?>
                </div>
            <?php else: ?>
                <h3 style="color: var(--primary); margin-top: 30px;">Popis:</h3>
                <p>U této knihy není uveden žádný popis.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; WA 2026 - Výukový projekt</p>
    </footer>
</body>
</html>
