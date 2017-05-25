$("#js-cropperOpsClose").on('click',function(){
        closeCropper();
    });

        function  closeCropper()
        {
                $("#commonOverlay").hide();
                $(".js-cropper").hide();
        }
	var cordinatesArray = {};
$(function () {

	'use strict';

	var console = window.console || { log: function () {} };
	var $body = $('body');

	/** LAVESH **/
	$('#js-cropperSave').bind('click', function() {
	
	var imageSource = $('.cropper-canvas').find('img').attr('src');
	//1-D array of preview type
	var imgPreviewTypeArr = ["imgPreviewLG","imgPreviewMD","imgPreviewSM","imgPreviewSS","imgPreviewXS"];

	//send ajax request to actual cropper
	sendProcessCropperRequest(cordinatesArray,imageSource,imgPreviewTypeArr);
	});
	/** LAVESH **/

	// Tooltip
	$('[data-toggle="tooltip"]').tooltip();
	$.fn.tooltip.noConflict();
	$body.tooltip();


	// Demo
	// ---------------------------------------------------------------------------

	(function () {
		var $image = $('.img-container > img');
		var $actions = $('.docs-actions');
		var $download = $('#download');
		var $dataX = $('#dataX');
		var $dataY = $('#dataY');
		var $dataHeight = $('#dataHeight');
		var $dataWidth = $('#dataWidth');
		var $dataRotate = $('#dataRotate');
		var $dataScaleX = $('#dataScaleX');
		var $dataScaleY = $('#dataScaleY');
		var options = {
					aspectRatio: 3 / 4, //<!-- LAVESH -->16 / 9,
		autoCropArea: 1.00,
					preview: '.img-preview',
					crop: function (e) {
						$dataX.val(Math.round(e.x));
						$dataY.val(Math.round(e.y));
						$dataHeight.val(Math.round(e.height));
						$dataWidth.val(Math.round(e.width));
						$dataRotate.val(e.rotate);
						$dataScaleX.val(e.scaleX);
						$dataScaleY.val(e.scaleY);
					}
				};

		$image.on({
			'build.cropper': function (e) {
				//console.log(e.type);
			},
			'built.cropper': function (e) {
				//console.log(e.type);
			},
			'cropstart.cropper': function (e) {
				//console.log(e.type, e.action);
			},
			'cropmove.cropper': function (e) {
				//console.log(e.type, e.action);
			},
			'cropend.cropper': function (e) {
				//console.log(e.type, e.action);
			},
			'crop.cropper': function (e) {
				//console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
	cordinatesArray["x"] = e.x;
	cordinatesArray["y"] = e.y;
	cordinatesArray["w"] = e.width;
	cordinatesArray["h"] = e.height;
			},
			'zoom.cropper': function (e) {
				//console.log(e.type, e.ratio);
			}
		}).cropper(options);


		// Buttons
		if (!$.isFunction(document.createElement('canvas').getContext)) {
			$('button[data-method="getCroppedCanvas"]').prop('disabled', true);
		}

		if (typeof document.createElement('cropper').style.transition === 'undefined') {
			$('button[data-method="rotate"]').prop('disabled', true);
			$('button[data-method="scale"]').prop('disabled', true);
		}


		// Download
		/* lavesh
		if (typeof $download[0].download === 'undefined') {
			$download.addClass('disabled');
		}
		*/


		// Options
		$actions.on('change', ':checkbox', function () {
			var $this = $(this);
			var cropBoxData;
			var canvasData;

			if (!$image.data('cropper')) {
				return;
			}

			options[$this.val()] = $this.prop('checked');

			cropBoxData = $image.cropper('getCropBoxData');
			canvasData = $image.cropper('getCanvasData');
			options.built = function () {
				$image.cropper('setCropBoxData', cropBoxData);
				$image.cropper('setCanvasData', canvasData);
			};

			$image.cropper('destroy').cropper(options);
		});


		// Methods
		$actions.on('click', '[data-method]', function () {
			var $this = $(this);
			var data = $this.data();
			var $target;
			var result;

			if ($this.prop('disabled') || $this.hasClass('disabled')) {
				return;
			}

			if ($image.data('cropper') && data.method) {
				data = $.extend({}, data); // Clone a new one

				if (typeof data.target !== 'undefined') {
					$target = $(data.target);

					if (typeof data.option === 'undefined') {
						try {
							data.option = JSON.parse($target.val());
						} catch (e) {
							console.log(e.message);
						}
					}
				}

				result = $image.cropper(data.method, data.option, data.secondOption);

				switch (data.method) {
					case 'scaleX':
					case 'scaleY':
						$(this).data('option', -data.option);
						break;

					case 'getCroppedCanvas':
						if (result) {

							// Bootstrap's Modal
							$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

						/* lavesh
							if (!$download.hasClass('disabled')) {
								$download.attr('href', result.toDataURL());
							}
				*/
						}

						break;
				}

				if ($.isPlainObject(result) && $target) {
					try {
						$target.val(JSON.stringify(result));
					} catch (e) {
						console.log(e.message);
					}
				}

			}
		});


		// Keyboard
		$body.on('keydown', function (e) {

			if (!$image.data('cropper') || this.scrollTop > 300) {
				return;
			}

			switch (e.which) {
				case 37:
					e.preventDefault();
					$image.cropper('move', -1, 0);
					break;

				case 38:
					e.preventDefault();
					$image.cropper('move', 0, -1);
					break;

				case 39:
					e.preventDefault();
					$image.cropper('move', 1, 0);
					break;

				case 40:
					e.preventDefault();
					$image.cropper('move', 0, 1);
					break;
			}

		});


		// Import image
		var $inputImage = $('#inputImage');
		var URL = window.URL || window.webkitURL;
		var blobURL;

		if (URL) {
			$inputImage.change(function () {
				var files = this.files;
				var file;

				if (!$image.data('cropper')) {
					return;
				}

				if (files && files.length) {
					file = files[0];

					if (/^image\/\w+$/.test(file.type)) {
						blobURL = URL.createObjectURL(file);
						$image.one('built.cropper', function () {
							URL.revokeObjectURL(blobURL); // Revoke when load complete
						}).cropper('reset').cropper('replace', blobURL);
						$inputImage.val('');
					} else {
						$body.tooltip('Please choose an image file.', 'warning');
					}
				}
			});
		} else {
			$inputImage.prop('disabled', true).parent().addClass('disabled');
		}

	}());

});

