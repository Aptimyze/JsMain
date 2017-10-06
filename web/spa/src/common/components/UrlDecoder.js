export function getRoutePath(location)
{
	var hash = location.split('#')[1] || '';

  if(hash)
	{
		hash = hash.replace(/^\/+/g, '');
		hash = "/" + hash;
	}
	else
	{
		if ( location.indexOf('spa/dist/index.html') !== -1 )
		{		
			hash = '/myjs';
		}
	}
	return hash;
}

export function getParameterByName(url, name)
{
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

export function stripTrailingSlash(str) {
    if(str.substr(-1) === '/') {
        return str.substr(0, str.length - 1);
    }
    return str;
}
