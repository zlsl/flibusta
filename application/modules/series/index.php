<style>
.c {
	background: #eee;
	border-radius: 50%;
	border-color: #eee;
}
</style>

<?php


$filter2 = "";
$letter = 'А%';
$get = '';

if (isset($_GET['q'])) {
	$get = mb_strtolower($_GET['q']);
	$letter = '%' . $get;
	$_SESSION['series_letter'] = $get;
}

if (isset($_SESSION['series_letter'])) {
	$get = $_SESSION['series_letter'];
}
if (isset($_GET['letter'])) {
	$get = mb_strtolower($_GET['letter']);
}
if ($get != '') {
	$_SESSION['series_letter'] = $get;
	$letter = $get . "%";
} else {
	unset($_SESSION['series_letter']);
}

echo "<ul class='pagination'>";
	foreach (range(chr(0xC0), chr(0xDF)) as $b) {
		$l = iconv('CP1251', 'UTF-8', $b);
		if ($l == mb_strtoupper($get)) {
			$cc = 'active';
		} else {
			$cc = '';
		}
		echo "<li class='page-item $cc'><a class='page-link' href='/series/?letter=" . urlencode($l) . "'>$l</a></li>";
	}
echo "</ul>";
echo "<ul class='pagination'>";
	foreach (range('A', 'Z') as $b) {
		$l = iconv('CP1251', 'UTF-8', $b);
		if ($l == mb_strtoupper($get)) {
			$cc = 'active';
		} else {
			$cc = '';
		}
		echo "<li class='page-item $cc'><a class='page-link' href='/series/?letter=" . urlencode($l) . "'>$l</a></li>";
	}

echo "</ul>";

?>
<form action='/series/'>
<div class="input-group mb-3">
  <input name="q" type="text" class="form-control" placeholder="Поиск серии" aria-label="Поиск серии" aria-describedby="basic-addon2">
  <div class="input-group-append">

    <input type='submit' class="btn btn-outline-secondary" value='Поиск' type="button">
  </div>
</div>
</form>

<?php
$start = SERIES_PAGE * $page;

$stmt = $dbh->prepare("SELECT COUNT(*) cnt FROM libseqname WHERE lower(libseqname.SeqName) LIKE :letter");
$stmt->bindParam(":letter", $letter);
$stmt->execute();
$cnt = $stmt->fetch()->cnt;

$stmt = $dbh->prepare("SELECT SeqName, SeqId,
		(SELECT COUNT(*) FROM libseq WHERE libseq.SeqId=libseqname.SeqId) cnt
		FROM libseqname 
		WHERE LOWER(libseqname.SeqName) LIKE :letter
		ORDER BY seqname LIMIT " . SERIES_PAGE . " OFFSET $start");
$stmt->bindParam(":letter", $letter);
$stmt->execute();

echo '<div class="row">';
show_gpager(ceil($cnt / SERIES_PAGE), 5);
while ($bs = $stmt->fetch()) {
	if ($bs->cnt > 0) {
		echo "<div class='col col-sm-6 mb-3 d-flex justify-content-between'><a class='mw-100 text-dark' href='/?sid=$bs->seqid'>$bs->seqname</a><span class='badge bg-secondary'>$bs->cnt</span></div>";
	}
}
echo "</div>";

show_gpager(ceil($cnt / SERIES_PAGE), 5);

