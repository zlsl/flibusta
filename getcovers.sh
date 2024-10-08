#!/bin/sh
rm cache/lib.a.attached.zip
rm cache/lib.b.attached.zip

wget --directory-prefix=cache -c -nc http://flibusta.is/sql/lib.a.attached.zip
wget --directory-prefix=cache -c -nc http://flibusta.is/sql/lib.b.attached.zip

