window.addEvent('load', function() {

	// For testing, showing the user the current Flash version.
	//document.getElement('h3 + p').appendText(' Detected Flash ' + Browser.Plugins.Flash.version + '!');
 
	var swiffy = new FancyUpload2($('demo-status'), $('demo-error'), $('demo-list-left'), $('demo-list-right'), {
		url: $('form-demo').action,
		fieldName: 'photoupload',
		path: '/images/Swiff.Uploader.swf',
		limitSize: 'false', // 5Mb
		onLoad: function() {
			//$('demo-status').removeClass('hide');
			//$('demo-fallback').destroy();
		},
		// The changed parts!
		debug: true, // enable logs, uses console.log
		target: 'demo-browse' // the element for the overlay (Flash 10 only)
	});
 
	/**
	 * Various interactions
	 */

	var filter = null;
		filter = {'Images (*.jpg, *.jpeg, *.gif, *.JPG, *.JPEG, *.GIF)': '*.jpg; *.jpeg; *.gif; *.JPG; *.JPEG; *.GIF'};
		swiffy.options.typeFilter = filter;
 
	$('demo-browse').addEvent('click', function() {
		/**
		 * Doesn't work anymore with Flash 10: swiffy.browse();
		 * FancyUpload moves the Flash movie as overlay over the link.
		 * (see opeion "target" above)
		 */
	//	alert("YYYYYYYYYYYYYYYYYYYYYYY");
		swiffy.browse();
		return false;
	});
 
	$('demo-upload').addEvent('click', function() {
		swiffy.upload();
		return false;
	});	
});
