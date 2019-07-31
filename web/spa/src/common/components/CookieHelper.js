export const getCookie = (key) =>
{
	let pairs = document.cookie.split("; "),
    count = pairs.length, parts;
    for (let i=0; i<pairs.length; i++) {
    	if(pairs[i].split("=")[0] == key) {
    		return pairs[i].split("=")[1];
    	}
    }
    return false;
}

export const setCookie = (key,value,hours=1) =>
{
	let date = new Date();
	date.setTime(date.getTime()+(hours*60*60*1000));
	window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + ";  path=/";
    return value;
}

export const removeCookie = (key,domain='.jeevansathi.com') =>
{
	 document.cookie = key + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;' + '; path=/';
}


/* -- condintion with path name --*/
export const newsetCookie = (key,value) =>
{
	let date = new Date();
	//date.setTime(date.getTime()+(hours*60*60*1000));
	window.document.cookie = key + "=" + value + "; path=/";
    return value;
}
export const newRemoveCookie = (key,domain='.jeevansathi.com') =>
{
	document.cookie = key + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
}
