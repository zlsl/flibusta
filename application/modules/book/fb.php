<script>
var isScrolling;

window.addEventListener('scroll', function ( event ) {
	window.clearTimeout( isScrolling );
	isScrolling = setTimeout(function() {
		console.log( this.scrollY );
		var x = new XMLHttpRequest();
		x.open("GET", "/save_position.php?<?php
		echo "user_uuid=$user_uuid&bookid=$url->var1&pos=";
		?>" + (100 / document.body.scrollHeight * this.scrollY), true);
		x.send(null);
	}, 66);

}, false);
</script>


<?php


if ($user_uuid != "") {
	$stmt = $dbh->prepare("SELECT pos FROM progress WHERE user_uuid=:uuid AND bookid=:id LIMIT 1");
	$stmt->bindParam(":uuid", $user_uuid);
	$stmt->bindParam(":id", $url->var1);
	$stmt->execute();
	if ($p = $stmt->fetch()) {
		echo "<script>";
		echo 'document.addEventListener("DOMContentLoaded", function(event) {';
		echo "window.scrollTo(0, (document.body.scrollHeight / 100 *" . $p->pos . "));\n";
		echo "});\n";
		echo "</script>";
	}
}


$content = '';
$data = $zip->getFromName("$url->var1.fb2");

$fb2 = simplexml_load_string($data);
echo ($fb2 ? '' : 'FB2 Parse Error'), PHP_EOL;

$images = array();
foreach ($fb2->binary as $binary) {
	$id = $binary->attributes()['id'];
	$images["$id"] = $binary;
}

if (isset($fb2->body->section)) {
	foreach ($fb2->body->section as $section) {
		$s = $section->asXML();
		$s = str_replace("<title>", "<subtitle>", $s);
		$s = str_replace("</title>", "</subtitle>", $s);
		$s = str_replace('<image l:href="#', '<img style="width:100%;" src="', $s);
		foreach (array_keys($images) as $i) {
			$s = str_replace($i, "data:image/jpeg;base64," . $images[$i], $s);
		}
		$content .= $s;
	}
} else {
	$s = $fb2->body->asXML();
	$s = str_replace("<title>", "<subtitle>", $s);
	$s = str_replace("</title>", "</subtitle>", $s);
	$s = str_replace('<image l:href="#', '<img style="width:100%;" src="', $s);
	foreach (array_keys($images) as $i) {
		$s = str_replace($i, "data:image/jpeg;base64," . $images[$i], $s);
	}
	$content .= $s;
}
echo str_replace("<p>***</p>",  '<div class="divider div-transparent div-dot"></div>', str_replace("section>>", "section>", $content));

