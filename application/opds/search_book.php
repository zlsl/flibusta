<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Поиск по книгам</title>
 <updated>$cdt</updated>
 <icon>/favicon.ico</icon>
 <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
 <link href="$webroot/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="$webroot/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />
_XML;

$q = trim($_GET['q'] ?? $_GET['searchTerm'] ?? $_GET['searchTerms'] ?? '');
$get = "?q=$q";

if ($q == '') {
	echo '</feed>';
	return;
}

$books = $dbh->prepare("SELECT DISTINCT libbook.BookId, libbook.Title as BookTitle,
        (SELECT Body FROM libbannotations WHERE BookId=libbook.BookId LIMIT 1) as Body
		FROM libbook
		LEFT JOIN libavtor ON libavtor.BookId=libbook.BookId
		LEFT JOIN libavtorname ON libavtorname.AvtorId=libavtor.AvtorId
		WHERE libbook.deleted='0' AND (
			libbook.Title ILIKE :contains_title OR
			libavtorname.LastName ILIKE :prefix_lastname OR
			libavtorname.FirstName ILIKE :prefix_firstname OR
			libavtorname.MiddleName ILIKE :prefix_middlename OR
			libavtorname.NickName ILIKE :contains_nickname
		)
		ORDER BY BookTitle
		LIMIT 100");
$contains = '%'.$q.'%';
$prefix = $q.'%';
$books->bindParam(":contains_title", $contains);
$books->bindParam(":prefix_lastname", $prefix);
$books->bindParam(":prefix_firstname", $prefix);
$books->bindParam(":prefix_middlename", $prefix);
$books->bindParam(":contains_nickname", $contains);
$books->execute();

while ($b = $books->fetchObject()) {
	echo " <entry> <updated>$cdt</updated>";
	echo " <id>tag:book:$b->bookid</id>";
	echo " <title>" . htmlspecialchars($b->booktitle) . "</title>";

	$as = '';
	$authors = $dbh->query("SELECT lastname, firstname, middlename FROM libavtorname, libavtor WHERE libavtor.BookId=$b->bookid AND libavtor.AvtorId=libavtorname.AvtorId ORDER BY LastName");
	while ($a = $authors->fetchObject()) {
		$as .= $a->lastname . " " . $a->firstname . " " . $a->middlename . ", ";
	}
	$authors = null;

	echo "<author> <name>" . htmlspecialchars($as) . "</name>";
	echo " <uri>/a/id</uri>";
	echo "</author>";
	echo " <content type='text/html'>" . htmlspecialchars($b->body ?? '') . "</content>";

	echo "<link rel='http://opds-spec.org/image/thumbnail' href='$webroot/extract_cover.php?id=$b->bookid' type='image/jpeg'/>";
	echo "<link rel='http://opds-spec.org/image' href='$webroot/extract_cover.php?id=$b->bookid' type='image/jpeg'/>";
	echo " <link href='$webroot/fb2.php?id=$b->bookid' rel='http://opds-spec.org/acquisition/open-access' type='application/fb2+zip' />";

	echo "</entry>\n";
}
$books = null;
?>
</feed>
