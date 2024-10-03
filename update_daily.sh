#!/bin/sh

# Определение переменных
URL="http://flibusta.is/daily/"
DEST_DIR="./Flibusta.Net"

# Создаем директорию для сохранения файлов, если её не существует
mkdir -p "$DEST_DIR"

# Загрузка HTML страницы
curl -s "$URL" > page.html

# Извлечение всех ссылок на .zip файлы
# Здесь мы ищем ссылки вида "href="f.fb2... или f.n...."
grep -Eo 'href="f\.(fb2)\.[0-9\-]+\.zip"' page.html | sed 's/href="//;s/"//' > links.txt

# Загрузка каждого найденного файла
while IFS= read -r file; do
    # Загружаем файл с помощью wget
    wget -c -P "$DEST_DIR" "$URL$file"
done < links.txt

# Удаление временных файлов
rm page.html links.txt
