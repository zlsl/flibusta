#!/bin/sh
rm FlibustaSQL/lib.a.attached.zip
rm FlibustaSQL/lib.b.attached.zip

wget --directory-prefix=FlibustaSQL -c -nc http://flibusta.is/sql/lib.a.attached.zip
wget --directory-prefix=FlibustaSQL -c -nc http://flibusta.is/sql/lib.b.attached.zip

