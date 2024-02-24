#!/bin/sh
echo "Создание индекса zip-файлов">>/application/sql/status
php /application/tools/app_update_zip_list.php

echo "">/application/sql/status


