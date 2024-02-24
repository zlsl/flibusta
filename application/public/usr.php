<?php
if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	die();
}
error_reporting(E_ALL);
include('../init.php');

$stmt = $dbh->prepare("SELECT libbook.Title BookTitle, libfilename.filename, libbook.filetype,
	CONCAT(libavtorname.LastName, ' ', libavtorname.FirstName) author_name
		FROM libbook 
		LEFT JOIN libavtor USING(BookId) 
		LEFT JOIN libfilename USING(BookId) 
		LEFT JOIN libavtorname USING(AvtorId) 
		WHERE libbook.BookId=:id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$book = $stmt->fetch();


$stmt = $dbh->prepare("SELECT * FROM book_zip WHERE $id BETWEEN start_id AND end_id AND usr=1");
$stmt->execute();
$zip_name = $stmt->fetch()->filename;
$zip = new ZipArchive();

if ($zip->open(ROOT_PATH . "flibusta/" . $zip_name)) {
	$filename = $book->author_name . " - " . $book->booktitle . " " . $id . "." . $book->filename . "." . trim($book->filetype);
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . basename(rawurlencode($filename)));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');

	if (isset($book->filename)) {
		echo $zip->getFromName($book->filename);
	} else {
		echo $zip->getFromName("$id." . trim($book->filetype));
	}
	$zip->close();
} else {
	echo "NO ZIP";
}



