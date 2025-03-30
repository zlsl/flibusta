<?php
include_once(ROOT_PATH . "webroot.php");
echo "<script src='$webroot/js/djvu.js'></script>\n";
echo "<script src='$webroot/js/djvu_viewer.js'></script>\n";
?>
<script>
window.ViewerInstance = new DjVu.Viewer();
window.ViewerInstance.render(
        document.querySelector("#reader")
);
window.ViewerInstance.configure({
	viewMode: 'single', //'continuous',
	language: 'ru'
});
window.ViewerInstance.loadDocumentByUrl(url);
window.ViewerInstance.configure({
	viewMode: 'single', //'continuous',
	language: 'ru'
});
</script>

