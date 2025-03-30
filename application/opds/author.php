<?php
header('Content-Type: application/atom+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';echo "\n";
echo '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/terms/" xmlns:os="http://a9.com/-/spec/opensearch/1.1/" xmlns:opds="https://specs.opds.io/opds-1.2">';

$author_id = $_GET['author_id'];
if ($author_id == '')
    die('author.php called without specifying id');

$seq_mode = isset($_GET['seq']);
if (! $seq_mode)  {  
    $stmt = $dbh->prepare("SELECT a.LastName as LastName, a.MiddleName as MiddleName, a.FirstName as FirstName, a.NickName as NickName,
        aa.Body as Body,  p.File as picFile 
        from libavtorname a 
        LEFT JOIN libaannotations aa on a.avtorid = aa.avtorid
        LEFT JOIN libapics p on a.avtorid=p.avtorid
        where a.avtorID=:authorid ");
} else {
    $stmt = $dbh->prepare("SELECT LastName, MiddleName, FirstName, NickName from libavtorname where avtorID=:authorid ");
}

$stmt->bindParam(':authorid', $author_id);
$stmt->execute();
if ($a = $stmt->fetchObject()){
    $author_name = ($a->nickname !='')?"$a->firstname $a->middlename $a->lastname ($a->nickname)"
                            :"$a->firstname  $a->middlename $a->lastname";
   
    if ($seq_mode) { // show list of sequences with current author's works
       
        echo <<<_XML
        <id>tag:author:$author_id:sequences</id>
        <title>$author_name : Книги по сериям</title>
        <updated>$cdt</updated>
        <icon>/favicon.ico</icon>
        <link href="$webroot/opds-opensearch.xml.php" rel="search" type="application/opensearchdescription+xml" />
        <link href="$webroot/opds/search?by=author&searchTerm={searchTerms}" rel="search" type="application/atom+xml" />
        <link href="$webroot/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />
        _XML;
        $sequences = $dbh->prepare("SELECT distinct sn.seqid seqid, sn.seqname seqname
        from libseqname sn, libseq s, libavtor a 
        where sn.seqid = s.seqid and s.bookId= a.bookId and a.avtorId= :aid");
        $sequences->bindParam(":aid", $author_id);
        $sequences->execute();
        while($seq = $sequences->fetchObject()){
            echo "<entry>\n";
            echo "<updated>$cdt</updated>\n";
            echo "<title>"  . htmlspecialchars($seq->seqname ?? '') . "</title>\n";
            echo "<link href='$webroot/opds/list?seq_id=$seq->seqid'  type='application/atom+xml;profile=opds-catalog' />\n";
            echo "</entry>\n";
        }
        $sequences = null;
    } else {
        echo <<<_XML
        <id>tag:author:$author_id</id>
        <title>$author_name</title>
        <updated>$cdt</updated>
        <icon>/favicon.ico</icon>
        <link href="$webroot/opds-opensearch.xml" rel="search" type="application/opensearchdescription+xml" />
        <link href="$webroot/opds/search?by=author&amp;searchTerm={searchTerms}" rel="search" type="application/atom+xml" />
        <link href="$webroot/opds" rel="start" type="application/atom+xml;profile=opds-catalog" />

        _XML;
        if ($a->body != '') {
            echo <<<_XML
            <entry>
            <updated>$cdt</updated>
            <id>tag:author:bio:$author_id</id>
            <title>Об авторе</title>
            _XML;
            if (!is_null($a->picfile)){
                echo <<<_XML
                    <link href="$webroot/extract_author.php?id=$author_id" rel="http://opds-spec.org/image" type="image/jpeg" />
                    <link href="$webroot/extract_author.php?id=$author_id" rel="x-stanza-cover-image" type="image/jpeg" />
                    <link href="$webroot/extract_author.php?id=$author_id" rel="http://opds-spec.org/image/thumbnail" type="image/jpeg" />
                    <link href="$webroot/extract_author.php?id=$author_id" rel="x-stanza-cover-image-thumbnail" type="image/jpeg" /> 

                _XML;
            }
            //echo '<content type = "text/html">';
            echo '<content type="text/html">';
           /* if (!is_null($a->picfile)){
                echo "&lt;img src=&quot;/extract_author.php?id=$author_id&quot; align=left style=&quot;border:5px solid #ededed; margin: 12px;&quot;&gt;&lt;p&gt;\n";
            }*/
            echo $a->body;
            echo "\n";
            echo <<< _XML
                </content>
                <link href="$webroot/author/view/$author_id" rel="alternate" type="text/html" title="Страница автора на сайте" />
                <link href="$webroot/author/view/$author_id" rel="http://opds-spec.org/acquisition" type="text/html" title="Страница автора на сайте" />
                <link href="$webroot/opds/list?author_id=$author_id&amp;display_type=alphabet" rel="http://www.feedbooks.com/opds/facet" type="application/atom+xml;profile=opds-catalog" title="Книги автора по алфавиту" />
                <link href="$webroot/opds/author?author_id=$author_id&amp;seq=1" rel="http://www.feedbooks.com/opds/facet" type="application/atom+xml;profile=opds-catalog" title="Книжные серии с произведениями автора" />
                <link href="$webroot/opds/list?author_id=$author_id&amp;display_type=sequenceless" rel="http://www.feedbooks.com/opds/facet" type="application/atom+xml;profile=opds-catalog" title="Книги автора вне серий" />
            </entry>
            _XML;
        }
        echo <<< _XML
        <entry>
        <updated>$cdt</updated>
        <title>Все книги автора (без сортировки)</title>
        <id>tag:author:$author_id:list</id>
        <link href="$webroot/opds/list?author_id=$author_id" type="application/atom+xml;profile=opds-catalog" />
        </entry>
        <entry>
        <updated>$cdt</updated>
        <title>Книги автора по алфавиту</title>
        <id>tag:author:$author_id:alphabet</id>
        <link href="$webroot/opds/list?author_id=$author_id&amp;display_type=alphabet" type="application/atom+xml;profile=opds-catalog" />
        </entry>
        <entry>
        <updated>$cdt</updated>
        <title>Книги автора по году издания</title>
        <id>tag:author:$author_id:year</id>
        <link href="$webroot/opds/list?author_id=$author_id&amp;display_type=year" type="application/atom+xml;profile=opds-catalog" />
        </entry>
        <entry>
        <updated>$cdt</updated>
        <title>Книжные серии с произведениями автора</title>
        <id>tag:author:$author_id:sequences</id>
        <link href="$webroot/opds/author?author_id=$author_id&amp;seq=1" type="application/atom+xml;profile=opds-catalog" />
        </entry>
        <entry>
        <updated>$cdt</updated>
        <title>Произведения  вне серий</title>
        <id>tag:author:$author_id:sequenceless</id>
        <link href="$webroot/opds/list?author_id=$author_id&amp;display_type=sequenceless" type="application/atom+xml;profile=opds-catalog" />
        </entry>
        _XML;
    } 
}
else 
    die("author with id $author_id not found in the data base");
$stmt = null;
?>
</feed>
