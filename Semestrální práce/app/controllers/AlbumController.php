<?php
class AlbumController {
    public function show($id) {
        require_once '../app/models/SpotifyService.php';
        require_once '../app/models/iTunesSearch.php';

        $spotify = new SpotifyService();
        $album = $spotify->getAlbum($id);

        // Fallback na iTunes
        if (!$album) {
            $album = iTunesSearch::getAlbum($id);
        }

        if (!$album) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/songs/album_detail.php';
    }
}
