<script src="/js/mobi.min.js"></script>
<script>
var reader = new FileReader();

fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => {
	new MobiFile(arrayBuffer).render_to("reader");
});
</script>

