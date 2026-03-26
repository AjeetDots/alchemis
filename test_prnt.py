import urllib.request
import re

req = urllib.request.Request('https://prnt.sc/iM17N11p1pNf', headers={'User-Agent': 'Mozilla/5.0'})
try:
    html = urllib.request.urlopen(req).read().decode('utf-8')
    match = re.search(r'<img.*?id="screenshot-image".*?src="(.*?)"', html)
    if match:
        print(match.group(1))
    else:
        print("Image URL not found in HTML.")
except Exception as e:
    print(f"Error: {e}")
