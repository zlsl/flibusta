<?xml version="1.0" encoding="utf-8"?>
 <feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="http://opds-spec.org/2010/catalog"> <id>tag:root:authors</id>
 <title>Книги по авторам</title>
 <updated>2019-02-08T18:11:20+01:00</updated>
 <icon>/favicon.ico</icon>
 <link href="/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
 <link href="/search?q={searchTerms}" rel="search" type="application/atom+xml" />
 <link href="/" rel="start" type="application/atom+xml;profile=opds-catalog" />
 <?php
$q = $_GET['q'];
$get = "?q=$q";

if ($q == '') {
	die(':(');
}

$filter2 = "AND libbook.Title LIKE " . DB::es('%' . $q . '%');

$books = DB::query("SELECT DISTINCT *, libbook.Title BookTitle,
        (SELECT Body FROM libbannotations WHERE BookId=libbook.BookId LIMIT 1) Body
		FROM libbook 
		JOIN libgenre USING(BookId) 
		WHERE deleted=0 $filter2
		GROUP BY BookId
		LIMIT 100");


while ($b = $books->fetch_object()) {
	echo " <entry> <updated>2019-02-08T21:53:29+01:00</updated>";
	echo " <id>tag:book:$b->BookId</id>";
	echo " <title>" . htmlspecialchars($b->BookTitle) . "</title>";

	$as = '';
	$authors = DB::query("SELECT libavtorname.* FROM libavtorname, libavtor WHERE libavtor.BookId=$b->BookId AND libavtor.AvtorId=libavtorname.AvtorId ORDER BY LastName");
	while ($a = $authors->fetch_object()) {
		$as .= $a->LastName . " " . $a->FirstName . " " . $a->MiddleName . ", ";
	}

	echo "<author> <name>$as</name>";
	echo " <uri>/a/id</uri>";
	echo "</author>";
	echo " <content type='text/html'>" . htmlspecialchars($b->Body) . "</content>";

	echo "<link rel='http://opds-spec.org/image/thumbnail' href='http://192.168.32.5/lib/get_cover.php?id=$b->BookId' type='image/jpeg'/>";
	echo "<link rel='http://opds-spec.org/image' href='http://192.168.32.5/lib/get_cover.php?id=$b->BookId' type='image/jpeg'/>";
	echo " <link href='http://192.168.32.5/lib/get_fb2.php?id=$b->BookId' rel='http://opds-spec.org/acquisition/open-access' type='application/fb2+zip' />";

	echo "</entry>\n";
}
?>
</feed>