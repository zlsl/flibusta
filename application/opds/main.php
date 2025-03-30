<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="https://specs.opds.io/opds-1.2">
 <id>tag:root</id>
 <title>Домашняя библиотека</title>
 <icon>/favicon.ico</icon>
 <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
 <link href="$webroot/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="$webroot/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />
 <link href="$webroot/opds/" rel="self" type="application/atom+xml;profile=opds-catalog" />
<entry> 
 <updated>$cdt</updated>
 <id>tag:root:new</id>
 <title>Новинки</title>
 <content type="text">Последние поступления в библиотеку</content>
 <link href="$webroot/opds/list/" rel="http://opds-spec.org/sort/new" type="application/atom+xml;profile=opds-catalog" />
 <link href="$webroot/opds/list/" type="application/atom+xml;profile=opds-catalog" />
</entry>
<entry> 
 <updated>$cdt</updated>
 <id>tag:root:shelf</id>
 <title>Книжные полки</title>
 <content type="text">Избранное</content>
 <link href="$webroot/opds/favs/" type="application/atom+xml;profile=opds-catalog" />
</entry>
<entry> <updated>$cdt</updated>
 <id>tag:root:genre</id>
 <title>По жанрам</title>
 <content type="text">Поиск книг по жанрам</content>
 <link href="$webroot/opds/genres" type="application/atom+xml;profile=opds-catalog" />
</entry>
<entry> <updated>$cdt</updated>
 <id>tag:root:authors</id>
 <title>По авторам</title>
 <content type="text">Поиск книг по авторам</content>
 <link href="$webroot/opds/authorsindex" type="application/atom+xml;profile=opds-catalog" />
</entry>
<entry> <updated>$cdt</updated>
 <id>tag:root:sequences</id>
 <title>По сериям</title>
 <content type="text">Поиск книг по сериям</content>
 <link href="$webroot/opds/sequencesindex" type="application/atom+xml;profile=opds-catalog" />
</entry>
_XML 
?>
</feed>