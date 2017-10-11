/* 
		dw_wipes.js		wipe methods for dynObj 
		(requires dw_core.js, dw_clip.js, and dw_util.js)
		version date: October 2002 (this.wiping prop added)
		
		This code is from Dynamic Web Coding  
		at http://www.dyn-web.com/
    Copyright 2002 by Sharon Paine 
    See Terms of Use at http://www.dyn-web.com/bus/terms.html
    Permission granted to use this code 
    as long as this entire notice is included.
		
		Idea and math for time-based animation from:
		Aaron Boodman at www.youngpup.net 
		and Mike Foster at www.cross-browser.com
*/

// wipe called can be prevented
var wipe_halt = false;	

// args: which wipe, delay, wipeTime, what next (fn)
dynObj.prototype.wipe=function(which,delay,wipeTime,fn) {
	if (wipe_halt||this.wiping) return;
	this.wipeTime=wipeTime||1000; this.delay=delay||100; this.fn=fn;
	switch (which) {
		// wipe into view from top to bottom
		case "in top" :
			this.clipTo(0,0,0,0);
			this.show();
			setTimeout(this.obj+".wipe_in_top()",this.delay);
			//setTimeout(this.obj+".wipe_out_right()",5000);
			//setTimeout(testWipe('out right'),10000);
			setTimeout(this.obj+".wipe('out bottom',800,800)",5000);
		break;
		
		// wipe out of view from top to bottom
		case "out bottom" :
			setTimeout(this.obj+".wipe_out_bottom()",this.delay);
		break;
		
  	default:
			alert("Oops! Check choices again.");
	}
	this.wipeStart = new Date().getTime()+this.delay;
	this.per = Math.PI/(2*this.wipeTime);
}

// wipe into view from top to bottom
dynObj.prototype.wipe_in_top=function() {
	this.wiping = true;
	var clipVal = this.getClipValues();
	var elapsed = (new Date().getTime())-this.wipeStart;
	if (elapsed<this.wipeTime) {
	var inc = this.height*((1/this.wipeTime)*elapsed);
	this.clipTo(0,this.width,inc,0);
	//this.clipTo(inc,this.width,0,0);
	setTimeout(this.obj+".wipe_in_top()",35);
	} else {
		this.clipTo(0,this.width,this.height,0);
		this.wiping = false;
		if (this.fn) eval(this.fn);
	}
}


// wipe out of view from top to bottom
dynObj.prototype.wipe_out_bottom=function() {
	this.wiping = true;
	var clipVal = this.getClipValues();
	var elapsed = (new Date().getTime())-this.wipeStart;
	if (elapsed<this.wipeTime) {
		var inc = this.height*((1/this.wipeTime)*elapsed);
		this.clipTo(inc,this.width,this.height,0);
		setTimeout(this.obj+".wipe_out_bottom()",35);
	} else {
	this.clipTo(0,this.width,this.height,this.width);
		this.wiping = false;
		if (this.fn) eval(this.fn);
	}
}


