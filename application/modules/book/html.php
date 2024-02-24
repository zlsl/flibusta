<script>
var reader = new FileReader();
fetch(url).then(res => res.arrayBuffer()).then(arrayBuffer => {
	var td = new TextDecoder("windows-1251");
	var htm = td.decode(arrayBuffer);
	document.getElementById("reader").insertAdjacentHTML('beforeend', htm);
});
</script>

