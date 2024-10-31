#!/bin/sh
source /application/tools/dbinit.sh

mkdir -p /application/sql/psql
mkdir -p /application/cache/authors
mkdir -p /application/cache/covers
mkdir -p /application/cache/tmp

echo "Распаковка sql.gz">/application/sql/status
gzip -f -d /application/sql/*.gz

/application/tools/app_topg lib.a.annotations_pics.sql
/application/tools/app_topg lib.b.annotations_pics.sql
/application/tools/app_topg lib.a.annotations.sql
/application/tools/app_topg lib.b.annotations.sql
/application/tools/app_topg lib.libavtorname.sql
/application/tools/app_topg lib.libavtor.sql
/application/tools/app_topg lib.libbook.sql
/application/tools/app_topg lib.libfilename.sql
/application/tools/app_topg lib.libgenrelist.sql
/application/tools/app_topg lib.libgenre.sql
/application/tools/app_topg lib.libjoinedbooks.sql
/application/tools/app_topg lib.librate.sql
/application/tools/app_topg lib.librecs.sql
/application/tools/app_topg lib.libseqname.sql
/application/tools/app_topg lib.libseq.sql
/application/tools/app_topg lib.libtranslator.sql
/application/tools/app_topg lib.reviews.sql

echo "Подчистка БД. Стираем авторов, серии и жанры у которых нет ни одной книги" >>/application/sql/status
$SQL_CMD -f /application/tools/cleanup_db.sql

echo "Обновление полнотекстовых индексов">>/application/sql/status
$SQL_CMD -f /application/tools/update_vectors.sql

echo "Создание индекса zip-файлов">>/application/sql/status
php /application/tools/app_update_zip_list.php

echo "">/application/sql/status

