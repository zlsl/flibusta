<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root</id>';
echo "<title>Жанры в ".$_GET['id']."</title>";
echo "<updated>$cdt</updated>";
echo  <<< _XML
 <icon>/favicon.ico</icon>
 <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
 <link href="$webroot/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="$webroot/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />

 _XML;

$gs = $dbh->prepare("SELECT *,
	(SELECT COUNT(*) FROM libgenre WHERE libgenre.genreid=g.genreid) cnt
	FROM libgenrelist g
	WHERE g.genremeta=:id");
$gs->bindParam(":id", $_GET['id']);
$gs->execute();

while ($g = $gs->fetch()) {
	echo "<entry> <updated>$cdt</updated>";
	echo " <id>tag:genre:$g->genrecode</id>";
	echo " <title>$g->genredesc</title>";
	echo " <content type='text'>Книг: $g->cnt</content>";
	echo " <link href='$webroot/opds/list/?genre_id=$g->genreid' type='application/atom+xml;profile=opds-catalog' />";
	echo "</entry>\n";
}
echo '</feed>';
?>

