<?xml version="1.0" encoding="utf-8"?>
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Книги по авторам</title>
 <updated>2019-02-08T18:11:20+01:00</updated>
 <icon>/favicon.ico</icon>
 <link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
 <link href="/search/author?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="/" rel="start" type="application/atom+xml;profile=opds-catalog" />


<entry> <updated>2019-02-14T21:52:14+01:00</updated>
 <id>tag:search:author</id>
 <title>Поиск авторов</title>
 <content type="text">Поиск авторов по фамилии</content>
 <link href="/search/author?q=authors&amp;searchTerm=" type="application/atom+xml;profile=opds-catalog" />
</entry>

 <?php
$q = $_GET['q'];
$get = "?q=$q";

if ($q == '') {
	die(':(');
}

$filter2 = "AND libbook.Title LIKE " . DB::es('%' . $q . '%');
$filter3 = "WHERE LastName LIKE " . DB::es('%' . $q . '%');
$filter4 = "WHERE SeqName LIKE " . DB::es('%' . $q . '%');


$authors = DB::query("SELECT *, 
		(SELECT COUNT(*) FROM libavtor, libbook WHERE 
		libbook.deleted=0 AND
		libbook.BookId=libavtor.BookId AND
		libavtor.AvtorId=libavtorname.AvtorId AND
		libavtor.avtorId=libavtorname.AvtorId) cnt 
		FROM libavtorname
			LEFT JOIN libapics USING(AvtorId)

		$filter3 ORDER BY LastName, FirstName");

while ($a = $authors->fetch_object()) {
	echo " <entry> <updated>2019-02-08T21:53:29+01:00</updated>";
	if ($a->cnt > 0) {
		echo " <id>tag:author:$a->AvtorId</id>";
		echo " <title>$a->LastName $a->MiddleName $a->FirstName $a->NickName</title>";

		$books_cnt = DB::query("SELECT COUNT(*) cnt FROM libbook,libavtor WHERE deleted=0 AND libavtor.BookId=libbook.BookId AND libavtor.AvtorId=$a->AvtorId")->fetch_object()->cnt;

		echo " <content type='text'>$books_cnt книга</content>";
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