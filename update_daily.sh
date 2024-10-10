#!/bin/sh
URL="http://flibusta.is/daily/"
DEST_DIR="./Flibusta.Net"
mkdir -p "$DEST_DIR"
curl -s "$URL" > page.html
grep -Eo 'href="f\.(fb2|n)\.[0-9\-]+\.zip"' page.html | sed 's/href="//;s/"//' > links.txt

while IFS= read -r file; do
    wget -c -P "$DEST_DIR" "$URL$file"
done < links.txt

rm page.html links.txt
