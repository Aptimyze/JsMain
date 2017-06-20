export function getRoutePath(location) {

	var hash = location.split('#')[1] || '';
	if(hash) 
	{
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