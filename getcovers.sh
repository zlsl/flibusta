#!/bin/sh

#zipchkcdm='zip -T'
zipchkcmd='7z -bsp0 -bso0 t'

echo "Backup old zip files"
mv cache/lib.a.attached.zip cache/lib.a.attached.zip.old
mv cache/lib.b.attached.zip cache/lib.b.attached.zip.old

wget --directory-prefix=cache -c -nc https://flibusta.is/sql/lib.a.attached.zip
res=$?
if test "$res" != "0"; then
   echo "the wget command failed with: $res"
   echo "Restore lib.a.attached.zip"
   mv cache/lib.a.attached.zip.old cache/lib.a.attached.zip
fi
wget --directory-prefix=cache -c -nc https://flibusta.is/sql/lib.b.attached.zip
res=$?
if test "$res" != "0"; then
   echo "the wget command failed with: $res"
   echo "Restore lib.b.attached.zip"
   mv cache/lib.b.attached.zip.old cache/lib.b.attached.zip
fi


eval $zipchkcmd cache/lib.a.attached.zip
res=$?
if test "$res" == "0"; then
   echo "Remove backup lib.a.attached.zip"
   rm cache/lib.a.attached.zip.old
fi

eval $zipchkcmd cache/lib.b.attached.zip
res=$?
if test "$res" == "0"; then
   echo "Remove backup lib.b.attached.zip"
   rm cache/lib.b.attached.zip.old
fi

