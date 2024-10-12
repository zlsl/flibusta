<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Книги по авторам</title>
 <updated>$cdt</updated>
 <icon>/favicon.ico</icon>
 <link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
 <link href="/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />\n
_XML;

$ai = $dbh->query("SELECT UPPER(SUBSTR(LastName, 1, 1)) as alpha, COUNT(*) as cnt FROM libavtorname WHERE SUBSTR(LastName, 1, 1) SIMILAR TO '[A-Za-zА-Яа-я]' GROUP BY UPPER(SUBSTR(LastName, 1, 1)) ORDER BY alpha");
while ($ach = $ai->fetchObject()) {
	echo "\n<entry> <updated>$cdt</updated>";
	echo "<id>tag:authors:$ach->alpha</id>";
	echo "<title>$ach->alpha</title>";
	echo "<content type='text'>$ach->cnt авторов на $ach->alpha</content>";
	echo "<link href='/opds/search?by=author&amp;q=$ach->alpha' type='application/atom+xml;profile=opds-catalog' />";
	echo "</entry>";
}
echo '</feed>';
?>