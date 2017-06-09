import Cookies from 'universal-cookie';

export function getCookie(key='x')
{
	const cookies = new Cookies();
	return cookies.get(key);
}