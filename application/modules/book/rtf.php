<?php
echo <<< __HTML
<script src="$webroot/js/WMFJS.bundle.js"></script>
<script src="$webroot/js/EMFJS.bundle.js"></script>
<script src="$webroot/js/RTFJS.bundle.js"></script>
<script>
__HTML
?>
var reader = new FileReader();

fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => {
	RTFJS.loggingEnabled(false);
	WMFJS.loggingEnabled(false);
	EMFJS.loggingEnabled(false);

	const doc = new RTFJS.Document(arrayBuffer);

	doc.render().then(html => {
		viewer = document.getElementById('reader');
        	viewer.append(...html);
        });
});
</script>
