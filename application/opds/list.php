<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';

$filter = "deleted='0' ";
$join = '';

$title = 'в новинках';

if (isset($_GET['genre_id'])) {
	$gid = intval($_GET['genre_id']);
	$filter .= 'AND genreid=:gid ';
	$join .= 'LEFT JOIN libgenre g USING(BookId) ';
	$stmt = $dbh->prepare("SELECT * FROM libgenrelist
		WHERE genreid=:gid");
	$stmt->bindParam(":gid", $gid);
	$stmt->execute();
	$g = $stmt->fetch();
	$title = "в $g->genremeta: $g->genredesc";
}

if (isset($_GET['seq_id'])) {
	$sid = intval($_GET['seq_id']);
	$filter .= 'AND seqid=:sid';
	$join .= 'LEFT JOIN libseq s USING(BookId) ';
	$stmt = $dbh->prepare("SELECT * FROM libseqname
		WHERE seqid=:sid");
	$stmt->bindParam(":sid", $sid);
	$stmt->execute();
	$s = $stmt->fetch();
	$title = "в Сборник: $s->seqname";
}

if (isset($_GET['author_id'])) {
	$aid = intval($_GET['author_id']);
	$filter .= 'AND avtorid=:aid ';
	$join .= 'JOIN libavtor USING (bookid) JOIN libavtorname USING (avtorid) ';
	$stmt = $dbh->prepare("SELECT * FROM libavtorname WHERE avtorid=:aid");
	$stmt->bindParam(":aid", $aid);
	$stmt->execute();
	$a = $stmt->fetch();
	$title = "$a->lastname $a->firstname $a->middlename -- $a->nickname";
}

echo <<< _XML
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog">
<id>tag:root:home</id>
_XML;
echo "<title>Книги $title</title>";
echo "<updated>$cdt</updated>";
echo <<< _XML
<icon>/favicon.ico</icon>
<link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
<link href="/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
<link href="/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />\n
_XML;

$books = $dbh->prepare("SELECT *
	FROM libbook b
	$join
	WHERE
	$filter
	ORDER BY time DESC
	LIMIT ". OPDS_FEED_COUNT);

if (isset($_GET['genre_id'])) {
	$books->bindParam(":gid", $gid);
}

if (isset($_GET['seq_id'])) {
	$books->bindParam(":sid", $sid);
}

if (isset($_GET['author_id'])) {
	$books->bindParam(":aid", $aid);
}

$books->execute();

while ($b = $books->fetch()) {
	opds_book($b);
}

echo "</feed>";
?>