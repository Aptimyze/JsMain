import Cookies from 'universal-cookie';

export function getCookie(key)
{
	const cookies = new Cookies();
	console.log("I am in get cookie: " + key);
	return cookies.get(key);
}
export function setCookie(key,value)
{
	const cookies = new Cookies();
	return cookies.set(key,value,{ path: '/' });
}
