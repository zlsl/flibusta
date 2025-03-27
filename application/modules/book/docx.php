<?php
include_once(ROOT_PATH . "webroot.php");
echo "<script src='$webroot/js/jszip.min.js'></script>\n";
echo "<script src='$webroot/js/docx-preview.min.js'></script>\n";
?>
<script>
var reader = new FileReader();
fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => {
	docx.renderAsync(arrayBuffer, document.getElementById("reader"));
});
</script>

