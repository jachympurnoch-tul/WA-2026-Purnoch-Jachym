<?php

class BookController {

    // 0. Výchozí metoda pro zobrazení úvodní stránky
        public function index() {
        // Načtení potřebných tříd
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        // Vytvoření připojení k databázi
        $database = new Database();
        $db = $database->getConnection();

        // Inicializace modelu a získání dat
        $bookModel = new Book($db);
        $books = $bookModel->getAll(); // Proměnná $books nyní obsahuje pole všech knih
        
        // Načte se (vloží) připravený soubor s HTML strukturou
        require_once '../app/views/books/books_list.php';
    }


    // 1. Zobrazení formuláře pro přidání nové knihy
    public function create() {
        // Zde se pouze načte (vloží) připravený soubor s HTML formulářem
        require_once '../app/views/books/book_create.php';
    }

       // 2. Zpracování dat odeslaných z formuláře
    public function store() {
        // Kontrola, zda byl formulář odeslán metodou POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Získání a očištění textových dat (ochrana proti XSS)
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            // U číselných hodnot se provádí explicitní přetypování
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            // ZDE JE ZMĚNA: Zavolání metody, která zpracuje soubory v $_FILES
            // Vrátí nám hezké pole s novými názvy (např. ['book_123.jpg', 'book_456.png'])
            $uploadedImages = $this->processImageUploads(); 

            // 2. Komunikace s databází a modelem
            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            // Vytvoření připojení k DB
            $database = new Database();
            $db = $database->getConnection();

            // Vytvoření objektu knihy a volání metody pro uložení
            $bookModel = new Book($db);
            $isSaved = $bookModel->create(
                $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages
            );

            // 3. Vyhodnocení výsledku a přesměrování
            if ($isSaved) {
                // Vyvolání zelené notifikace pro úspěšnou akci
                $this->addSuccessMessage('Kniha byla úspěšně uložena do databáze.');
                
                // Přesměrování zpět na hlavní stránku s využitím dynamické BASE_URL
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                // Vyvolání červené notifikace pro kritické selhání
                $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit knihu do databáze.');
            }
            
        } else {
            // Pokud je stránka navštívena napřímo bez odeslání dat, zobrazí se žlutá informativní zpráva
            $this->addNoticeMessage('Pro přidání knihy je nutné odeslat formulář.');
        }
    }

    // --- Pomocné metody pro systém notifikací ---
    // (V reálném projektu by tyto metody ideálně ležely v hlavní nadřazené třídě Controller)

    protected function addSuccessMessage($message) {
        // Zde by byla logika pro uložení zelené zprávy o úspěchu (např. do $_SESSION)
    }

    protected function addNoticeMessage($message) {
        // Zde by byla logika pro uložení žluté informativní zprávy (např. do $_SESSION)
    }

    protected function addErrorMessage($message) {
        // Zde by byla logika pro uložení červené chybové zprávy (např. do $_SESSION)
    }

    // --- Pomocná metoda pro zpracování nahrávání obrázků ---
    protected function processImageUploads() {
        $uploadedFiles = [];
        
        // Cesta ke složce, kam se budou obrázky fyzicky ukládat (relativně od index.php)
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        
        // Zkontrolujeme, zda vůbec existuje adresář, pokud ne, vytvoříme ho
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Zkontrolujeme, zda byl odeslán alespoň jeden soubor
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                // Pokud při nahrávání tohoto konkrétního souboru nedošlo k chybě
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    // Zjištění koncovky (např. jpg, png)
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    // Můžeme zde přidat i kontrolu povolených formátů (volitelné)
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue; // Přeskočíme nepodporovaný soubor
                    }

                    // 1. Vygenerování unikátního jména pomocí aktuálního času a náhodného řetězce
                    // např: book_64a2b1c_8f2a.jpg
                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    // 2. Fyzický přesun souboru z dočasné paměti do naší složky uploads
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        // 3. Uložení POUZE NÁZVU do pole, které pak pošleme databázi
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

        // 3. Smazání existující knihy
    public function delete($id = null) {
        // Kontrola, zda bylo v URL předáno ID
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení potřebných tříd a spojení s databází
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        // Inicializace modelu a zavolání metody pro smazání
        $bookModel = new Book($db);
        $isDeleted = $bookModel->delete($id);

        // Vyhodnocení výsledku a přesměrování s notifikací
        if ($isDeleted) {
            // Zelená zpráva o úspěchu
            $this->addSuccessMessage('Kniha byla trvale smazána z databáze.');
        } else {
            // Červená zpráva pro případ, že kniha neexistovala nebo selhal dotaz
            $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.');
        }

        // Vždy následuje návrat na seznam knih
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

        // 5. Zpracování dat odeslaných z editačního formuláře
    public function update($id = null) {
        // Zabezpečení: Je k dispozici ID a byl odeslán formulář?
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Získání a očištění textových dat
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            // Přetypování číselných hodnot
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            // 2. Komunikace s databází a modelem
            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();
            $bookModel = new Book($db);

            // ZDE JE ZMĚNA: Zavolání metody, která zpracuje soubory v $_FILES
            // Vrátí nám hezké pole s novými názvy (např. ['book_123.jpg', 'book_456.png'])
            $uploadedImages = $this->processImageUploads(); 

            // ZACHOVÁNÍ OBRÁZKŮ PŘI EDITACI (KROK 5)
            // Pokud uživatel nenahrál žádné nové fotky, zachováme mu ty stávající
            if (empty($uploadedImages)) {
                $existingBook = $bookModel->getById($id);
                if ($existingBook && !empty($existingBook['images'])) {
                    $decoded = json_decode($existingBook['images'], true);
                    $uploadedImages = is_array($decoded) ? $decoded : [];
                }
            }

            // 3. Volání updatu nad modelem
            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages
            );

            // 4. Vyhodnocení výsledku a přesměrování
            if ($isUpdated) {
                // Vyvolání zelené notifikace o úspěchu
                $this->addSuccessMessage('Kniha byla úspěšně upravena.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                // Vyvolání červené chybové notifikace
                $this->addErrorMessage('Nastala chyba. Změny se nepodařilo uložit.');
            }
            
        } else {
            // Pokud by někdo zkusil přistoupit na URL napřímo bez odeslání formuláře (žlutá notifikace)
            $this->addNoticeMessage('Pro úpravu knihy je nutné odeslat formulář.');
        }
    }
    // 4. Zobrazení formuláře pro úpravu existující knihy
    public function edit($id = null) {
        // Kontrola, zda bylo v URL vůbec předáno nějaké ID
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení potřebných tříd a spojení s databází
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        // Získání dat o konkrétní knize
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        // Bezpečnostní kontrola: Zda kniha s daným ID vůbec existuje
        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Pokud je vše v pořádku, načte se připravený soubor s HTML formulářem pro úpravy
        require_once '../app/views/books/book_edit.php';
    }

    // Zobrazení detailu jedné knihy
    public function show($id = null) {
        // Kontrola, zda bylo v URL předáno ID
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k zobrazení.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení potřebných tříd a spojení s databází
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        // Inicializace modelu a získání dat
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        // Bezpečnostní kontrola: Zda kniha s daným ID vůbec existuje
        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Pokud je vše v pořádku, načte se (vloží) nový HTML pohled pro detail
        require_once '../app/views/books/book_show.php';
    }

}