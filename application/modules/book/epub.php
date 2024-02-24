<script src="/js/jszip.min.js"></script>
<script src="/js/epub.min.js"></script>
<script>
const book = ePub({ replacements: 'blobUrl' });
book.open(url, 'epub');
var r = book.renderTo(document.body, {
	flow: "scrolled-doc",
        manager: "continuous",
//        flow: "scrolled",
        width: "69%"
});
r.themes.default({
	p: {
		'font-size': '1.2rem;',
		'font-weight': '400;',
		'line-height': '1.7;',
		'color': '#333;',
		'font-family': 'sans-serif;',
		'margin-bottom': '15px;'
	}
});
var displayed = r.display();

r.reportLocation();


</script>

