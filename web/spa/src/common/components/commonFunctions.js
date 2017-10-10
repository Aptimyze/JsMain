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


export const removeClass = (ele,className) => {
	var array=[];
	if(Object.prototype.toString.call( ele ) !== '[object Array]')
	 	array[0] = ele;
	else array = ele;
	var re = new RegExp(" "+className+" ","g");
		for(var i=0;i<array.length;i++){
    array[i].className = (' '+array[i].className+' ').replace(re,' ');
}
}
export const addClass = (ele,className) => {
var array=[];
if(Object.prototype.toString.call( someVar ) !== '[object Array]')
 	array[0] = ele;
else array = ele;
	for(var i=0;i<array.length;i++){
  array[i].className = array[i].className + " "+className;
	}
}

export const $i = (id) => {

return document.getElementById(id);

}


export const $c = (className) => {

return document.getElementsByClassName(className);

}
