<?php
$stmt = $dbh->prepare("SELECT * FROM libavtorname LEFT JOIN libapics USING(AvtorId) WHERE avtorid=:id");
$stmt->bindParam(":id", $url->var1);
$stmt->execute();
$a = $stmt->fetch();

echo "<div class='card mb-3'>";
echo "<div class='card-header'><h3>$a->lastname $a->firstname $a->middlename $a->nickname</h3></div>";
echo "<div class='card-body'><div class='row'>";

echo "<div class='col-sm-2 mb-3'>";
if (isset($a->file)) {
	echo "<img src='/extract_author.php?id=$a->avtorid' style='width: 100%;' class='card-image' /><br />";	
}
echo "<a class='btn btn-primary mt-2 w-100' href='/?aid=$a->avtorid'>Книги автора</a>";

$stmt = $dbh->prepare("SELECT COUNT(*) cnt FROM fav WHERE user_uuid=:uuid AND avtorid=:id");
$stmt->bindParam(":uuid", $user_uuid);
$stmt->bindParam(":id", $a->avtorid);
$stmt->execute();
$is_fav = ($stmt->fetch()->cnt > 0);
if (!$is_fav) {
	echo "<a class='btn btn-secondary mt-2 w-100' href='/?fav_author=$a->avtorid'>В избранное</a>";
} else {
	echo "<a class='btn btn-warning mt-2 w-100' href='/?unfav_author=$a->avtorid'>Из избранного</a>";
}
echo "</div>";
echo "<div class='col-sm-10 mb-3'>";

$stmt = $dbh->prepare("SELECT * FROM libaannotations WHERE AvtorId=:id");
$stmt->bindParam(":id", $url->var1);
$stmt->execute();
while ($an = $stmt->fetch()) {
	echo "$an->title<br />";
	echo "<p>", bbc2html(nl2br($an->body)), "</p>";
}
echo '</div>';



echo "</div></div></div>";

