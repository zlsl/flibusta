#!/bin/sh
mkdir /application/sql/psql
mkdir /application/cache/authors
mkdir /application/cache/covers

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

echo "Обновление полнотекстовых индексов">>/application/sql/status
PGPASSWORD=flibusta psql -h postgres  -d flibusta -U flibusta -f /application/tools/update_vectors.sql

echo "Создание индекса zip-файлов">>/application/sql/status
php /application/tools/app_update_zip_list.php

echo "">/application/sql/status

