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
	window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + ";domain=.jeevansathi.com;  path=/";
    return value;
}

export const removeCookie = (key) =>
{
	 document.cookie = key + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;'+ ';domain=.jeevansathi.com; path=/';
}