<?php

function bbc2html($content) {
  $search = array (
    '/(\[b\])(.*?)(\[\/b\])/',
    '/(\[i\])(.*?)(\[\/i\])/',
    '/(\[u\])(.*?)(\[\/u\])/',
    '/(\[ul\])(.*?)(\[\/ul\])/',
    '/(\[li\])(.*?)(\[\/li\])/',
    '/(\[url=)(.*?)(\])(.*?)(\[\/url\])/',
    '/(\[url\])(.*?)(\[\/url\])/'
  );

  $replace = array (
    '<strong>$2</strong>',
    '<em>$2</em>',
    '<u>$2</u>',
    '<ul>$2</ul>',
    '<li>$2</li>',
    '<a href="$2" target="_blank">$4</a>',
    '<a href="$2" target="_blank">$2</a>'
  );

  return preg_replace($search, $replace, $content);
}


function show_gpager($page_count, $block_size = 100) {
	global $url;
	if (isset($_GET['page'])) {
		$page = intval($_GET['page']);
	} else {
		$page = 0;
	}
	if ($page_count > 1) {
		echo "<nav><ul class='pagination pagination-sm'>";

		$b1 = $page - $block_size;
		$b2 = $block_size + $page;


		if ($b1 < 1) {
			$b1 = 1;
		}
		if ($b2 > $page_count) {
			$b2 = $page_count;
		}

    	if ($b1 > 1) {
 			echo "<li class='page-item'><a class='page-link' href='?page=", $b1 - 2, "' aria-label='Previous'><span aria-hidden='true'><i class='fas fa-angle-left'></i></span></a></li>";
	    	
    	}

		for ($p = $b1; $p <= $b2; $p++) {
			if ($p == $page + 1) {
				$pv = 'active';
			} else {
				$pv = '';
			}
			echo "<li class='page-item $pv'><a class='page-link' href='?page=", $p - 1, "'>$p</a></li>";
		}
		$pv = '';		
    	
    	if ($b2 < $page_count) {
    		echo "<li class='page-item'><a class='page-link' href='?page=", $b2, "' aria-label='Next'><span aria-hidden='true'><i class='fas fa-angle-right'></i></span></a></li>";
    	}

		echo '</ul></nav>';
	}
}



function pg_array_parse($literal){
    if ($literal == '') return;
    preg_match_all('/(?<=^\{|,)(([^,"{]*)|\s*"((?:[^"\\\\]|\\\\(?:.|[0-9]+|x[0-9a-f]+))*)"\s*)(,|(?<!^\{)(?=\}$))/i', $literal, $matches, PREG_SET_ORDER);
    $values = [];
    foreach ($matches as $match) {
        $values[] = $match[3] != '' ? stripcslashes($match[3]) : (strtolower($match[2]) == 'null' ? null : $match[2]);
    }
    return $values;
}

function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' .addslashes($t) . '"';
            $result[] = $t;
        }
    }
    return '{' . implode(",", $result) . '}'; // format
}


function book_small_pg($book, $full = false) {
	global $dbh, $user_uuid;
	if (!isset($book->bookid)) {
		return;
	}
	echo "<div class='col-sm-2 col-6 mb-3'>";
	echo "<div style='height: 100%' class='cover rounded text-center d-flex align-items-end flex-column'>";
	echo "<a class='w-100' href='/book/view/$book->bookid'>";
	echo "<img class='w-100 card-image rounded-top' src='/extract_cover.php?id=$book->bookid&small' />";

	$dt =DateTime::createFromFormat('Y-m-d H:i:se', $book->time)->format('Y-m-d');
	if (trim($book->filetype) == 'fb2') {
		$ft = 'success';
		$fhref = "/fb2.php?id=$book->bookid";
	} else {
		$ft = 'secondary';
		$fhref = "/usr.php?id=$book->bookid";
	}

	if ($book->year != 0) {
		$year = $book->year;
	} else {
		$year = $dt;
	}

	$stmt = $dbh->prepare("SELECT COUNT(*) cnt FROM fav WHERE user_uuid=:uuid AND bookid=:id");
	$stmt->bindParam(":uuid", $user_uuid);
	$stmt->bindParam(":id", $book->bookid);
	$stmt->execute();
	if ($stmt->fetch()->cnt > 0) {
		$fav = 'btn-primary';
		$fav_url = "?unfav_book=$book->bookid";
	} else {
		$fav = 'btn-outline-secondary';
		$fav_url = "?fav_book=$book->bookid";
	}

	echo "<div>$book->title</div></a>";
	echo "<div class='btn-group w-100 mt-auto' role='group'>";
	echo "<button type='button' class='btn btn-outline-secondary btn-sm'>$year</button>";
	echo "<a href='$fhref' title='Скачать' type='button' class='btn btn-outline-$ft btn-sm'>$book->filetype</a>";
//	echo "<button type='button' class='btn btn-outline-secondary btn-sm'>$book->lang</button>";
	echo "<a href='$fav_url' title='В избранное' type='button' class='btn $fav btn-sm'><i class='fas fa-heart'></i></a>";
	
	echo "</div></div></div>\n";
}

