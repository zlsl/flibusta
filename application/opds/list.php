<?xml version="1.0" encoding="utf-8"?>
<?php
$filter = "deleted='0' ";
$join = '';

$title = 'новинках';

if (isset($_GET['genre_id'])) {
	$gid = intval($_GET['genre_id']);
	$filter .= 'AND genreid=:gid ';
	$join .= 'LEFT JOIN libgenre g USING(BookId) ';
	$stmt = $dbh->prepare("SELECT * FROM libgenrelist
		WHERE genreid=:gid");
	$stmt->bindParam(":gid", $gid);
	$stmt->execute();
	$g = $stmt->fetch();
	$title = "$g->genremeta: $g->genredesc";
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
	$title = "Сборник: $s->seqname";
}

?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog">
<id>tag:root:home</id>
<title>Книги в <?php echo $title ?></title>
<updated><?php echo $cdt; ?></updated>
<icon>/favicon.svg</icon>
<link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
<link href="/opds/search?q={searchTerms}" rel="search" type="application/atom+xml" />
<link href="/opds/" rel="start" type="application/atom+xml;profile=opds-catalog" />

<?php
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
$books->execute();

while ($b = $books->fetch()) {
	opds_book($b);
}

echo "</feed>";
