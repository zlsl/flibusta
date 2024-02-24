<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog">
<id>tag:root:home</id>
<title>Книги по авторам</title>
<updated><?php echo $cdt; ?></updated>
<icon>/favicon.svg</icon>
<link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
<link href="/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
<link href="/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />
<link href="/opds/fav/?uuid=<?php echo $_GET['uuid']; ?>" rel="self" type="application/atom+xml;profile=opds-catalog" />
 <?php

$uuid = $_GET['uuid'];
$books = $dbh->prepare("SELECT DISTINCT b.*
		FROM fav f
		LEFT JOIN libbook b USING(bookid)
		WHERE user_uuid=:uuid AND f.bookid IS NOT NULL");
$books->bindParam(":uuid", $uuid);
$books->execute();

while ($b = $books->fetch()) {
	opds_book($b);
}

echo "</feed>";
