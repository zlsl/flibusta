<?php
if (isset($_GET['fb2'])) {
	if ($_GET['fb2'] == '') {
		unset($_SESSION['fb2']);
	} else {
		$_SESSION['fb2'] = true;
	}
}
if (isset($_GET['ru'])) {
	if ($_GET['ru'] == '') {
		unset($_SESSION['ru']);
	} else {
		$_SESSION['ru'] = true;
	}
}
if (isset($_GET['q'])) {
	if ($_GET['q'] == '') {
		unset($_SESSION['search']);
	} else {
		$get = mb_strtolower($_GET['q']);
		$search = str_replace(' ', '&', $get);
		$_SESSION['search'] = $search;
	}
}

if (isset($_GET['aid'])) {
	if ($_GET['aid'] == '') {
		unset($_SESSION['filter_author']);
	} else {
		$_SESSION['filter_author'] = intval($_GET['aid']);
	}
}


if (isset($_GET['gid'])) {
	if ($_GET['gid'] == '') {
		unset($_SESSION['filter_genre']);
	} else {
		$_SESSION['filter_genre'] = intval($_GET['gid']);
	}
}

if (isset($_GET['sid'])) {
	if ($_GET['sid'] == '') {
		unset($_SESSION['filter_series']);
	} else {
		$_SESSION['filter_series'] = intval($_GET['sid']);
	}
}



if (isset($_GET['xgid'])) {
	if ($_GET['xgid'] == '') {
		unset($_SESSION['filter_xgenre']);
	} else {
		if ($_SESSION['filter_genre'] == intval($_GET['xgid'])) {
			unset($_SESSION['filter_genre']);
		}
		$_SESSION['filter_xgenre'] = intval($_GET['xgid']);
	}
}


$filter = '';
$fcontent = '';
$join = '';
$cols = '';


$fcontent .= '<div class="btn-group mt-1 me-1" role="group">';
if (isset($_SESSION['fb2'])) {
	$filter .= "AND filetype='fb2' ";
	$fcontent .= "<a class='btn bg-dark text-white bg-opacity-90 text-white' href='/?fb2'>Только FB2</a> ";
} else {
	$fcontent .= "<a class='btn bg-dark text-white bg-opacity-50 text-white' href='/?fb2=1'>Все форматы</a> ";
}

if (isset($_SESSION['ru'])) {
	$filter .= "AND lang='ru' ";
	$fcontent .= "<a class='btn bg-success text-white bg-opacity-90 text-white' href='/?ru'>На русском</a> ";
} else {
	$fcontent .= "<a class='btn bg-success text-white bg-opacity-50 text-white' href='/?ru=1'>Все языки</a> ";
}
$fcontent .= '</div>';

