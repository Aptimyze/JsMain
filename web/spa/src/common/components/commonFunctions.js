export const getIosVersion = (inputUa) => {
	let ua = inputUa || navigator.userAgent;
	let match = ua.match(/(iPhone);/i);
	let OsVersion = ua.match(/OS\s[0-9.]*/i);
	if(OsVersion != null) {
		if(OsVersion[0].substring(3,5)>=7) {
			return true;
		} 
	} 
	return false;
}

export const getAndroidVersion = (inputUa) => {
	let ua = inputUa || navigator.userAgent;
	let android = ua.indexOf("Android");
	let match = ua.match(/Android\s([0-9\.]*)/);
	let mobile = ua.indexOf("Mobile");
 	let operaMini = ua.indexOf("Opera Mini");
	if(android != -1) {
		if(typeof(parseFloat(match[1]))=='number') {
			let androidVersion=match[1].substring(0,3);
	   			if(androidVersion>2.3) {
	   				return true;	
	   			}	
		} else if(match == null) {
			return true;
		}	
	}
	return false;
}
export const getCookieData = ( name ) => {
    let pairs = document.cookie.split("; "),
    count = pairs.length, parts;
    for (let i=0; i<pairs.length; i++) {
    	if(pairs[i].split("=")[0] == name) {
    		return pairs[i].split("=")[1];
    	}
    }
    return false;
}
export const writeCookie = (key, value, hours) => {
  	let date = new Date();
	date.setTime(date.getTime()+(hours*60*60*1000));
	window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + "; path=/";
    return value;
  }

