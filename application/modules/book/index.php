<?php
echo "<script>var url = '/usr.php?id=$url->var1';</script>";

function nl2p($string) {
    $paragraphs = '';

    foreach (explode("\n", $string) as $line) {
        if (trim($line)) {
            $paragraphs .= '<p>' . $line . '</p>';
        }
    }

    return $paragraphs;
}
book_info_pg($book, true);

echo "<div class='card card-body p-3'><ul>";

$stmt = $dbh->prepare("SELECT name, text FROM libreviews WHERE bookid=:id ORDER BY time");
$stmt->bindParam(":id", $url->var1);
$stmt->execute();

while ($r = $stmt->fetch()) {
	echo "<li><span class='badge bg-secondary'>$r->name</span> " . stripslashes($r->text) . "</li>";
}

echo "</ul></div>";
	

function str_replace_first($from, $to, $content) { 
    $from = '/'.preg_quote($from, '/').'/';
    return preg_replace($from, $to, $content, 1);
}


$ext = strtolower(trim($book->filetype));

if ($ext == 'fb2') {
	$stmt = $dbh->prepare("SELECT * FROM book_zip WHERE $url->var1 BETWEEN start_id AND end_id AND usr=0");
} else {
	$stmt = $dbh->prepare("SELECT * FROM book_zip WHERE $url->var1 BETWEEN start_id AND end_id AND usr=1");
}
$stmt->execute();
$zip_name = $stmt->fetch()->filename;
$zip = new ZipArchive(); 

echo "<div id='reader' class='reader'>";
if ($zip->open(ROOT_PATH . "flibusta/" . $zip_name)) {
	if ($ext == 'fb2') {
		include('fb.php');
	}

	if ($ext == 'txt') {
		include('txt.php');
	}

	if ($ext == 'epub') {
		include('epub.php');
	}

	if ($ext == 'pdf') {
		include('pdf.php');
	}

	if ($ext == 'mobi') {
		include('mobi.php');
	}

	if (($ext == 'djvu') || ($ext == 'djv')) {
		include('djvu.php');
	}

	if ($ext == 'rtf') {
		include('rtf.php');
	}

	if ($ext == 'docx') {
		include('docx.php');
	}

	if (($ext == 'html') || ($ext == 'htm')) {
		include('html.php');
	}

	$zip->close();
}


?>
</div>
