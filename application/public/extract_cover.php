<?php
include('../init.php');
$cover = '';
$q = 75;
header('Cache-Control: public, max-age=86400');

function resizeCover($filename, $newwidth, $newheight){
	$i = imagecreatefromstring($filename);
	$width = imagesx($i);
       	$height = imagesy($i);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);   
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresized($thumb, $i, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $thumb;
}

function lastm($path) {
	$fmtimestamp = filemtime($path);
	if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $fmtimestamp <= strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
		die();
	} else {
		header("Expires: " . gmdate("D, d M Y H:i:s", filemtime($path) + 60*60*24) . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($path)) . " GMT");

		echo file_get_contents($path);
	}
}

$small = isset($_GET['small']);

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	if (isset($_GET['sid'])) {
		$id = $_GET['sid'];
		$small = true;
	}
}
$iid = $id;

header("Content-type: image/jpeg");

if ($small) {
	if (file_exists(ROOT_PATH . "cache/covers/$id-small.jpg")) {
		lastm(ROOT_PATH . "cache/covers/$id-small.jpg");
		die();
	}
} else {
	if (file_exists(ROOT_PATH . "cache/covers/$id.jpg")) {
		lastm(ROOT_PATH . "cache/covers/$id.jpg");
		die();
	}
}

$stmt = $dbh->prepare("SELECT file FROM libbpics WHERE BookId=$id");
$stmt->execute();
$f = $stmt->fetch();

if (isset($f->file)) {
	$zip = new ZipArchive(); 
	if ($zip->open(ROOT_PATH . "cache/lib.b.attached.zip")) {
		$f = $zip->getFromName($f->file);
		if (strlen($f) > 0) {
			file_put_contents(ROOT_PATH . "cache/covers/$id.jpg", $f);
			$thm = resizeCover($f, 300, 400);
			imagejpeg($thm, ROOT_PATH . "cache/covers/$id-small.jpg", 75);
			imagedestroy($thm);
			if ($small) {
				if (file_exists(ROOT_PATH . "cache/covers/$id-small.jpg")) {
					lastm(ROOT_PATH . "cache/covers/$id-small.jpg");
				die();
				}
			} else {
				echo $f;
				die();
			}
		}
	}
	$zip->close();
}


$stmt = $dbh->prepare("SELECT filetype FROM libbook WHERE bookid=$id LIMIT 1");
$stmt->execute();
$type = trim($stmt->fetch()->filetype);
if ($type == 'fb2') {
	$u = '0';
} else {
	$u = '1';
}

$stmt = $dbh->prepare("SELECT * FROM book_zip WHERE $id BETWEEN start_id AND end_id AND usr=$u");
$stmt->execute();
$zip_name = $stmt->fetch()->filename;
$zip = new ZipArchive(); 

$filename = $dbh->query("SELECT filename FROM libfilename where BookId=$id")->fetch()->filename;
if ($filename == '') {
	$filename = trim("$id.$type");
}

if ($zip->open(ROOT_PATH . "flibusta/" . $zip_name)) {
	$f = $zip->getFromName("$filename");
}


if ($type == 'fb2') {
	$fb2 = simplexml_load_string($f);
	$images = array();
	if (isset($fb2->binary)) {
		foreach ($fb2->binary as $binary) {
			$id = $binary->attributes()['id'];		
			if (
				(strpos($id, "cover") !==  false) ||
				(strpos($id, "jpg") !==  false) ||
				(strpos($id, "obloj") !==  false)
			) {
				$cover = base64_decode($binary);
			}
			$images["$id"] = $binary;
		}
	}
	$zip->close();
}

if ($type == 'epub') {
	file_put_contents(ROOT_PATH . "cache/tmp/$iid.tmp", $f);
	include('/application/epub.php');
	$d = new EPub(ROOT_PATH . "cache/tmp/$iid.tmp");
	$im = $d->Cover();
	if ($im['found'] != '') {
		$cover = $im['data'];
		unlink(ROOT_PATH . "cache/tmp/$iid.tmp");
	} else {
		echo file_get_contents('none.jpg');
	}
}

if (strlen($cover) < 100) {
	$cover = file_get_contents(ROOT_PATH . 'none.jpg');
	echo $cover;
	die();
} else {
	file_put_contents(ROOT_PATH . "cache/covers/$iid.jpg", $cover);
	$thm = resizeCover($cover, 300, 400);
	imagejpeg($thm, ROOT_PATH . "cache/covers/$iid-small.jpg", 75);
	imagedestroy($thm);
}

if ($small) {
	if (file_exists(ROOT_PATH . "cache/covers/$iid-small.jpg")) {
		lastm(ROOT_PATH . "cache/covers/$iid-small.jpg");
		die();
	}
} else {
	echo $cover;
}

