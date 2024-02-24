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
	$_SESSION['authors_letter'] = $get;
}

if (isset($_SESSION['authors_letter'])) {
	$get = $_SESSION['authors_letter'];
}
if (isset($_GET['letter'])) {
	$get = mb_strtolower($_GET['letter']);
}
if ($get != '') {
	$_SESSION['authors_letter'] = $get;
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
		echo "<li class='page-item $cc'><a class='page-link' href='/authors/?letter=" . urlencode($l) . "'>$l</a></li>";
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
		echo "<li class='page-item $cc'><a class='page-link' href='/authors/?letter=" . urlencode($l) . "'>$l</a></li>";
	}

echo "</ul>";

?>
<form action='/authors/'>
<div class="input-group mb-3">
  <input name="q" type="text" class="form-control" placeholder="Поиск автора" aria-label="Поиск серии" aria-describedby="basic-addon2">
  <div class="input-group-append">

    <input type='submit' class="btn btn-outline-secondary" value='Поиск' type="button">
  </div>
</div>
</form>

<?php
$start = AUTHORS_PAGE * $page;

$stmt = $dbh->prepare("SELECT COUNT(*) cnt FROM libavtorname WHERE lower(libavtorname.lastname) LIKE :letter");
$stmt->bindParam(":letter", $letter);
$stmt->execute();
$cnt = $stmt->fetch()->cnt;

$stmt = $dbh->prepare("SELECT *,
		(SELECT COUNT(*) FROM libavtor WHERE libavtor.avtorid=libavtorname.avtorid) cnt
		FROM libavtorname
		LEFT JOIN libapics USING(AvtorId)
		WHERE LOWER(libavtorname.lastname) LIKE :letter
		ORDER BY firstname LIMIT " . AUTHORS_PAGE . " OFFSET $start");
$stmt->bindParam(":letter", $letter);
$stmt->execute();

echo '<div class="row">';
show_gpager(ceil($cnt / AUTHORS_PAGE), 5);
while ($a = $stmt->fetch()) {
	if ($a->cnt > 0) {
		echo "<div class='col col-sm-6 mb-3 d-flex justify-content-between'>";
		echo "<a class='mw-100 rounded-pill author' href='/author/view/$a->avtorid'>";
		if ($a->file != '') {
			echo "<img class='rounded-circle contact' src='/extract_author.php?id=$a->avtorid' />";	
		}
		echo "&nbsp;$a->lastname $a->firstname $a->middlename $a->nickname&nbsp;</a>";
		echo "<div class='badge bg-secondary'>$a->cnt</div>";
		echo "</div>";

	}
}
echo "</div>";

show_gpager(ceil($cnt / AUTHORS_PAGE), 5);
