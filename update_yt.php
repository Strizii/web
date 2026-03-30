<?php
// Twoje dane konfiguracyjne
$apiKey = 'TWÓJ_KLUCZ_API';
$channelId = 'TWÓJ_ID_KANAŁU';
$jsonFile = 'data.json';

// 1. Pobieranie statystyk kanału (Suby, Wyświetlenia, Filmy) - Koszt: 1 jednostka
$statsUrl = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$channelId&key=$apiKey";
$statsResponse = file_get_contents($statsUrl);
$statsData = json_decode($statsResponse, true);

// 2. Pobieranie najnowszego filmu - Koszt: 100 jednostek
$videoUrl = "https://www.googleapis.com/youtube/v3/search?key=$apiKey&channelId=$channelId&part=id&order=date&maxResults=1&type=video";
$videoResponse = file_get_contents($videoUrl);
$videoData = json_decode($videoResponse, true);

// Sprawdzenie czy dane dotarły poprawnie
if (isset($statsData['items'][0]) && isset($videoData['items'][0])) {
    $s = $statsData['items'][0]['statistics'];
    
    $output = [
        'subs' => $s['subscriberCount'],
        'views' => $s['viewCount'],
        'videos' => $s['videoCount'],
        'videoId' => $videoData['items'][0]['id']['videoId'],
        'lastUpdated' => date('Y-m-d H:i:s')
    ];

    // Zapisz do pliku data.json
    file_put_contents($jsonFile, json_encode($output));
    echo "Sukces: Dane zostały zaktualizowane w pliku JSON.";
} else {
    echo "Błąd: Nie udało się pobrać danych z API.";
}
?>