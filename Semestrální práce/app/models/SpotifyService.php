<?php
class SpotifyService {
    // SEM DOPLŇTE SVÉ ÚDAJE ZE SPOTIFY DEVELOPER DASHBOARD
    // 1. Jděte na https://developer.spotify.com/dashboard/
    // 2. Přihlaste se a vytvořte novou aplikaci (Create App)
    // 3. V nastavení aplikace najdete Client ID a Client Secret
    private $clientId = "a8e21339bfc54437b392ec67ffef1ca3";
    private $clientSecret = "64569a8a0b4d4021b37d45866e652d60";

    private static $accessToken = null;

    /**
     * Získá Access Token pomocí Client Credentials flow (s cachováním v SESSION)
     */
    private function getAccessToken() {
        // 1. Zkusíme statickou proměnnou (v rámci jednoho běhu PHP)
        if (self::$accessToken) return self::$accessToken;

        // 2. Zkusíme SESSION (aby se token nemusel tahat při každém kliknutí)
        if (isset($_SESSION['spotify_token']) && isset($_SESSION['spotify_token_exp']) && $_SESSION['spotify_token_exp'] > time()) {
            self::$accessToken = $_SESSION['spotify_token'];
            // Uvolníme zámek session, aby mohly dotazy běžet paralelně (důležité pro realtime search)
            session_write_close();
            return self::$accessToken;
        }
        
        // Kontrola, zda uživatel vyplnil klíče
        if (empty($this->clientId) || empty($this->clientSecret) || $this->clientId === "VAŠE_CLIENT_ID") {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (isset($data['access_token'])) {
            self::$accessToken = $data['access_token'];
            // Uložíme do session na 1 hodinu (Spotify tokeny platí 3600s)
            $_SESSION['spotify_token'] = $data['access_token'];
            $_SESSION['spotify_token_exp'] = time() + 3500; 
            session_write_close();
            return self::$accessToken;
        }

        return null;
    }

    /**
     * Vyhledá skladby na Spotify
     */
    public function search($query, $limit = 20) {
        $token = $this->getAccessToken();
        if (!$token) return [];

        $url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=" . $limit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        
        if (!isset($data['tracks']['items'])) return [];

        // Přemapování na náš formát
        return array_map(function($item) {
            return [
                'title' => $item['name'],
                'artist' => $item['artists'][0]['name'],
                'artistId' => $item['artists'][0]['id'],
                'album' => $item['album']['name'],
                'albumId' => $item['album']['id'],
                'image' => $item['album']['images'][0]['url'] ?? '',
                'previewUrl' => $item['preview_url'] ?? '',
                'spotifyId' => $item['id'],
                'isLocal' => false
            ];
        }, $data['tracks']['items']);
    }

    /**
     * Získá detail alba a jeho skladby
     */
    public function getAlbum($id) {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/albums/" . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (isset($data['error'])) return null;

        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'artist' => $data['artists'][0]['name'],
            'artistId' => $data['artists'][0]['id'],
            'image' => $data['images'][0]['url'] ?? '',
            'release_date' => $data['release_date'],
            'tracks' => array_map(function($t) use ($data) {
                return [
                    'title' => $t['name'],
                    'artist' => $t['artists'][0]['name'],
                    'artistId' => $t['artists'][0]['id'],
                    'album' => $data['name'],
                    'albumId' => $data['id'],
                    'image' => $data['images'][0]['url'] ?? '',
                    'previewUrl' => $t['preview_url'] ?? '',
                    'spotifyId' => $t['id'],
                    'isLocal' => false
                ];
            }, $data['tracks']['items'])
        ];
    }

    /**
     * Získá detail umělce
     */
    public function getArtist($id) {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/" . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (isset($data['error'])) return null;

        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'image' => $data['images'][0]['url'] ?? '',
            'genres' => $data['genres'] ?? [],
            'followers' => $data['followers']['total'] ?? 0
        ];
    }

    /**
     * Získá nejposlouchanější skladby umělce
     */
    public function getArtistTopTracks($id) {
        $token = $this->getAccessToken();
        if (!$token) return [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/" . $id . "/top-tracks?market=CZ");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['tracks'])) return [];

        return array_map(function($item) {
            return [
                'title' => $item['name'],
                'artist' => $item['artists'][0]['name'],
                'artistId' => $item['artists'][0]['id'],
                'album' => $item['album']['name'],
                'albumId' => $item['album']['id'],
                'image' => $item['album']['images'][0]['url'] ?? '',
                'previewUrl' => $item['preview_url'] ?? '',
                'spotifyId' => $item['id'],
                'isLocal' => false
            ];
        }, $data['tracks']);
    }

    /**
     * Získá alba umělce
     */
    public function getArtistAlbums($id) {
        $token = $this->getAccessToken();
        if (!$token) return [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/" . $id . "/albums?include_groups=album,single&limit=20");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['items'])) return [];

        return array_map(function($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'image' => $item['images'][0]['url'] ?? '',
                'release_date' => $item['release_date'],
                'type' => $item['album_type']
            ];
        }, $data['items']);
    }

    /**
     * Získá nejnovější vydaná alba
     */
    public function getNewReleases($limit = 20) {
        $token = $this->getAccessToken();
        if (!$token) return [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/browse/new-releases?limit=" . $limit);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['albums']['items'])) return [];

        return array_map(function($item) {
            return [
                'title' => $item['name'],
                'artist' => $item['artists'][0]['name'],
                'artistId' => $item['artists'][0]['id'],
                'album' => $item['name'],
                'albumId' => $item['id'],
                'image' => $item['images'][0]['url'] ?? '',
                'previewUrl' => '', 
                'spotifyId' => $item['id'],
                'isLocal' => false
            ];
        }, $data['albums']['items']);
    }

    /**
     * Získá trendy skladby (z globálního playlistu "Today's Top Hits")
     */
    public function getTrendyTracks($limit = 20) {
        $token = $this->getAccessToken();
        if (!$token) return [];

        // Playlist Today's Top Hits
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/playlists/37i9dQZF1DXcBWf9pws6fR/tracks?limit=" . $limit);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (!isset($data['items'])) return [];

        return array_map(function($item) {
            $t = $item['track'];
            return [
                'title' => $t['name'],
                'artist' => $t['artists'][0]['name'],
                'artistId' => $t['artists'][0]['id'],
                'album' => $t['album']['name'],
                'albumId' => $t['album']['id'],
                'image' => $t['album']['images'][0]['url'] ?? '',
                'previewUrl' => $t['preview_url'] ?? '',
                'spotifyId' => $t['id'],
                'isLocal' => false
            ];
        }, $data['items']);
    }
}
