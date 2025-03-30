<?php 
echo "<script src='$webroot/js/mobi.min.js'></script>\n"; ?>
<script>
var reader = new FileReader();

fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => {
	new MobiFile(arrayBuffer).render_to("reader");
});
</script>

