<?php

class GameController {

    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Game.php';
        require_once '../app/models/Tag.php';
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();

        $search = $_GET['search'] ?? '';
        $tagIds = $_GET['tags'] ?? [];
        if (!is_array($tagIds)) {
            $tagIds = [$tagIds];
        }
        $tagIds = array_filter($tagIds); // Remove empty values
        $subcategoryId = $_GET['subcategory'] ?? '';

        $gameModel = new Game($db);
        $games = $gameModel->getAll($search, $tagIds, $subcategoryId);
        
        $tagModel = new Tag($db);
        $tags = $tagModel->getAllTags();
        
        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();
        
        require_once '../app/views/games/games_list.php';
    }

    // Zobrazení formuláře pro přidání hry
    public function create() {
        // Kontrola přihlášení (pokud ji už máte zavedenou)
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání hry se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        // ZMĚNA: Načtení databáze a modelů Tag a Subcategory
        require_once '../app/models/Database.php';
        require_once '../app/models/Tag.php';
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();

        // ZMĚNA: Získání seznamu tagů a subkategorií
        $tagModel = new Tag($db);
        $tags = $tagModel->getAllTags();

        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();

        // V šabloně game_create.php nyní budeme mít k dispozici pole $categories a $subcategories
        require_once '../app/views/games/game_create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $this->addErrorMessage('Pro uložení hry musíte být přihlášeni.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }
            $userId = $_SESSION['user_id'];

            $title = htmlspecialchars($_POST['title'] ?? '');
            $categoryId = null; // Legacy, musi byt null kvuli FK
            $subcategoryId = (int)($_POST['subcategory'] ?? 0); 
            $developer = htmlspecialchars($_POST['developer'] ?? '');
            $publisher = htmlspecialchars($_POST['publisher'] ?? '');
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            
            $tags = $_POST['tags'] ?? [];
            if (empty($tags) || count($tags) > 5) {
                $this->addErrorMessage('Vyberte prosím 1 až 5 tagů.');
                header('Location: ' . BASE_URL . '/index.php?url=game/create');
                exit;
            }

            if ($year < 1970 || $year > 2035) {
                $this->addErrorMessage('Rok vydání musí být mezi 1970 a 2035.');
                header('Location: ' . BASE_URL . '/index.php?url=game/create');
                exit;
            }

            if ($price < 0) {
                $this->addErrorMessage('Cena nemůže být záporná.');
                header('Location: ' . BASE_URL . '/index.php?url=game/create');
                exit;
            }

            $uploadedImages = $this->processImageUploads();

            require_once '../app/models/Database.php';
            require_once '../app/models/Game.php';
            require_once '../app/models/Tag.php';

            $database = new Database();
            $db = $database->getConnection();

            $gameModel = new Game($db);
            
            $gameId = $gameModel->create(
                $title, $categoryId, $subcategoryId, $developer, 
                $year, $price, $publisher, $description, $link, $uploadedImages,
                $userId
            );

            if ($gameId) {
                $tagModel = new Tag($db);
                $tagModel->updateGameTags($gameId, $tags);
                
                $this->addSuccessMessage('Hra byla úspěšně uložena.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nepodařilo se uložit hru do databáze.');
            }
        }
    }

    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }

    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'avif', 'svg', 'tiff', 'ico'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue;
                    }

                    $newName = 'game_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání hry se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID hry ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Game.php';

        $database = new Database();
        $db = $database->getConnection();
        $gameModel = new Game($db);

        $game = $gameModel->getById($id);

        if (!$game) {
            $this->addErrorMessage('Hra nebyla nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($game['created_by'] != $_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění smazat tuto hru.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isDeleted = $gameModel->delete($id);

        if ($isDeleted) {
            $this->addSuccessMessage('Hra byla úspěšně smazána.');
        } else {
            $this->addErrorMessage('Nastala chyba při mazání hry.');
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function update($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID hry k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title'] ?? '');
            $categoryId = null; // Legacy, musi byt null kvuli FK
            $subcategoryId = (int)($_POST['subcategory'] ?? 0);
            $developer = htmlspecialchars($_POST['developer'] ?? '');
            $publisher = htmlspecialchars($_POST['publisher'] ?? '');
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $tags = $_POST['tags'] ?? [];
            if (empty($tags) || count($tags) > 5) {
                $this->addErrorMessage('Vyberte prosím 1 až 5 tagů.');
                header('Location: ' . BASE_URL . '/index.php?url=game/edit/' . $id);
                exit;
            }

            if ($year < 1970 || $year > 2035) {
                $this->addErrorMessage('Rok vydání musí být mezi 1970 a 2035.');
                header('Location: ' . BASE_URL . '/index.php?url=game/edit/' . $id);
                exit;
            }

            if ($price < 0) {
                $this->addErrorMessage('Cena nemůže být záporná.');
                header('Location: ' . BASE_URL . '/index.php?url=game/edit/' . $id);
                exit;
            }

            require_once '../app/models/Database.php';
            require_once '../app/models/Game.php';
            require_once '../app/models/Tag.php';

            $database = new Database();
            $db = $database->getConnection();
            $gameModel = new Game($db);

            $uploadedImages = $this->processImageUploads(); 

            if (empty($uploadedImages)) {
                $existingGame = $gameModel->getById($id);
                if ($existingGame && !empty($existingGame['images'])) {
                    $decoded = json_decode($existingGame['images'], true);
                    $uploadedImages = is_array($decoded) ? $decoded : [];
                }
            }

            $isUpdated = $gameModel->update(
                $id, $title, $categoryId, $subcategoryId, $developer, 
                $year, $price, $publisher, $description, $link, $uploadedImages,
                $_SESSION['user_id']
            );

            if ($isUpdated) {
                $tagModel = new Tag($db);
                $tagModel->updateGameTags((int)$id, $tags);

                $this->addSuccessMessage('Hra byla úspěšně upravena.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání změn.');
            }
        }
    }

    // Zobrazení formuláře pro úpravu hry
    public function edit($id = null) {
        // Kontrola přihlášení
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu hry se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
        
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID hry k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // ZMĚNA: Načtení databáze a modelů
        require_once '../app/models/Database.php';
        require_once '../app/models/Game.php';
        require_once '../app/models/Tag.php';
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();

        // ZMĚNA: Získání seznamu tagů, subkategorií a dat hry
        $tagModel = new Tag($db);
        $tags = $tagModel->getAllTags();

        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();
        
        $gameModel = new Game($db);
        $game = $gameModel->getById($id);

        if (!$game) {
            $this->addErrorMessage('Hra nebyla nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($game['created_by'] != $_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tuto hru.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/games/game_edit.php';
    }

    public function show($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID hry k zobrazení.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Game.php';
        require_once '../app/models/Comment.php';

        $database = new Database();
        $db = $database->getConnection();

        $gameModel = new Game($db);
        $game = $gameModel->getById($id);

        if (!$game) {
            $this->addErrorMessage('Hra nebyla nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel = new Comment($db);
        $comments = $commentModel->getByGameId((int)$id);

        require_once '../app/views/games/game_show.php';
    }

}
