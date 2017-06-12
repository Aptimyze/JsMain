export function getRoutePath(location) {
	var hash = location.split('#')[1] || ''
	if(hash) hash = "/" + hash
	return hash;
}