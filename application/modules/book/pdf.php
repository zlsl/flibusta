<script src="/js/pdf.js"></script>

<script>
var pdfjsLib = window['pdfjs-dist/build/pdf'];

pdfjsLib.GlobalWorkerOptions.workerSrc = '/js/pdf.worker.js';

var currPage = 1;
var numPages = 0;
var thePDF = null;

pdfjsLib.getDocument(url).promise.then(function(pdf) {
        thePDF = pdf;
        numPages = pdf.numPages;
        pdf.getPage( 1 ).then( handlePages );
});


function handlePages(page) {
    var viewport = page.getViewport( {scale: 1.5} );

	viewer = document.getElementById('reader');
    var canvas = document.createElement( "canvas" );
    canvas.style.display = "block";
    var context = canvas.getContext('2d');

    viewer.appendChild(canvas);            
    canvas.height = viewport.height;
    canvas.width = viewport.width;
    page.render({canvasContext: context, viewport: viewport});
    var line = document.createElement("hr");
    document.body.appendChild( line );

    currPage++;
    if ( thePDF !== null && currPage <= numPages ) {
        thePDF.getPage( currPage ).then( handlePages );
    }
}

</script>

