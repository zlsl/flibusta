<?php
header('Content-Type: application/atom+xml; charset=utf-8');

$letters = $_GET['letters'] ?? '';

if ($letters !== '') {
    $length_letters = mb_strlen($letters, 'UTF-8');
} else {
    $length_letters = 0; // Установите подходящее значение по умолчанию
}

echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Книги по авторам</title>
 <updated>$cdt</updated>
 <icon>/favicon.ico</icon>
 <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
 <link href="$webroot/opds/authorsindex?letters={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="$webroot/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />\n
_XML;

$query="
	SELECT UPPER(SUBSTR(LastName, 1, ".($length_letters + 1).")) as alpha, COUNT(*) as cnt
	FROM libavtorname
	WHERE UPPER(SUBSTR(LastName, 1, ".($length_letters + 1).")) SIMILAR TO '".$letters."[A-ZА-Я]'
	GROUP BY UPPER(SUBSTR(LastName, 1, ".($length_letters + 1)."))
	ORDER BY alpha";
$ai = $dbh->query($query);
while ($ach = $ai->fetchObject()) {
	echo "\n<entry> <updated>$cdt</updated>";
	echo "<id>tag:authors:$ach->alpha</id>";
	echo "<title>$ach->alpha</title>";
	echo "<content type='text'>$ach->cnt авторов на $ach->alpha</content>";
	if ($ach->cnt>500) {
		$url="$webroot/opds/authorsindex?letters=$ach->alpha";
	} else {
		$url="$webroot/opds/search?by=author&amp;q=$ach->alpha";
	}
	echo "<link href='$url' type='application/atom+xml;profile=opds-catalog' />";
	echo "</entry>";
}
echo '</feed>';
?>