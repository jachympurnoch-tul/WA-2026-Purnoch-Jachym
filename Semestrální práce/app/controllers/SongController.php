<?php

class SongController {

    public function index() {
        require_once '../app/views/songs/songs_list.php';
    }

    public function spotifySearch() {
        $query = $_GET['q'] ?? '';
        if (empty($query)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        require_once '../app/models/SpotifyService.php';
        $spotify = new SpotifyService();
        
        // Speciální případy pro Novinky a Trendy
        if ($query === 'new release') {
            $results = $spotify->getNewReleases();
        } elseif ($query === 'top hits') {
            $results = $spotify->getTrendyTracks();
        } else {
            $results = $spotify->search($query);
        }

        // FALLBACK: Pokud Spotify vrátí prázdné pole (např. kvůli chybějícím klíčům), zkusíme iTunes
        if (empty($results)) {
            require_once '../app/models/iTunesSearch.php';
            $results = iTunesSearch::search($query);
        }

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }


    public function getFullVersion() {
        $title = $_GET['title'] ?? '';
        $artist = $_GET['artist'] ?? '';
        if (empty($title)) {
            echo json_encode(['error' => 'No title']);
            exit;
        }

        require_once '../app/models/YouTubeSearch.php';
        $videoId = YouTubeSearch::getFirstVideoId($artist . ' ' . $title);

        header('Content-Type: application/json');
        echo json_encode(['videoId' => $videoId]);
        exit;
    }

    /** API: Vrátí playlisty uživatele + příznak, zda je v nich tato skladba */
    public function getPlaylistsForSong() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'not_logged_in']); exit; }

        $title  = $_GET['title']  ?? '';
        $artist = $_GET['artist'] ?? '';

        require_once '../app/models/Database.php';
        require_once '../app/models/Playlist.php';
        require_once '../app/models/ExternalSong.php';

        $db = (new Database())->getConnection();
        $playlists   = (new Playlist($db))->getByUser((int)$_SESSION['user_id']);
        $extSong     = new ExternalSong($db);
        $savedInIds  = $extSong->getPlaylistsForSong($title, $artist);

        foreach ($playlists as &$p) {
            $p['has_song'] = in_array((int)$p['id'], array_map('intval', $savedInIds));
        }
        echo json_encode($playlists);
        exit;
    }

    /** API: Hromadně uloží/odebere skladbu z playlistů */
    public function syncSongPlaylists() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'not_logged_in']); exit; }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['error' => 'method']); exit; }

        $body = json_decode(file_get_contents('php://input'), true);
        $title      = $body['title']       ?? '';
        $artist     = $body['artist']      ?? '';
        $album      = $body['album']       ?? '';
        $image      = $body['image']       ?? '';
        $previewUrl = $body['previewUrl']  ?? '';
        $albumId    = $body['albumId']     ?? '';
        $artistId   = $body['artistId']    ?? '';
        $selected   = array_map('intval', $body['playlistIds'] ?? []);

        require_once '../app/models/Database.php';
        require_once '../app/models/Playlist.php';
        require_once '../app/models/ExternalSong.php';

        $db = (new Database())->getConnection();
        $allIds = array_map(fn($p) => (int)$p['id'], (new Playlist($db))->getByUser((int)$_SESSION['user_id']));
        (new ExternalSong($db))->syncPlaylists($selected, $title, $artist, $album, $image, $previewUrl, $allIds, $albumId, $artistId);

        // Pokud žádný playlist není vybrán → je odstraněna ze všech
        $msg = count($selected) === 0
            ? 'Skladba odebrána ze všech playlistů'
            : 'Uloženo do ' . count($selected) . ' playlist' . (count($selected) === 1 ? 'u' : 'ů');

        echo json_encode(['ok' => true, 'message' => $msg]);
        exit;
    }

    public function getLocalSongs() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Song.php';
        $db = (new Database())->getConnection();
        $songModel = new Song($db);
        $songs = $songModel->getAll();
        
        header('Content-Type: application/json');
        echo json_encode($songs);
        exit;
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
        require_once '../app/views/songs/song_create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }
            $userId = $_SESSION['user_id'];

            $title = htmlspecialchars($_POST['title'] ?? '');
            $artist = htmlspecialchars($_POST['artist'] ?? '');
            $album = htmlspecialchars($_POST['album'] ?? '');
            $genre = htmlspecialchars($_POST['genre'] ?? '');
            $release_year = (int)($_POST['release_year'] ?? 0);
            $duration = htmlspecialchars($_POST['duration'] ?? '');
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $uploadedImages = $this->processImageUploads();
            $audioFile = $this->processAudioUpload();

            require_once '../app/models/Database.php';
            require_once '../app/models/Song.php';
            $db = (new Database())->getConnection();
            $songModel = new Song($db);
            
            $isSaved = $songModel->create(
                $title, $artist, $album, $genre, 
                $release_year, $duration, $description, $link, $audioFile, $uploadedImages,
                $userId
            );

            if ($isSaved) {
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }
        }
    }

    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
        require_once '../app/models/Database.php';
        require_once '../app/models/Song.php';
        $db = (new Database())->getConnection();
        $songModel = new Song($db);
        $song = $songModel->getById($id);
        require_once '../app/views/songs/song_edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }
            $title = htmlspecialchars($_POST['title'] ?? '');
            $artist = htmlspecialchars($_POST['artist'] ?? '');
            $album = htmlspecialchars($_POST['album'] ?? '');
            $genre = htmlspecialchars($_POST['genre'] ?? '');
            $release_year = (int)($_POST['release_year'] ?? 0);
            $duration = htmlspecialchars($_POST['duration'] ?? '');
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            require_once '../app/models/Database.php';
            require_once '../app/models/Song.php';
            $db = (new Database())->getConnection();
            $songModel = new Song($db);

            $uploadedImages = $this->processImageUploads(); 
            $audioFile = $this->processAudioUpload();

            if (empty($uploadedImages)) {
                $existing = $songModel->getById($id);
                $uploadedImages = $existing['images'] ? json_decode($existing['images'], true) : [];
            }
            if (empty($audioFile)) {
                $existing = $songModel->getById($id);
                $audioFile = $existing['audio_file'] ?? null;
            }

            $songModel->update($id, $title, $artist, $album, $genre, $release_year, $duration, $description, $link, $audioFile, $uploadedImages, $_SESSION['user_id']);
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id'])) exit;
        require_once '../app/models/Database.php';
        require_once '../app/models/Song.php';
        $db = (new Database())->getConnection();
        $songModel = new Song($db);
        $songModel->delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=song/playlist');
        exit;
    }

    public function removeExternal() {
        if (!isset($_SESSION['user_id'])) exit;
        $playlistId = (int)($_GET['playlist_id'] ?? 0);
        $title = $_GET['title'] ?? '';
        $artist = $_GET['artist'] ?? '';

        require_once '../app/models/Database.php';
        require_once '../app/models/ExternalSong.php';
        $db = (new Database())->getConnection();
        (new ExternalSong($db))->removeFromPlaylist($playlistId, $title, $artist);
        
        header('Location: ' . BASE_URL . '/index.php?url=song/viewPlaylist/' . $playlistId);
        exit;
    }

    public function playlist() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
        require_once '../app/models/Database.php';
        require_once '../app/models/Playlist.php';
        $db = (new Database())->getConnection();
        $playlistModel = new Playlist($db);
        
        $playlists = $playlistModel->getByUser($_SESSION['user_id']);
        
        if (empty($playlists)) {
            $playlistModel->createDefault($_SESSION['user_id']);
            $playlists = $playlistModel->getByUser($_SESSION['user_id']);
        }
        
        require_once '../app/views/songs/playlists_index.php';
    }

    public function viewPlaylist($id) {
        require_once '../app/models/Database.php';
        require_once '../app/models/Playlist.php';
        require_once '../app/models/Song.php';
        $db = (new Database())->getConnection();
        $playlistModel = new Playlist($db);
        $songModel = new Song($db);
        
        $playlist = $playlistModel->getById($id);
        
        // Interní skladby (nahrané uživatelem přímo do DB)
        $internalSongs = $songModel->getAllByPlaylist($id);
        
        // Externí skladby (přidané z vyhledávání)
        require_once '../app/models/ExternalSong.php';
        $externalSongs = (new ExternalSong($db))->getByPlaylist($id);

        // Sjednotíme formát pro zobrazení
        $songs = array_merge(
            array_map(function($s) {
                return [
                    'id'         => $s['id'],
                    'title'      => $s['title'],
                    'artist'     => $s['artist'],
                    'album'      => $s['album'],
                    'genre'      => $s['genre'],
                    'duration'   => $s['duration'],
                    'images'     => $s['images'], // JSON
                    'audio_file' => $s['audio_file'],
                    'link'       => $s['link'],
                    'is_external' => false
                ];
            }, $internalSongs),
            array_map(function($s) {
                return [
                    'id'         => $s['id'],
                    'title'      => $s['title'],
                    'artist'     => $s['artist'],
                    'album'      => $s['album'] ?? 'Neznámé album',
                    'genre'      => 'Streaming',
                    'duration'   => '3:30', // Default pro externí
                    'images'     => json_encode([$s['image']]), // Převedeme na stejný formát
                    'audio_file' => null,
                    'link'       => $s['preview_url'],
                    'album_id'   => $s['album_id'],
                    'artist_id'  => $s['artist_id'],
                    'is_external' => true
                ];
            }, $externalSongs)
        );

        require_once '../app/views/songs/playlist.php';
    }

    protected function processAudioUpload() {
        $uploadDir = __DIR__ . '/../../public/audio/'; 
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (isset($_FILES['audio_file']) && $_FILES['audio_file']['name'] !== '' && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['audio_file']['tmp_name'];
            $fileExtension = strtolower(pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION));
            $newName = 'audio_' . uniqid() . '.' . $fileExtension;
            if (move_uploaded_file($tmpName, $uploadDir . $newName)) return $newName;
        }
        return null;
    }

    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION));
                    $newName = 'song_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) $uploadedFiles[] = $newName;
                }
            }
        }
        return $uploadedFiles;
    }

    protected function addErrorMessage($msg) {
        $_SESSION['error_message'] = $msg;
    }
}
