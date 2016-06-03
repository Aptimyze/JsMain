/*
		dw_clip.js - clip methods for use with dw_core.js
		developed from study of code from dynduo, brainjar and bratta
		i.e., http://www.dansteinman.com/dynduo/
		http://www.brainjar.com/ and http://www.dhtmlcentral.com/
		
		This code is from Dynamic Web Coding 
    at http://www.dyn-web.com/
    See Terms of Use at http://www.dyn-web.com/bus/terms.html
    Permission granted to use this code 
    as long as this entire notice is included.
*/

dynObj.prototype.getClipValues = function() {
  if (typeof this.css.clip != "undefined") {
    var clipVal = new Array();
    if (typeof this.css.clip.top != "undefined") {
  		clipVal[0] = this.css.clip.top;
  		clipVal[1] = this.css.clip.right;
  		clipVal[2] = this.css.clip.bottom;
  		clipVal[3] = this.css.clip.left;
  	} else {
  		clipVal = this.css.clip.slice(5,-1).split(' ');
  			for (var i=0; i<4; i++) {
  				clipVal[i] = parseInt(clipVal[i]);
  			}
    } 
    return clipVal;
  } 
  else return false;
}

dynObj.prototype.clipBy = function(top,rt,btm,lft) {
	if (typeof this.css.clip != "undefined") {
    if (typeof this.css.clip.top != "undefined") {
    	this.css.clip.top += top;
  		this.css.clip.right += rt;
  		this.css.clip.bottom += btm;
  		this.css.clip.left += lft;
    } else {
      var clipVal = this.getClipValues();
    	this.css.clip = "rect(" + Number(clipVal[0]+top) + "px, " + Number(clipVal[1]+rt)  + "px, " + Number(clipVal[2]+btm) + "px, " + Number(clipVal[3]+lft) + "px)";
    } 
  }
}

dynObj.prototype.clipTo = function(top,rt,btm,lft) {
	 if (typeof this.css.clip != "undefined") {
    if (typeof this.css.clip.top != "undefined") {
    	this.css.clip.top = top;
  		this.css.clip.right = rt;
  		this.css.clip.bottom = btm;
  		this.css.clip.left = lft;
    } else {
      this.css.clip = "rect("+top+"px, "+rt+"px, "+btm+"px, "+lft+"px)";
    }
  }
}

