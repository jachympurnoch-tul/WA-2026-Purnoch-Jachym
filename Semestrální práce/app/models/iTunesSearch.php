<?php
class iTunesSearch {
    public static function search($query, $limit = 20) {
        $url = "https://itunes.apple.com/search?term=" . urlencode($query) . "&media=music&entity=song&limit=" . $limit;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['results'])) return [];

        return array_map(function($item) {
            return [
                'title' => $item['trackName'] ?? '',
                'artist' => $item['artistName'] ?? '',
                'artistId' => $item['artistId'] ?? '',
                'album' => $item['collectionName'] ?? '',
                'albumId' => $item['collectionId'] ?? '',
                'image' => isset($item['artworkUrl100']) ? str_replace('100x100bb', '400x400bb', $item['artworkUrl100']) : '',
                'previewUrl' => $item['previewUrl'] ?? '',
                'isLocal' => false
            ];
        }, $data['results']);
    }

    public static function getAlbum($id) {
        $url = "https://itunes.apple.com/lookup?id=" . $id . "&entity=song";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['results']) || count($data['results']) === 0) return null;

        $album = $data['results'][0];
        $tracks = array_slice($data['results'], 1);

        return [
            'id' => $album['collectionId'],
            'name' => $album['collectionName'],
            'artist' => $album['artistName'],
            'artistId' => $album['artistId'],
            'image' => isset($album['artworkUrl100']) ? str_replace('100x100bb', '600x600bb', $album['artworkUrl100']) : '',
            'release_date' => $album['releaseDate'] ?? '',
            'tracks' => array_map(function($t) {
                return [
                    'title' => $t['trackName'],
                    'artist' => $t['artistName'],
                    'artistId' => $t['artistId'],
                    'album' => $t['collectionName'],
                    'albumId' => $t['collectionId'],
                    'image' => isset($t['artworkUrl100']) ? str_replace('100x100bb', '400x400bb', $t['artworkUrl100']) : '',
                    'previewUrl' => $t['previewUrl'] ?? '',
                    'isLocal' => false
                ];
            }, $tracks)
        ];
    }

    public static function getArtist($id) {
        $url = "https://itunes.apple.com/lookup?id=" . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['results']) || count($data['results']) === 0) return null;

        $artist = $data['results'][0];
        return [
            'id' => $artist['artistId'],
            'name' => $artist['artistName'],
            'image' => 'https://via.placeholder.com/600x600?text=' . urlencode($artist['artistName']),
            'genres' => [$artist['primaryGenreName'] ?? ''],
            'followers' => 0
        ];
    }

    public static function getArtistTopTracks($id) {
        $url = "https://itunes.apple.com/lookup?id=" . $id . "&entity=song&limit=10";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['results'])) return [];

        return array_map(function($item) {
            return [
                'title' => $item['trackName'] ?? '',
                'artist' => $item['artistName'] ?? '',
                'artistId' => $item['artistId'] ?? '',
                'album' => $item['collectionName'] ?? '',
                'albumId' => $item['collectionId'] ?? '',
                'image' => isset($item['artworkUrl100']) ? str_replace('100x100bb', '400x400bb', $item['artworkUrl100']) : '',
                'previewUrl' => $item['previewUrl'] ?? '',
                'isLocal' => false
            ];
        }, array_slice($data['results'], 1));
    }

    public static function getArtistAlbums($id) {
        $url = "https://itunes.apple.com/lookup?id=" . $id . "&entity=album&limit=20";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['results'])) return [];

        return array_map(function($item) {
            return [
                'id' => $item['collectionId'],
                'name' => $item['collectionName'],
                'image' => isset($item['artworkUrl100']) ? str_replace('100x100bb', '400x400bb', $item['artworkUrl100']) : '',
                'release_date' => $item['releaseDate'] ?? '',
                'type' => 'album'
            ];
        }, array_slice($data['results'], 1));
    }
}
