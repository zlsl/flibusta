<?xml version="1.0" encoding="utf-8"?>
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root</id>
 <title>Категории жанров</title>
 <updated><?php echo $cdt; ?></updated>
 <icon>/favicon.svg</icon>
 <link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
 <link href="/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />
 <link href="/opds/genres/" rel="self" type="application/atom+xml;profile=opds-catalog" />

<?php

$gs = $dbh->prepare("SELECT DISTINCT(genremeta) genre FROM libgenrelist ORDER BY genre");
$gs->execute();

while ($g = $gs->fetch()) {
	echo "<entry> <updated>$cdt</updated>";
	echo " <id>tag:genre:" . urlencode($g->genre) . "</id>";
	echo " <title>$g->genre</title>";
	echo " <content type='text'></content>";
	echo " <link href='/opds/listgenres/?id=" . urlencode($g->genre) . "' type='application/atom+xml;profile=opds-catalog' />";
	echo "</entry>\n";
}
?>
</feed>
