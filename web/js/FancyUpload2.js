/**
 * FancyUpload - Flash meets Ajax for powerful and elegant uploads.
 *
 * @version		2.1
 *
 * @license		MIT License
 *
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 */

var FancyUpload2 = new Class({

	Extends: Swiff.Uploader,

	options: {
		limitSize: false,
		limitFiles: 40,
		instantStart: false,
		allowDuplicates: false,
		validateFile: $lambda(true), // provide a function that returns true for valid and false for invalid files.
		debug: false,

		fileInvalid: null, // called for invalid files with error stack as 2nd argument
		fileCreate: null, // creates file element after select
		fileUpload: null, // called when file is opened for upload, allows to modify the upload options (2nd argument) for every upload
		fileComplete: null, // updates the file element to completed state and gets the response (2nd argument)
		fileRemove: null // removes the element
		/**
		 * Events:
		 * onBrowse, onSelect, onAllSelect, onCancel, onBeforeOpen, onOpen, onProgress, onComplete, onError, onAllComplete
		 */
	},

	initialize: function(status, errorlist, list1, list2, options) {
		this.status = $(status);
		this.errorlist = $(errorlist);
		this.list1 = $(list1);
		this.list2 = $(list2);

		this.files = [];
		this.bytesTotal = 0;
		this.uploadFileCounter = 1;
		this.errorFileCount = 0;
		this.errorFileCount1 = 0;
		this.loaderWidth = 0;
		this.loaderIncrement = 0;
		this.formatErrCount = 0;

		if (options.callBacks) {
			this.addEvents(options.callBacks);
			options.callBacks = null;
		}

		this.parent(options);
		this.render();
	},

	render: function() {
		this.currentTitle = this.status.getElement('.current-title');
		this.currentText = this.status.getElement('.current-text');
		var progress = this.status.getElement('.overall-progress');
		this.overallProgress = new Fx.ProgressBar(progress, {
			text: new Element('span', {'class': 'progress-text'}).inject(progress, 'after')
		});
		progress = this.status.getElement('.current-progress')
		this.currentProgress = new Fx.ProgressBar(progress, {
			text: new Element('span', {'class': 'progress-text'}).inject(progress, 'after')
		});
	},

	onLoad: function() {
		this.log('Uploader ready!');
	},

	onBeforeOpen: function(file, options) {
		this.log('Initialize upload for "{name}".', file);
		var fn = this.options.fileUpload;
		var obj = (fn) ? fn.call(this, this.getFile(file), options) : options;
		return obj;
	},

	onOpen: function(file, overall) {
		this.log('Starting upload "{name}".', file);
		file = this.getFile(file);
		file.element.addClass('file-uploading');
		this.currentProgress.cancel().set(0);
		this.currentTitle.set('html', 'File Progress "{name}"'.substitute(file) );
	},

	onProgress: function(file, current, overall) {
		this.overallProgress.start(overall.bytesLoaded, overall.bytesTotal);
		this.currentText.set('html', 'Upload with {rate}/s. Time left: ~{timeLeft}'.substitute({
			rate: (current.rate) ? this.sizeToKB(current.rate) : '- B',
			timeLeft: Date.fancyDuration(current.timeLeft || 0)
		}));
		this.currentProgress.start(current.bytesLoaded, current.bytesTotal);
	},

	onSelect: function(file, index, length) {
		var errors = [];
		if (this.options.limitSize && (file.size > this.options.limitSize)) errors.push('size');
		if (this.options.limitFiles && (this.countFiles() >= this.options.limitFiles)) errors.push('length');
		if (!this.options.allowDuplicates && this.getFile(file)) errors.push('duplicate');
		if (!this.options.validateFile.call(this, file, errors)) errors.push('custom');
		if (errors.length) {
			var fn = this.options.fileInvalid;
			if (fn) fn.call(this, file, errors);
			return false;
		}
		(this.options.fileCreate || this.fileCreate).call(this, file);
		this.files.push(file);
		return true;
	},

	onAllSelect: function(files, current, overall) {

				document.getElementById("upload_error").style.display = "none";
				document.getElementById("error_display1").style.display = "none";
				document.getElementById("error_display2").style.display = "none";
				document.getElementById("error_display4").style.display = "none";

		if (parseInt(document.getElementById("demo-list-left").offsetHeight) >= 160)
		{
			document.getElementById("demo-status").style.height = "auto";
		}
		document.getElementById("upload-err").style.display = "none";
		document.getElementById("demo-status").style.display = "block";
		var actualUploadCount = parseInt(document.getElementById("actualUploadCount").value);
		var maxFileSize = parseInt(document.getElementById("maxFileSize").value);
		
		this.log('Added ' + files.length + ' files, now we have (' + current.bytesTotal + ' bytes).', arguments);
		
		var largeFileCount = this.checkSize(maxFileSize);

		var checkedFilesCount = this.alertForChecked();
		
		if (checkedFilesCount>actualUploadCount)
		{
			document.getElementById("totalFileList").value = checkedFilesCount;
			document.getElementById("upload_error").style.display = "block";
			if (largeFileCount > 0)
			{
				document.getElementById("totalLargeFiles").value = largeFileCount;
				document.getElementById("error_display1").style.display = "block";
				document.getElementById("error_display2").style.display = "block";
			}
			else
			{
				document.getElementById("error_display1").style.display = "block";
			}
		}
		else
		{
			if (largeFileCount > 0)
			{
				document.getElementById("upload_error").style.display = "block";
				document.getElementById("totalLargeFiles").value = largeFileCount;
				document.getElementById("error_display2").style.display = "block";
			}
			else
			{
			}
		}

		//this.updateOverall(current.bytesTotal);
		document.getElementById("upload_btn").style.display = "block";
		this.updateOverall(this.bytesTotal);
		this.status.removeClass('status-browsing');
		if (this.files.length && this.options.instantStart) this.upload.delay(10, this);
		parent.resizeFrame();
	},

	checkSize: function(maxFileSize){
		var i=0;
		var count = 0;
		this.bytesTotal = 0;
		for (i=0;i<this.files.length;i++)
		{
			if(this.files[i].size>maxFileSize*1024*1024)
			{
				count++;
			}
			else
			{
				if (document.getElementById(i).checked)
					this.bytesTotal = this.bytesTotal+this.files[i].size;
			}
		}
		return count;
	},

	onComplete: function(file, response) {
		this.log('Completed upload "' + file.name + '".', arguments);
		this.loaderWidth = this.loaderWidth+this.loaderIncrement;
		document.getElementById("rect_loader").style.width = this.loaderWidth+"%";
		document.getElementById("uploadFileNo").innerHTML = this.uploadFileCounter;
		this.uploadFileCounter = this.uploadFileCounter+1;
		this.currentText.set('html', 'Upload complete!');
		this.currentProgress.start(100);
		(this.options.fileComplete || this.fileComplete).call(this, this.finishFile(file), response);
	},

	onError: function(file, error, info) {
		this.errorFileCount1 = this.errorFileCount1+1;
		this.log('Upload "' + file.name + '" failed. "{1}": "{2}".', arguments);
		(this.options.fileError || this.fileError).call(this, this.finishFile(file), error, info);
	},

	onCancel: function() {
		this.log('Filebrowser cancelled.', arguments);
		this.status.removeClass('file-browsing');
	},

	onAllComplete: function(current) {
		this.log('Completed all files, ' + current.bytesTotal + ' bytes.', arguments);
		this.updateOverall(current.bytesTotal);
		this.overallProgress.start(100);
		this.status.removeClass('file-uploading');
		if (this.errorFileCount1 == this.files.length)
		{
			var i=0;
                      	for (i=0;i<this.files.length;i++)
                       	{
                            	this.removeFile(this.files[i]);
                              	i--;
                    	}
                     	document.getElementById("demo-loader").style.display = "none";
                   	document.getElementById("upload-err").style.display = "block";
                   	this.updateOverall(0);
			parent.resizeFrame();	
		}
		else
		{
			var successCount = this.files.length - this.errorFileCount;
			if (successCount)
			{
				var params = "successCount="+successCount;
        			var url = "/social/flashMisEntry";
        			sendRequest('POST',url,params);
			}
	
			if (this.errorFileCount)
			{
				parent.location.href= "/social/saveImage?err="+successCount+"&format="+this.formatErrCount+"&total="+this.files.length+"&successCount="+successCount;
			}
			else
			{
				parent.location.href= "/social/saveImage?successCount="+successCount;
			}
		}
	},

	browse: function(fileList) {
		var ret = this.parent(fileList);
		if (ret !== true){
			if (ret) this.log('An error occured: ' + ret);
			else this.log('Browse in progress.');
		} else {
			this.log('Browse started.');
			this.status.addClass('file-browsing');
		}
	},

	alertDisplay: function(actualUploadCount) {

		var arr = new Array();
		var i = 0;
		var index = 0;	
		if (document.zzz.pictures.length)
		{
			for (i=0;i<document.zzz.pictures.length;i++)
			{
				arr[i] = document.zzz.pictures[i].value;
			}
	
			for (i=0;i<this.files.length;i++)
			{
				index = arr.indexOf(this.files[i].name);
				if (!document.zzz.pictures[index].checked)
				{
					this.removeFile(this.files[i]);
					i--;
				}
			}
		}
		else
		{
			if (!document.zzz.pictures.checked)
			{
				this.removeFile(this.files[i]);
				i--;
			}
		}
		
		if (this.files.length>actualUploadCount)
		{
			for (i=actualUploadCount;i<this.files.length;i++)
			{
				this.removeFile(this.files[i]);
				i--;
			}
		}		
	},

	alertForChecked: function() {
		var arr = new Array();
                var i = 0;
                var index = 0;
		var output = 0;
		if (document.zzz.pictures.length)
		{
                        for (i=0;i<document.zzz.pictures.length;i++)
                        {
                                arr[i] = document.zzz.pictures[i].value;
                        }

                        for (i=0;i<this.files.length;i++)
                        {
                                index = arr.indexOf(this.files[i].name);
                                if (document.zzz.pictures[index].checked)
                                {
                   			output++;	                    
                                }
                        }
		}
		else
		{
			if (document.zzz.pictures.checked)
			{
				output++;
			}
		}
		return output;
	},

	upload: function(options) {
		window.parent.scroll(200,200);
			document.getElementById("upload_error").style.display = "none";
			document.getElementById("error_display1").style.display = "none";
			document.getElementById("error_display4").style.display = "none";
		document.getElementById("upload_btn").style.display = "none";
		var output = this.alertForChecked();
		if (output == 0)
		{
			document.getElementById("upload_error").style.display = "block";
			document.getElementById("error_display4").style.display = "block";
			document.getElementById("demo-status").style.display = "block";
                	document.getElementById("demo-loader").style.display = "none";
                	document.getElementById("browse_flash_button").style.visibility = "visible";
			document.getElementById("upload_btn").style.display = "block";
			parent.resizeFrame();
			return false;
		}
		else
		{
			document.getElementById("upload_error").style.display = "none";
			document.getElementById("error_display2").style.display = "none";
			document.getElementById("direction_text").style.display = "none";
			document.getElementById("bottom-text").style.display = "none";
			document.getElementById("demo-status1").style.opacity ="0";
			document.getElementById("demo-status1").style.filter ="alpha(opacity=0)";
			document.getElementById("demo-status").style.display = "none";
			document.getElementById("demo-loader").style.display = "block";
			document.getElementById("browse_flash_button").style.visibility = "hidden";
			var actualUploadCount = parseInt(document.getElementById("actualUploadCount").value);
			this.alertDisplay(actualUploadCount);
		}
	//	document.getElementById("demo-status").style.display = "none";
	//	document.getElementById("demo-loader").style.display = "block";
		//document.getElementById("enable_upload1").style.display = "none";
		//document.getElementById("disable_upload1").style.display = "block";
	//	document.getElementById("browse_flash_button").style.visibility = "hidden";
	//	var actualUploadCount = parseInt(document.getElementById("actualUploadCount").value);
	//	this.alertDisplay(actualUploadCount);
	//	if (this.files.length<1)
	//	{
	//		document.getElementById("error_display4").style.display = "block";
	//		document.getElementById("demo-status").style.display = "block";
        // 	      	document.getElementById("demo-loader").style.display = "none";
        //        	document.getElementById("browse_flash_button").style.visibility = "visible";
	//	}
		var ret = this.parent(options);
		if (ret !== true) {
			this.log('Upload in progress or nothing to upload.');
			if (ret) 
			{
				//alert(ret);
				var i=0;
				for (i=0;i<this.files.length;i++)
				{
					this.removeFile(this.files[i]);
                                	i--;
				}
                		document.getElementById("demo-loader").style.display = "none";
				document.getElementById("upload-err").style.display = "block";
				this.updateOverall(0);
			}
		} else {
			this.log('Upload started.');
			this.status.addClass('file-uploading');
			this.overallProgress.set(0);
			document.getElementById("uploadFileNo").innerHTML = "0";
			document.getElementById("totalFileToUpload").innerHTML = this.files.length;
			this.loaderIncrement = (100/this.files.length);
			document.getElementById("rect_loader").style.width = this.loaderWidth+"%";
			document.getElementById("uploadProgressIndicator").style.display = "block";
		}
		parent.resizeFrame();
	},

	removeFile: function(file) {
		var remove = this.options.fileRemove || this.fileRemove;
		if (!file) {
			this.files.each(remove, this);
			this.files.empty();
			this.updateOverall(0);
		} else {
			if (!file.element) file = this.getFile(file);
			this.files.erase(file);
			remove.call(this, file);
			//this.updateOverall(this.bytesTotal - file.size);
		}
		this.parent(file);
	},

	getFile: function(file) {
		var ret = null;
		this.files.some(function(value) {
			if ((value.name != file.name) || (value.size != file.size)) return false;
			ret = value;
			return true;
		});
		return ret;
	},

	countFiles: function() {
		var ret = 0;
		for (var i = 0, j = this.files.length; i < j; i++) {
			if (!this.files[i].finished) ret++;
		}
		return ret;
	},

	updateOverall: function(bytesTotal) {
		this.bytesTotal = bytesTotal;
		document.getElementById("total_size").innerHTML = "Total Size " + this.sizeToKB(bytesTotal);
	},

	finishFile: function(file) {
		file = this.getFile(file);
		file.element.removeClass('file-uploading');
		file.finished = true;
		return file;
	},

	fileCreate: function(file) 
	{
		var fileName = file.name;
		if (fileName.length>25)
		{
			fileName = fileName.substr(0,25)+"...";
		}
		var maxFileSize = parseInt(document.getElementById("maxFileSize").value);
		
		if (file.size>maxFileSize*1024*1024)
		{
			if ((this.files.length%2)==0)
			{
				file.element = new Element('span', {'class': 'file'}).adopt(
				//new Element('span', {'class': 'file-size', 'html': this.sizeToKB(file.size)}),
				new Element ('input', {	
						 	'type': 'checkbox',
							'id': this.files.length,
							'name': 'pictures', 
							'value': file.name,
							'disabled': 'disabled'
							}),
				new Element('span', {'class': 'no_b', 'style': 'color: red; font-weight: bold', 'html': ' &nbsp '+fileName}),
				new Element('span', {'class': 'no_b', 'html': ' (Size exceeded)'})
				).inject(this.list1);
			}
			else
			{
				file.element = new Element('span', {'class': 'file'}).adopt(
				//new Element('span', {'class': 'file-size', 'html': this.sizeToKB(file.size)}),
				new Element ('input', {	
						 	'type': 'checkbox',
							'id': this.files.length,
							'name': 'pictures', 
							'value': file.name,
							'disabled': 'disabled'
							}),
				new Element('span', {'class': 'no_b', 'style': 'color: red; font-weight: bold', 'html': ' &nbsp '+fileName}),
				new Element('span', {'class': 'no_b', 'html': ' (Size exceeded)'})
				).inject(this.list2);
			}
		}
		else
		{
			if ((this.files.length%2)==0)
			{
				file.element = new Element('span', {'class': 'file'}).adopt(
				//new Element('span', {'class': 'file-size', 'html': this.sizeToKB(file.size)}),
				new Element ('input', {	
						 	'type': 'checkbox',
							'id': this.files.length,
							'name': 'pictures', 
							'value': file.name,
							'checked': 'checked',
							'events': {
								'click': function(id) {
									if(document.getElementById(id.target.id).checked)
										{
										this.updateOverall(this.bytesTotal + file.size);		
										document.getElementById(id.target.id).blur();
										}
									else
										{
										this.updateOverall(this.bytesTotal - file.size);
										document.getElementById(id.target.id).blur();
										}									
									return true;
									}.bind(this)
								}
							}),
				new Element('span', {'class': 'no_b', 'html': ' &nbsp '+fileName})
				).inject(this.list1);
			}
			else
			{
				file.element = new Element('span', {'class': 'file'}).adopt(
				//new Element('span', {'class': 'file-size', 'html': this.sizeToKB(file.size)}),
				new Element ('input', {	
						 	'type': 'checkbox',
							'id': this.files.length,
							'name': 'pictures', 
							'value': file.name,
							'checked': 'checked',
							'events': {
								'click': function(id) {
									if(document.getElementById(id.target.id).checked)
										{
										this.updateOverall(this.bytesTotal + file.size);		
										document.getElementById(id.target.id).blur();
										}
									else
										{
										this.updateOverall(this.bytesTotal - file.size);
										document.getElementById(id.target.id).blur();
										}									
									return true;
									}.bind(this)
								}
							}),
				new Element('span', {'class': 'no_b', 'html': ' &nbsp '+fileName})
				).inject(this.list2);
			}
		}
	},

	fileComplete: function(file, response) {
		this.options.processResponse || this
		var json = $H(JSON.decode(response, true));
		if (json.get('result') == 'success') {
			file.element.addClass('file-success');
			file.info.set('html', json.get('size'));
		} else {
			file.element.addClass('file-failed');
			if (json.get('error') == 'FormatError')
			{
				this.formatErrCount = this.formatErrCount+1;
			}
			this.errorFileCount = this.errorFileCount+1;
			file.info.set('html', json.get('error') || response);
		}
	},

	fileError: function(file, error, info) {
		file.element.addClass('file-failed');
		file.info.set('html', '<strong>' + error + '</strong><br />' + info);
	},

	fileRemove: function(file) {
		file.element.fade('out').retrieve('tween').chain(Element.destroy.bind(Element, file.element));
	},

	sizeToKB: function(size) {
		var unit = 'B';
		if ((size / 1048576) > 1) {
			unit = 'MB';
			size /= 1048576;
		} else if ((size / 1024) > 1) {
			unit = 'kB';
			size /= 1024;
		}
		return size.round(2) + unit;
	},

	log: function(text, args) {
		if (this.options.debug && window.console) console.log(text.substitute(args || {}));
	}

});

/**
 * @todo Clean-up, into Date.js
 */
Date.parseDuration = function(sec) {
	var units = {}, conv = Date.durations;
	for (var unit in conv) {
		var value = Math.floor(sec / conv[unit]);
		if (value) {
			units[unit] = value;
			if (!(sec -= value * conv[unit])) break;
		}
	}
	return units;
};

Date.fancyDuration = function(sec) {
	var ret = [], units = Date.parseDuration(sec);
	for (var unit in units) ret.push(units[unit] + Date.durationsAbbr[unit]);
	return ret.join(', ');
};

Date.durations = {years: 31556926, months: 2629743.83, days: 86400, hours: 3600, minutes: 60, seconds: 1, milliseconds: 0.001};
Date.durationsAbbr = {
	years: 'j',
	months: 'm',
	days: 'd',
	hours: 'h',
	minutes: 'min',
	seconds: 'sec',
	milliseconds: 'ms'
};
