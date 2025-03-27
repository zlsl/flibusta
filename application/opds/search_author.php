<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Поиск по авторам</title>
 <updated>$cdt</updated>
 <icon>/favicon.ico</icon>
 <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
 <link href="$webroot/opds/authorsindex?letters={searchTerm}" rel="search" type="application/atom+xml" />
 <link href="$webroot/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />


<entry> <updated>$cdt</updated>
 <id>tag:search:author</id>
 <title>Поиск авторов</title>
 <content type="text">Поиск авторов по фамилии</content>
 <link href="$webroot/opds/authorsindex?letters={searchTerm}" type="application/atom+xml;profile=opds-catalog" />
</entry>
_XML;

$q = $_GET['q'];

if ($q == '') {
	die(':(');
}
$queryParam = $q . '%';
$authors = $dbh->prepare("SELECT *, 
		(SELECT COUNT(*) FROM libavtor, libbook WHERE 
		libbook.deleted='0' AND
		libbook.bookid=libavtor.bookid AND
		libavtor.avtorid=libavtorname.avtorid) cnt
		FROM libavtorname
		WHERE lastname ILIKE :q ORDER BY lastname, firstname");
		$authors->bindParam(":q", $queryParam);
		$authors->execute();
while ($a = $authors->fetch()) {
	if ($a->cnt > 0) {
		echo "\n<entry> <updated>$cdt</updated>";
		echo " <id>tag:author:$a->avtorid</id>";
		echo " <title>$a->lastname $a->firstname $a->middlename $a->nickname</title>";

		$stmt = $dbh->query("SELECT COUNT(*) as cnt FROM libbook,libavtor WHERE deleted='0' AND libavtor.bookid=libbook.bookid AND libavtor.avtorid=$a->avtorid");
		$stmt->execute();
		$books_cnt = $stmt->fetch()->cnt;
		$stmt = null;
		echo " <content type='text'>$books_cnt книг</content>";
		echo " <link href='$webroot/opds/author?author_id=$a->avtorid' type='application/atom+xml;profile=opds-catalog' />";
		echo '</entry>';
	}
}


/*
echo "<div class='mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid'>";
echo '<div class="mdl-grid">';
$seqs = DB::query("SELECT *,
	(SELECT COUNT(*) FROM libseq WHERE libseq.seqid=libseqname.SeqId) cnt
	FROM libseqname $filter4 ORDER BY seqname");

while ($s = $seqs->fetch_object()) {
	if ($s->cnt > 0) {
		echo "<div class='mdl-cell--4-col'>";

		echo "<span class='mdl-chip' onclick=\"location='/library/seq/$s->SeqId'\">";

		echo "<span class='mdl-chip__text'>$s->SeqName</span>";



		echo "<span class='mdl-chip__action'>$s->cnt</span>";

		echo "</span>";

		echo "</div>";
	}
}
*/
?>
</feed>