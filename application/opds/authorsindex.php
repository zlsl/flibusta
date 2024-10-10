<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Книги по авторам</title>
 <updated>2019-02-08T18:11:20+01:00</updated>
 <icon>/favicon.ico</icon>
 <link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
 <link href="/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />
_XML;

$ai = $dbh->query("SELECT SUBSTR(LastName, 1, 1) alpha, COUNT(*) cnt FROM libavtorname GROUP BY SUBSTR(LastName, 1, 1) ORDER BY SUBSTR(LastName, 1, 1)");
while ($ach = $ai->fetchObject()) {
	echo "<entry> <updated>2019-02-08T18:11:21+01:00</updated>";
	echo "<id>tag:authors:$ach->alpha</id>";
	echo "<title>$ach->alpha</title>";
	echo "<content type='text'>$ach->cnt авторов на $ach->alpha</content>";
	echo "<link href='/opds/search/author?q=$ach->alpha' type='application/atom+xml;profile=opds-catalog' />";
	echo "</entry>";
}
echo '</feed>';
?>