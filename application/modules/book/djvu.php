<script src="/js/djvu.js"></script>
<script src="/js/djvu_viewer.js"></script>
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

