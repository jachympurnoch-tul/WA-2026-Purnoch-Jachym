<?php
class ArtistController {
    public function show($id) {
        require_once '../app/models/SpotifyService.php';
        require_once '../app/models/iTunesSearch.php';

        $spotify = new SpotifyService();
        $artist = $spotify->getArtist($id);
        
        if ($artist) {
            $topTracks = $spotify->getArtistTopTracks($id);
            $albums = $spotify->getArtistAlbums($id);
        } else {
            // Fallback na iTunes
            $artist = iTunesSearch::getArtist($id);
            if ($artist) {
                $topTracks = iTunesSearch::getArtistTopTracks($id);
                $albums = iTunesSearch::getArtistAlbums($id);
            }
        }

        if (!$artist) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/songs/artist_profile.php';
    }
}
