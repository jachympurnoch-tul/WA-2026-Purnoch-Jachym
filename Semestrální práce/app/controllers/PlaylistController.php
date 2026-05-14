<?php

class PlaylistController {
    
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name'] ?? 'Nový playlist');
            
            require_once '../app/models/Database.php';
            require_once '../app/models/Playlist.php';
            $db = (new Database())->getConnection();
            $playlistModel = new Playlist($db);
            
            $playlistModel->create($_SESSION['user_id'], $name);
            
            header('Location: ' . BASE_URL . '/index.php?url=song/playlist');
            exit;
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id'])) exit;
        
        require_once '../app/models/Database.php';
        require_once '../app/models/Playlist.php';
        $db = (new Database())->getConnection();
        (new Playlist($db))->delete((int)$id, $_SESSION['user_id']);
        
        header('Location: ' . BASE_URL . '/index.php?url=song/playlist');
        exit;
    }

    public function updateImage($id) {
        if (!isset($_SESSION['user_id'])) exit;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $file = $_FILES['image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newName = 'playlist_' . uniqid() . '.' . $ext;
                
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                    require_once '../app/models/Database.php';
                    require_once '../app/models/Playlist.php';
                    $db = (new Database())->getConnection();
                    (new Playlist($db))->updateImage((int)$id, $_SESSION['user_id'], $newName);
                }
            }
        }
        header('Location: ' . BASE_URL . '/index.php?url=song/playlist');
        exit;
    }
    public function resetImage($id) {
        if (!isset($_SESSION['user_id'])) exit;

        require_once '../app/models/Database.php';
        require_once '../app/models/Playlist.php';
        $db = (new Database())->getConnection();
        (new Playlist($db))->clearImage((int)$id, $_SESSION['user_id']);

        header('Location: ' . BASE_URL . '/index.php?url=song/playlist');
        exit;
    }
}
