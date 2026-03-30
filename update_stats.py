import requests
import json
import os

# Pobieranie danych z sekretów GitHub
API_KEY = os.getenv('YT_API_KEY')
CHANNEL_ID = os.getenv('CHANNEL_ID')

def update_data():
    # 1. Pobierz statystyki kanału
    stats_url = f"https://www.googleapis.com/youtube/v3/channels?part=statistics&id={CHANNEL_ID}&key={API_KEY}"
    stats_data = requests.get(stats_url).json()
    
    # 2. Pobierz najnowszy film
    video_url = f"https://www.googleapis.com/youtube/v3/search?key={API_KEY}&channelId={CHANNEL_ID}&part=id&order=date&maxResults=1&type=video"
    video_data = requests.get(video_url).json()

    if 'items' in stats_data and 'items' in video_data:
        s = stats_data['items'][0]['statistics']
        v_id = video_data['items'][0]['id']['videoId']

        new_data = {
            "subs": s['subscriberCount'],
            "views": s['viewCount'],
            "videos": s['videoCount'],
            "videoId": v_id
        }

        # Zapisz do pliku data.json
        with open('data.json', 'w') as f:
            json.dump(new_data, f, indent=4)
        print("Dane zaktualizowane pomyślnie!")

if __name__ == "__main__":
    update_data()