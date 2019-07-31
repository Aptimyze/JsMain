export function getRoutePath(location,hash1 ="")
{
	var hash = hash1 || (location.split('#')[1] || '')
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

export function getSearchListingUrl(loc,hash1="")
{
	if ( loc.indexOf("search/perform") != -1 )
	{
		var tempLocation = loc.split('?')[1] || '';
		tempLocation = tempLocation.split('=')[0] || '';
		if(hash1)
			return "/search/"+tempLocation+"?"+hash1.replace("search/perform?","");
		else
			return "/search/"+tempLocation+"?"+window.location.href.split("#")[1].replace("search/perform?","");
	}
	else
		return false;
}

export function getInboxListingUrl(loc,hash1="")
{
	if ( loc.indexOf("inbox/jsmsPerform") != -1 )
	{
		let searchid = getParameterByName(loc,"searchId");
		
		if ( searchid.toString() == '5' )
		{
			if(hash1)
				return "/search/visitors"+"?"+hash1.replace("inbox/jsmsPerform?","");
			else
				return "/search/visitors"+"?"+window.location.href.split("#")[1].replace("inbox/jsmsPerform?","");
		}
		if(hash1)
			return "/inbox/"+searchid+"?"+hash1.replace("inbox/jsmsPerform?","");
		else
			return "/inbox/"+searchid+"?"+window.location.href.split("#")[1].replace("inbox/jsmsPerform?","");
	}
	else
		return false;
}
