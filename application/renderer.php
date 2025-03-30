<!doctype html>
<html lang="ru">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name='wmail-verification' content='7404cc552213a233445be2fe20acca8c' />

<?php
	if ($url->description != '') {
		echo "<meta name='description' content='$url->description' />";
	}

	if ($url->title != '') {
		$title = $url->title;
	} else {
		$title = 'Библиотека';
	}
	echo "<title>$title</title>";
	include_once(ROOT_PATH . 'webroot.php');
echo <<< __HTML

<link href="$webroot/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="$webroot/bootstrap/js/bootstrap.bundle.min.js"></script>

<link rel="icon" href="$webroot/favicon.svg" sizes="any" type="image/svg+xml">

<link href="$webroot/css/all.min.css" rel="stylesheet">
<link href="$webroot/css/style.css" rel="stylesheet">
__HTML
?>
<style>
.pagination>li.active>a {
  background-color: #777 !important;
  border-color: #6d6d6d !important;
}

.badge {
    white-space: break-spaces;
}

.author {
        background: #dddddd;
}

.author a {
	color: #333;
	line-height: 24px;
	padding-left: 3px;
}

.contact {
         width: 24px;
         height: 24px;
}

</style>

</head>
<?php
$c1 = '';
$c2 = '';
$c3 = '';
$c4 = '';
$c5 = '';
$c6 = '';

switch ($url->mod) {
	case '':
		$c1 = 'active';
		break;
	case 'genres':
		$c2 = 'active';
		break;
	case 'genres':
		$c3 = 'active';
		break;
	case 'authors':
		$c4 = 'active';
		break;
	case 'fav':
		$c5 = 'active';
		break;
	case 'service':
		$c6 = 'active';
		break;
	default:
		$c1 = 'active';
}



echo <<< __HTML

<body style='background-color: #343a40;'>

<div class="container whb">
<nav class="navbar navbar-expand-lg navbar-dark rounded-bottom shadow" style="background-color: #3e3b6c;">
<div class="container-fluid">
  <a class="navbar-brand" href="$webroot/" title="Библиотека">
   &nbsp;Библиотека
  </a>
		<ul class="navbar-nav mr-auto">
			<li class="nav-item $c1"><a title="" class="nav-link" href="$webroot/">Книги</a></li>
			<li class="nav-item $c2"><a title="" class="nav-link" href="$webroot/genres/">Жанры</a></li>
			<li class="nav-item $c4"><a title="" class="nav-link" href="$webroot/authors/">Авторы</a></li>
			<li class="nav-item $c3"><a title="" class="nav-link" href="$webroot/series/">Серии</a></li>
			<li class="nav-item $c5"><a title="" class="nav-link" href="$webroot/fav/">Полка</a></li>
			<li class="nav-item $c6"><a title="" class="nav-link" href="$webroot/service/">Сервис</a></li>
		</ul>

<div class="d-flex">

<a href='$webroot/favlist/' class='btn btn-outline-success' type='submit'>$user_name</a>
__HTML
?>
</div>

</div>
</nav>
</div>
<div class="container whb">
<br />
<?php
if (file_exists($url->module)) {
//	echo "<h1>$url->title</h1>";
	include($url->module);
} else {
	echo 'Раздел не найден', 'Вы ввели неверный адрес, либо раздел находится в разработке.';
	header("HTTP/1.0 404 Not Found");
}
?>
<div>&nbsp;</div>
</div>



<div class="container whb rounded-bottom mb-3">
	<nav class="navbar navbar-expand-lg rounded-top shadow navbar-dark bg-dark" style="background-color: #768fa8;">
		<ul class="navbar-nav mr-auto">
		</ul>
	</nav>
</div>




</body>
</html>