function book_info_pg($book, $full = false) {
	global $dbh, $user_uuid;
	if (!isset($book->bookid)) {
		return;
	}
	echo "<div class='hic card mb-3' itemscope='' itemtype='http://schema.org/Book'>";
//	echo "<div class='card-header'>";
	echo "<h4 class='rounded-top' style='background: #d0d0d0;'><a class='book-link' href='/book/view/$book->bookid'><i class='fas'></i> $book->title</h4></a>";
//	echo "</div>";
	echo "<div class='card-body'>";
	echo "<div class='row'>";
	echo "<div class='col-sm-2'>";
	echo "<img class='w-100 card-image rounded cover' src='/extract_cover.php?id=$book->bookid&small' />";

	$dt =DateTime::createFromFormat('Y-m-d H:i:se', $book->time)->format('Y-m-d');
	if (trim($book->filetype) == 'fb2') {
		$ft = 'success';
		$fhref = "/fb2.php?id=$book->bookid";
	} else {
		$ft = 'secondary';
		$fhref = "/usr.php?id=$book->bookid";
	}

	if ($book->year != 0) {
		$year = $book->year;
	} else {
		$year = $dt;
	}
	

	echo "<div class='btn-group w-100 mt-1' role='group'>";
	echo "<button type='button' class='btn btn-outline-secondary btn-sm'>$year</button>";
	echo "<a href='$fhref' title='Скачать' type='button' class='btn btn-outline-$ft btn-sm'>$book->filetype</a>";
//	echo "<button type='button' class='btn btn-outline-secondary btn-sm'>$book->lang</button>";
	if ($user_uuid != '') {
		$stmt = $dbh->prepare("SELECT COUNT(*) cnt FROM fav WHERE user_uuid=:uuid AND bookid=:id");
		$stmt->bindParam(":uuid", $user_uuid);
		$stmt->bindParam(":id", $book->bookid);
		$stmt->execute();
		if ($stmt->fetch()->cnt > 0) {
			$fav = 'btn-primary';
			$fav_url = "?unfav_book=$book->bookid";
		} else {
			$fav = 'btn-outline-secondary';
			$fav_url = "?fav_book=$book->bookid";
		}
		echo "<a href='$fav_url' title='В избранное' type='button' class='btn $fav btn-sm'><i class='fas fa-heart'></i></a>";
	}
	echo "</div>";
	
	echo "</div><div class='col-sm-10'>";
	echo "<div class='authors-list'>";
	$stmt = $dbh->prepare("SELECT AvtorId, LastName, FirstName, nickname, middlename, File FROM libavtor a
		LEFT JOIN libavtorname USING(AvtorId)
		LEFT JOIN libapics USING(AvtorId)
		WHERE a.BookId=:id");
	$stmt->bindParam(":id", $book->bookid);
	$stmt->execute();
	while ($a = $stmt->fetch()) {
		echo "<div class='badge rounded-pill author'>";
		if ($a->file != '') {
			echo "<img class='rounded-circle contact' src='/extract_author.php?id=$a->avtorid' />";	
		}
		echo "<a href='/author/view/$a->avtorid'>$a->lastname $a->middlename $a->firstname $a->nickname</a>";
		echo "</div>";
	}
	echo "</div>";


	echo "<div style='margin-bottom: 3px;'>";
	$genres = $dbh->prepare("SELECT GenreId, GenreDesc FROM libgenre 
		JOIN libgenrelist USING(GenreId)
		WHERE BookId=$book->bookid");
	$genres->execute();
	while ($g = $genres->fetch()) {
		echo "<a class='badge bg-success p-1 text-white' href='/?gid=$g->genreid'>$g->genredesc</a> ";
	}
	echo "</div>";
	
	echo "<div style='margin-bottom: 3px;'>";
	$seq = $dbh->prepare("SELECT SeqId, SeqName, SeqNumb FROM libseq
				JOIN libseqname USING(SeqId)
				WHERE BookId=:id");
	$seq->bindParam(":id", $book->bookid);
	$seq->execute();
	while ($s = $seq->fetch()) {
		echo "<a class='badge bg-danger p-1 text-white' href='/?sid=$s->seqid'>$s->seqname ";
		if ($s->seqnumb > 0) {
			echo " $s->seqnumb";
		}
		echo "</a> ";
	}
	echo "</div>";

	echo "<div style='margin-bottom: 3px;'>";
	if ($book->keywords != '') {
		$kw = explode(",", $book->keywords);
		foreach ($kw as $k) {
			echo "<a class='badge bg-secondary p-1 text-white' href='#'>$k</a> ";
		}
	}
	echo "</div>";

	echo "<div style='font-size: 0.8em;'>";
	if (isset($book->body)) {
		if ($full) {
			echo "<p>" . trim($book->body) . "</p>";
		} else {
			echo "<p>" . cut_str(trim(strip_tags($book->body))) . "</p>";
		}
	}
	echo "</div>";

	echo "</div>";
	echo "</div>";
	echo "</div></div>\n";
}

date_default_timezone_set('Europe/Minsk');
date_default_timezone_set('Etc/GMT-3');
setlocale(LC_ALL, 'rus_RUS');

$m_time = explode(" ",microtime());
$m_time = $m_time[0] + $m_time[1];
$starttime = $m_time;
$sql_time = 0;


$cdt = date('Y-m-d H:i:s');
$today_from =  date('Y-m-d') . ' 00:00:00';
$today_to   = date('Y-m-d') . ' 23:59:59';


function russian_date() {
 $translation = array(
 "am" => "дп",
 "pm" => "пп",
 "AM" => "ДП",
 "PM" => "ПП",
 "Monday" => "Понедельник",
 "Mon" => "Пн",
 "Tuesday" => "Вторник",
 "Tue" => "Вт",
 "Wednesday" => "Среда",
 "Wed" => "Ср",
 "Thursday" => "Четверг",
 "Thu" => "Чт",
 "Friday" => "Пятница",
 "Fri" => "Пт",
 "Saturday" => "Суббота",
 "Sat" => "Сб",
 "Sunday" => "Воскресенье",
 "Sun" => "Вс",
 "January" => "Января",
 "Jan" => "Янв",
 "February" => "Февраля",
 "Feb" => "Фев",
 "March" => "Марта",
 "Mar" => "Мар",
 "April" => "Апреля",
 "Apr" => "Апр",
 "May" => "Мая",
 "May" => "Мая",
 "June" => "Июня",
 "Jun" => "Июн",
 "July" => "Июля",
 "Jul" => "Июл",
 "August" => "Августа",
 "Aug" => "Авг",
 "September" => "Сентября",
 "Sep" => "Сен",
 "October" => "Октября",
 "Oct" => "Окт",
 "November" => "Ноября",
 "Nov" => "Ноя",
 "December" => "Декабря",
 "Dec" => "Дек",
 "st" => "ое",
 "nd" => "ое",
 "rd" => "е",
 "th" => "ое",
 );
 if (func_num_args() > 1) {
	$timestamp = func_get_arg(1);
	return strtr(date(func_get_arg(0), $timestamp), $translation);
 } else {
	return strtr(date(func_get_arg(0)), $translation);
 };
}
/***************************************************************************/
function transliterate($string){
  $cyr=array(
     "Щ", "Ш", "Ч","Ц", "Ю", "Я", "Ж","А","Б","В",
     "Г","Д","Е","Ё","З","И","Й","К","Л","М","Н",
     "О","П","Р","С","Т","У","Ф","Х","Ь","Ы","Ъ",
     "Э","Є", "Ї","І",
     "щ", "ш", "ч","ц", "ю", "я", "ж","а","б","в",
     "г","д","е","ё","з","и","й","к","л","м","н",
     "о","п","р","с","т","у","ф","х","ь","ы","ъ",
     "э","є", "ї","і", " "
  );
  $lat=array(
     "Shch","Sh","Ch","C","Yu","Ya","J","A","B","V",
     "G","D","e","e","Z","I","y","K","L","M","N",
     "O","P","R","S","T","U","F","H","", 
     "Y","" ,"E","E","Yi","I",
     "shch","sh","ch","c","Yu","Ya","j","a","b","v",
     "g","d","e","e","z","i","y","k","l","m","n",
     "o","p","r","s","t","u","f","h",
     "", "y","" ,"e","e","yi","i", "%20"
  );
  for($i=0; $i<count($cyr); $i++)  {
     $c_cyr = $cyr[$i];
     $c_lat = $lat[$i];
     $string = str_replace($c_cyr, $c_lat, $string);
  }
  $string = 
  	preg_replace(
  		"/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", 
  		"\${1}e", $string);
/*  $string = 
  	preg_replace(
  		"/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", 
  		"\${1}'", $string);*/
  $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
  $string = preg_replace("/^kh/", "h", $string);
  $string = preg_replace("/^Kh/", "H", $string);
  return $string;
}


function stars($rating) {
    $fullStar = '<img alt="1" class="star" src="/i/s1.png" />';
    $emptyStar = '<img alt="0" class="star" src="/i/s0.png" />';
    $rating = $rating <= 5?$rating:5;
    $fullStarCount = (int)$rating;
    $emptyStarCount = 5 - $fullStarCount;
    $html = str_repeat($fullStar,$fullStarCount);
    $html .= str_repeat($emptyStar,$emptyStarCount);
    echo $html;
}

/***************************************************************************/
function cut_str($string, $maxlen=700) {
    $len = (mb_strlen($string) > $maxlen)
        ? mb_strripos(mb_substr($string, 0, $maxlen), ' ')
        : $maxlen
    ;
    $cutStr = mb_substr($string, 0, $len);
    return (mb_strlen($string) > $maxlen)
        ? $cutStr . '...'
        : $cutStr
    ;
}

/***************************************************************************/
function cut_str2($string, $maxlen=700) {
    $len = (mb_strlen($string) > $maxlen)
        ? mb_strripos(mb_substr($string, 0, $maxlen), ' ')
        : $maxlen
    ;
    $cutStr = mb_substr($string, 0, $len);
    return $cutStr . $len;
}

/***************************************************************************/
function clean_str($input, $sql=false) {
  if ($sql) {
    $input = DB::esc($input);
  }
  $input = strip_tags($input);

  $input = str_replace ("\n"," ", $input);
  $input = str_replace ("\r","", $input);

  $input = preg_replace("/[^(\w)|(\x7F-\xFF)|^(_,\-,\.,\;,\@)|(\s)]/", " ", $input);

  return $input;
}

/***************************************************************************/
function decode_gurl($mobile = false)  {
  global $last_modified, $url, $robot;
  global $sex_post;

  $urlx = parse_url(urldecode($_SERVER['REQUEST_URI']));

  list($x, $module, $action, $var1, $var2, $var3) = array_pad(explode('/', $urlx['path']), 6, null);

  $url = new stdClass();

  $url->mod = safe_str($module);
  $url->action = safe_str($action);
  $url->var1 = intval($var1);
  $url->var2 = intval($var2);
  $url->var3 = intval($var3); 
  $url->title = '';
  $url->description = '';
  $url->mod_path = '';
  $url->mod_menu = '';
  $url->image = '';
  $url->noindex = 0;
  $url->index = 1;
  $url->follow = 1;
  $url->module_menu = '';
  $url->js = array();
  $url->editor = 0;
  $url->access = 0;
  $url->canonical = '';

  $menu = true;

  if ($url->mod == '') {
    $url->mod ='primary';
  }

  if (file_exists(ROOT_PATH . 'modules/' . $url->mod . '/module.conf')) {
    $last_modified = gmdate('D, d M Y H:i:s', filemtime(ROOT_PATH . 'modules/' . $url->mod . '/index.php')) . ' GMT';
    $url->module = ROOT_PATH . 'modules/' . $url->mod . '/index.php';
    $url->mod_path = ROOT_PATH . 'modules/' . $url->mod . '/';
    include(ROOT_PATH . 'modules/' . $url->mod . '/module.conf');
  } else {
    $menu = false;
    include(ROOT_PATH . 'modules/404/module.conf');
    $url->module = ROOT_PATH . 'modules/404/index.php';
    $url->mod = '404';  
  }

  if ($url->access > 0) {
    if (!is_admin()) {
      include(ROOT_PATH . 'modules/403/module.conf');
      $url->module = ROOT_PATH . 'modules/403/index.php';
      $url->mod = '403';
      $menu = false;
    }
  }

  if ( (file_exists(ROOT_PATH . 'modules/' . $url->mod . '/module_menu.php')) && ($menu) ) {
    $url->module_menu = ROOT_PATH . 'modules/' . $url->mod . '/module_menu.php';
  }

  return $url;
}

function safe_str($str) {
        return preg_replace("/[^A-Za-z0-9 -_]/", '', $str);
}


function mobile() {
        $devices = array(
                "android" => "android.*mobile",
                "androidtablet" => "android(?!.*mobile)",
                "iphone" => "(iphone|ipod)",
                "ipad" => "(ipad)",
                "generic" => "(kindle|mobile|mmp|midp|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|opera mini)"
        );
        $isMobile = false;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
        } else {
                $userAgent = "";
        }
        if (isset($_SERVER['HTTP_ACCEPT'])) {
               $accept = $_SERVER['HTTP_ACCEPT'];
        } else {
                $accept = '';
        }
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
                $isMobile = true;
        } elseif (strpos($accept, 'text/vnd.wap.wml') > 0 || strpos($accept, 'application/vnd.wap.xhtml+xml') > 0) {
                $isMobile = true;
        } else {
                foreach ($devices as $device => $regexp) {
                        if (preg_match("/" . $devices[strtolower($device)] . "/i", $userAgent)) {
                                $isMobile = true;
                        }
                }
        }
        return $isMobile;
}


function opds_book($b) {
	global $dbh;
	echo " <entry> <updated>" . $b->time . "</updated>";
	echo " <id>tag:book:$b->bookid</id>";
	echo " <title>" . htmlspecialchars($b->title) . "</title>";

	$ann = $dbh->prepare("SELECT body annotation FROM libbannotations WHERE bookid=:id LIMIT 1");
	$ann->bindParam(":id", $b->bookid);
	$ann->execute();
	if ($tmp = $ann->fetch()) {
		$an = $tmp->annotation;
	} else {
		$an = '';
	}
	$genres = $dbh->prepare("SELECT genrecode, GenreId, GenreDesc FROM libgenre 
		JOIN libgenrelist USING(GenreId)
		WHERE bookid=:id");
	$genres->bindParam(":id", $b->bookid);
	$genres->execute();
	while ($g = $genres->fetch()) {
		echo "<category term='/subject/" . urlencode($g->genrecode) . "' label='$g->genredesc'/>";
	}

	$sq = '';
	$seq = $dbh->prepare("SELECT SeqId, SeqName, SeqNumb FROM libseq
		JOIN libseqname USING(SeqId)
		WHERE BookId=:id");
	$seq->bindParam(":id", $book->bookid);
	$seq->execute();
	while ($s = $seq->fetch()) {
		$ssq = $s->seqname;
		if ($s->seqnumb > 0) {
			$ssq .= " ($s->seqnumb) ";
		}
		$sq .= $ssq;
	}
	if ($sq != '') {
		$sq = "Сборник: $sq";
	}


	echo "<author>";
	$au = $dbh->prepare("SELECT AvtorId, LastName, FirstName, nickname, middlename, File FROM libavtor a
		LEFT JOIN libavtorname USING(AvtorId)
		LEFT JOIN libapics USING(AvtorId)
		WHERE a.bookid=:id");
	$au->bindParam(":id", $b->bookid);
	$au->execute();
	while ($a = $au->fetch()) {
		echo "<name>$a->lastname $a->firstname $a->middlename</name>";
		echo " <uri>/a/id</uri>";
	}

	echo "</author>";
	echo " <dc:language>" . trim($b->lang) . "</dc:language>";
	if ($b->year > 0) {
		echo " <dc:issued>$b->year</dc:issued>";
	}
//	if (intval($b->pages > 0)) {
//		echo "<dcterms:extent>$b->pages страниц</dcterms:extent>";
//	}
	echo " <summary type='text'>" . strip_tags($an) . "\n $sq \n $b->keywords</summary>";
	echo "<link rel='http://opds-spec.org/image/thumbnail' href='/extract_cover.php?sid=$b->bookid' type='image/jpeg'/>";
	echo "<link rel='http://opds-spec.org/image' href='/extract_cover.php?id=$b->bookid' type='image/jpeg'/>";
	if (trim($b->filetype) == 'fb2') {
		$ur = 'fb2';
	} else {
		$ur = 'usr';
	}
	echo " <link href='/$ur.php?id=$b->bookid' rel='http://opds-spec.org/acquisition/open-access' type='application/" . trim($b->filetype) . "' />";

	echo "</entry>\n";
}
