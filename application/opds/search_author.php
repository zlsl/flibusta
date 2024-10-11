<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
echo <<< _XML
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Поиск по авторам</title>
 <updated>2019-02-08T18:11:20+01:00</updated>
 <icon>/favicon.ico</icon>
 <link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
 <link href="/opds/search?by=author&amp;q={searchTerm}" rel="search" type="application/atom+xml" />
 <link href="/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />


<entry> <updated>2019-02-14T21:52:14+01:00</updated>
 <id>tag:search:author</id>
 <title>Поиск авторов</title>
 <content type="text">Поиск авторов по фамилии</content>
 <link href="/opds/search?by=author&amp;q={searchTerm}" type="application/atom+xml;profile=opds-catalog" />
</entry>
_XML;

$q = $_GET['q'];

if ($q == '') {
	die(':(');
}
$queryParam = $q . '%';
error_log($queryParam);
$authors = $dbh->prepare("SELECT *, 
		(SELECT COUNT(*) FROM libavtor, libbook WHERE 
		libbook.deleted='0' AND
		libbook.bookid=libavtor.bookid AND
		libavtor.AvtorId=libavtorname.avtorid AND
		libavtor.avtorId=libavtorname.avtorid) cnt
		FROM libavtorname
			LEFT JOIN libapics USING(avtorid)
		WHERE lastname ILIKE :q ORDER BY lastname, firstname");
		$authors->bindParam(":q", $queryParam);
		$authors->execute();
while ($a = $authors->fetch()) {
	echo "\n<entry> <updated>2019-02-08T21:53:29+01:00</updated>";
	if ($a->cnt > 0) {
		echo " <id>tag:author:$a->avtorid</id>";
		echo " <title>$a->lastname $a->middlename $a->firstname $a->nickname</title>";

		$stmt = $dbh->query("SELECT COUNT(*) as cnt FROM libbook,libavtor WHERE deleted='0' AND libavtor.bookid=libbook.bookid AND libavtor.avtorid=$a->avtorid");
		$stmt->execute();
		$books_cnt = $stmt->fetch()->cnt;
		$stmt = null;
		echo " <content type='text'>$books_cnt книг</content>";
	}
	echo '</entry>';
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