/** send ajax request to ProcessCropper action to crop image and save to disk
* @param : cordinatesArray,imageSource,imgPreviewTypeArr
**/
function sendProcessCropperRequest(cordinatesArray,imageSource,imgPreviewTypeArr)
{
	var url = '/social/processCropper';
	
    if (typeof imageCopyServer === 'undefined' || imageCopyServer == '')
        url = "/social/processCropper";
    else
        url = "/"+imageCopyServer+"/social/processCropper";
    //alert(url);
	var postSendData = {'cropBoxDimensionsArr':cordinatesArray,'imageSource':imageSource,'imgPreviewTypeArr':imgPreviewTypeArr};
	
	$.myObj.ajax({
		url: url,
		dataType: 'json',
		type: 'POST',
		data: postSendData,
		timeout: 60000,
		beforeSend: function( xhr ) 
		{  
			showCommonLoader();
			//console.log(postSendData);            
		},

		success: function(response) 
		{
			hideCommonLoader();
			var url = '/social/addPhotos?showConf=1';
	
			if (typeof imageCopyServer === 'undefined' || imageCopyServer == '')
        url = "/social/addPhotos?showConf=1";
			else
        url = "/"+imageCopyServer+"/social/addPhotos?showConf=1";
			//console.log("cropped image successfully"); 
			window.location=url;
		},
		error: function(xhr) 
		{
			console.log("error"); //LATER
			return "error";
		}
	});
	return false;
}

function sendOpsProcessCropperRequest()
{
        var imageSource = $('.cropper-canvas').find('img').attr('src');
        var imgPreviewTypeArr = ["imgPreviewLG","imgPreviewMD","imgPreviewSM","imgPreviewSS","imgPreviewXS"];
	imgPreviewTypeArrStr = JSON.stringify(imgPreviewTypeArr, null, 2);
	cordinatesArrayStr = JSON.stringify(cordinatesArray, null, 2);
	$("#cropBoxDimensionsArr").val(cordinatesArrayStr);
	$("#imageSource").val(imageSource);
	$("#imgPreviewTypeArr").val(imgPreviewTypeArrStr);
	$("#ops").val(true);
}

