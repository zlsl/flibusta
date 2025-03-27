<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root</id>
 <title>Категории жанров</title>
 _XML;
echo  "<updated>$cdt</updated>";
echo <<< _XML
 <icon>/favicon.ico</icon>
 <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
 <link href="$webroot/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="$webroot/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />
 <link href="$webroot/opds/genres/" rel="self" type="application/atom+xml;profile=opds-catalog" />
_XML;
$gs = $dbh->prepare("SELECT DISTINCT(genremeta) genre FROM libgenrelist ORDER BY genre");
$gs->execute();

while ($g = $gs->fetch()) {
	echo "<entry> <updated>$cdt</updated>";
	echo " <id>tag:genre:" . urlencode($g->genre) . "</id>";
	echo " <title>$g->genre</title>";
	echo " <content type='text'></content>";
	echo " <link href='$webroot/opds/listgenres/?id=" . urlencode($g->genre) . "' type='application/atom+xml;profile=opds-catalog' />";
	echo "</entry>\n";
}
echo "</feed>";
?>