if (isset($_SESSION['filter_author'])) {
	$do_cnt = true;
	$filter .= 'AND avtorid=:aid ';
	$join .= 'LEFT JOIN libavtor a USING(BookId) ';
	$stmt = $dbh->prepare("SELECT * FROM libavtorname
		LEFT JOIN libapics USING(AvtorId)
		WHERE AvtorId=:id");
	$stmt->bindParam(":id", $_SESSION['filter_author']);
	$stmt->execute();
	$a = $stmt->fetch();

	$fcontent .= "<div class='badge rounded-pill author'>";
	if ($a->file != '') {
		$fcontent .= "<img class='rounded-circle contact' src='/extract_author.php?id=$a->avtorid' />";
	}
	$fcontent .= "<a href='/?aid'>$a->lastname $a->middlename $a->firstname $a->nickname</a> <i class='fas fa-times-circle'></i></div> ";
}

if (isset($_SESSION['filter_genre'])) {
	$filter .= 'AND g.genreid=:gid ';
	$join .= 'LEFT JOIN libgenre g USING(BookId) ';
	$stmt = $dbh->prepare("SELECT * FROM libgenrelist
		WHERE genreid=:id");
	$stmt->bindParam(":id", $_SESSION['filter_genre']);
	$stmt->execute();
	$g = $stmt->fetch();

	$fcontent .= "<div class='badge bg-success p-1 text-white'>";
	$fcontent .= "<a class='text-white' href='/?xgid=$g->genreid'>$g->genremeta: $g->genredesc <i class='fas fa-times-circle'></i></a></div> ";
}

if (isset($_SESSION['filter_xgenre'])) {
	$filter .= 'AND (SELECT COUNT(*) FROM libgenre xg WHERE xg.BookId=B.BookId AND xg.genreid=:xgid) = 0';
	$stmt = $dbh->prepare("SELECT * FROM libgenrelist
		WHERE genreid=:id");
	$stmt->bindParam(":id", $_SESSION['filter_xgenre']);
	$stmt->execute();
	$xg = $stmt->fetch();

	$fcontent .= "<div class='badge bg-secondary p-1 text-white'>";
	$fcontent .= "<a style='text-decoration: line-through;' class='text-white' href='/?xgid'>$xg->genremeta: $xg->genredesc <i class='fas fa-times-circle'></i></a></div> ";
}


if (isset($_SESSION['filter_series'])) {
	$do_cnt = true;
	$cols = 's.seqnumb,';
	$filter .= 'AND seqid=:sid ';
	$join .= 'LEFT JOIN libseq s USING(BookId) ';
	$stmt = $dbh->prepare("SELECT * FROM libseqname
		WHERE seqid=:id");
	$stmt->bindParam(":id", $_SESSION['filter_series']);
	$stmt->execute();
	$s = $stmt->fetch();

	$fcontent .= "<div class='badge bg-danger p-1 text-white'>";
	$fcontent .= "<a class='text-white' href='/?sid'>$s->seqname <i class='fas fa-times-circle'></i></a></div> ";
	$order = "s.seqnumb, $order";
	$seqname = $s->seqname;
	$seqid = $_SESSION['filter_series'];
}

if (isset($_SESSION['search'])) {
	$filter .= "AND vector @@ to_tsquery('russian', :search) ";
	$join .= 'LEFT JOIN libbook_ts USING(bookid) ';

	$fcontent .= "<div class='badge bg-success p-1 text-white'>";
	$fcontent .= "<a class='text-white' href='/?q'>" . $_SESSION['search'] . " <i class='fas fa-times-circle'></i></a></div> ";
}

if (isset($_SESSION['filter_series'])) {
	$fcontent .= "<a class='btn btn-sm btn-info float-end' href='/?fav_seq=$seqid'>$seqname в Избранное</a> ";
}



echo "<div class='block rounded' style='margin-bottom:8px;'>";
?>
<form action='/'>
<div class="input-group mb-3">
   <input name="q" type="text" class="form-control" placeholder="Поиск по названию" aria-label="Поиск серии" aria-describedby="basic-addon2">
   <div class="input-group-append">
   <input type='submit' class="btn btn-outline-secondary" value='Поиск' type="button">

 </div>
</div>
</form>
<?php
echo $fcontent;

echo "</div>";


$sql = "SELECT *, $cols
        (SELECT Body FROM libbannotations WHERE BookId=b.BookId LIMIT 1) Body
		FROM libbook b
		$join
		WHERE deleted='0'
		$filter
		ORDER BY $order LIMIT " . RECORDS_PAGE . " OFFSET $start";

//echo "$sql";

$stmt = $dbh->prepare($sql);

if (isset($_SESSION['filter_author'])) {
	$stmt->bindParam(":aid", $_SESSION['filter_author']);
}
if (isset($_SESSION['filter_genre'])) {
	$stmt->bindParam(":gid", $_SESSION['filter_genre']);
}
if (isset($_SESSION['filter_xgenre'])) {
	$stmt->bindParam(":xgid", $_SESSION['filter_xgenre']);
}
if (isset($_SESSION['filter_series'])) {
	$stmt->bindParam(":sid", $_SESSION['filter_series']);
}
if (isset($_SESSION['search'])) {
	$stmt->bindParam(":search", $_SESSION['search']);
}


try {
	$stmt->execute();
} catch (Exception $e) {
	$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' 504 Gateway Time-out');

	echo "<div class='card m-3 border-danger'><div class='card-header bg-danger'>База данных</div><div class='card-body'>";
	echo "<h3>" . $e->getMessage() . "</h3>";
	echo "<p>Попробуйте упростить параметры поиска, убрать часть тэгов, направленность. Сервер маленький ^^.</p>";
	echo "</div></div>";
}

if (COUNT_BOOKS) {
	$sql = "SELECT COUNT(*) cnt
		FROM libbook b
		$join
		WHERE deleted='0'
		$filter";
	$stt = $dbh->prepare($sql);

	if (isset($_SESSION['filter_author'])) {
		$stt->bindParam(":aid", $_SESSION['filter_author']);
	}
	if (isset($_SESSION['filter_genre'])) {
		$stt->bindParam(":gid", $_SESSION['filter_genre']);
	}
	if (isset($_SESSION['filter_xgenre'])) {
		$stt->bindParam(":xgid", $_SESSION['filter_xgenre']);
	}
	if (isset($_SESSION['filter_series'])) {
		$stt->bindParam(":sid", $_SESSION['filter_series']);
	}
	if (isset($_SESSION['search'])) {
		$stt->bindParam(":search", $_SESSION['search']);
	}

	$stt->execute();
	$cnt = $stt->fetch()->cnt;
	echo "<span class='badge bg-primary mb-1'>Найдено: $cnt</span> ";
} else {
	$cnt = 2000;
}

$rcnt = $stmt->rowCount();
if ($rcnt < RECORDS_PAGE) {
	$cnt = $page * RECORDS_PAGE + $rcnt;
}

show_gpager(ceil($cnt / RECORDS_PAGE), 5);

$c = 0;
while ($book = $stmt->fetch()) {
	$c++;
	if ($c > 10) {
		break;
	}
	book_info_pg($book);
}

show_gpager(ceil($cnt / RECORDS_PAGE), 5);

