<script src="/js/jszip.min.js"></script>
<script src="/js/docx-preview.min.js"></script>
<script>
var reader = new FileReader();
fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => {
	docx.renderAsync(arrayBuffer, document.getElementById("reader"));
});
</script>

