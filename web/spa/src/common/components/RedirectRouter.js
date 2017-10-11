import { removeCookie } from '../../common/components/CookieHelper';

export const redirectToLogin = (history,responseStatusCode) =>
{
    if(responseStatusCode == 9)
    {
        removeCookie("AUTHCHECKSUM");
        history.push('/login');
    }
}