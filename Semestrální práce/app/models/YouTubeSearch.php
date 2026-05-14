<?php
class YouTubeSearch {
    public static function getFirstVideoId($query) {
        $query = $query . " audio";
        $url = "https://www.youtube.com/results?search_query=" . urlencode($query);
        
        // Použijeme cURL, který je rychlejší než file_get_contents
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout 5 sekund
        $html = curl_exec($ch);
        curl_close($ch);

        if (!$html) return null;

        // Bleskové hledání Video ID v JSONu YouTube
        if (preg_match('/"videoRenderer":\{"videoId":"([^"]+)"/', $html, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
