<?php
$stmt = $dbh->prepare("SELECT COUNT(*) cnt FROM fav_users");
$stmt->execute();
$fav_count = $stmt->fetch()->cnt;

if ($fav_count == 0) {
	die("Книжные полки не определены");
}

$stmt = $dbh->prepare("SELECT *
		FROM fav
		LEFT JOIN libavtorname USING(AvtorId)
		LEFT JOIN libapics USING(AvtorId)
		WHERE user_uuid=:uuid AND avtorid IS NOT NULL");
$stmt->bindParam(":uuid", $user_uuid);

try {
	$stmt->execute();
} catch (PDOException $e) {
	//
}

echo '<div class="row">';
while ($a = $stmt->fetch()) {
	echo "<div class='col col-sm-333 mb-3 d-flex justify-content-between'>";
	echo "<a class='mw-100 rounded-pill author' href='$webroot/author/view/$a->avtorid'>";
	if ($a->file != '') {
		echo "<img class='rounded-circle contact' src='$webroot/extract_author.php?id=$a->avtorid' />";	
	}
	echo "&nbsp;$a->lastname $a->firstname $a->middlename $a->nickname&nbsp;</a>";
	echo "</div>";
}
echo "</div>";


$stmt = $dbh->prepare("SELECT *
		FROM fav
		LEFT JOIN libseqname USING(seqid)
		WHERE user_uuid=:uuid AND seqid IS NOT NULL");
$stmt->bindParam(":uuid", $user_uuid);

echo '<div class="row">';
echo '<div class="col mb-3">';
echo '<div class="block">';
try {
$stmt->execute();
while ($s = $stmt->fetch()) {
	echo "<a class='btn btn-sm btn-dark' href='$webroot/?sid=$s->seqid'>";
	echo "&nbsp;$s->seqname&nbsp;</a> ";
}

} catch (PDOException $e) {
	print_r($e);
}
echo "</div>";
echo "</div>";
echo "</div>";

$stmt = $dbh->prepare("SELECT DISTINCT b.*
		FROM fav f
		LEFT JOIN libbook b USING(bookid)
		WHERE user_uuid=:uuid AND f.bookid IS NOT NULL");
$stmt->bindParam(":uuid", $user_uuid);
$stmt->execute();

$cnt = $stmt->rowCount();

//show_gpager(ceil($cnt / RECORDS_PAGE), 5);

echo "<div class='contaner'>";
echo "<div class='row equal'>";

$c = 0;
while ($book = $stmt->fetch()) {
	$c++;
	if ($c > 10) {
//		break;
	}
	book_small_pg($book,$webroot);
}
echo "</div>";
echo "</div>";

//show_gpager(ceil($cnt / RECORDS_PAGE), 5);

