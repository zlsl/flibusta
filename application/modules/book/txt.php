<?php
$content = $zip->getFromName("$url->var1.txt");
if (!mb_detect_encoding($content, 'UTF-8', true)) {
	$content =iconv('windows-1251//IGNORE', 'UTF-8//IGNORE', $content);
}
$content = nl2p($content);
echo "<section>";
echo str_replace("<p>***</p>",  '<div class="divider div-transparent div-dot"></div>', str_replace("section>>", "section>", $content));
echo "</section>";

