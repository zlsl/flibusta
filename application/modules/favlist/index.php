<div class="block rounded" style="margin-bottom:8px;"><form action="/favlist/">
<div class="input-group mb-3">
   <input name="new_uuid" type="text" class="form-control" placeholder="Новая полка" aria-label="Новая полка" aria-describedby="basic-addon2">
   <div class="input-group-append">
   <input type="submit" class="btn btn-outline-secondary" value="Создать">
 </div>
</div>
</form>
</div>

<?php
$stmt = $dbh->query("SELECT * FROM fav_users");

echo '<div class="row">';
while ($a = $stmt->fetch()) {
	echo "<div class='col-sm-6'>";
	echo "<div class='card mb-3'>";

	echo "<div class='card-header'>";
	echo "<a href='/fav/?login_uuid=$a->user_uuid'>$a->name</a>";
	echo "</div>";

	echo "<div class='card-body'>";
	$bs = $dbh->prepare("SELECT (SELECT COUNT(*) cnt FROM fav WHERE user_uuid=:uuid AND bookid is not null) bcnt, (SELECT COUNT(*) cnt FROM fav WHERE user_uuid=:uuid AND avtorid is not null) acnt, (SELECT COUNT(*) cnt FROM fav WHERE user_uuid=:uuid AND seqid is not null) scnt");
	$bs->bindParam(":uuid", $a->user_uuid);
	$bs->execute();
	$sta = $bs->fetch();
	echo '<ul class="list-group list-group-horizontal">';
	echo "<li class='list-group-item flex-fill'>Книг: $sta->bcnt</li>";
	echo "<li class='list-group-item flex-fill'>Авторов: $sta->acnt</li>";
	echo "<li class='list-group-item flex-fill'>Серий: $sta->scnt</li>";
	echo "</ul>";
	echo "</div>";

	echo "<div class='card-footer'>";
	echo "<a class='btn btn-danger btn-sm float-end' href='/favlist/?delete_uuid=$a->user_uuid'>Удалить</a>";
	echo "</div>";

	echo "</div>";
	echo "</div>";
}
echo "</div>";

