export const SET_AUTHCHECKSUM =  'SET_AUTHCHECKSUM';
export const AUTHCHECKSUM =  'AUTHCHECKSUM';
export const LOGIN_ATTEMPT_COOKIE =  'loginAttemptNew';
export const DISPLAY_PROPS = {windowWidth : window.innerWidth};
window.addEventListener('resize',()=>{DISPLAY_PROPS.windowWidth = window.innerWidth;});
export const LOGGED_OUT_PAGE = ['/profile/viewprofile.php','/PageNotFound','search/topSearchBand'];
export const SPA_PAGE = ['/','/profile/viewprofile.php','/PageNotFound','/login','/myjs','/social/MobilePhotoAlbum','/static/forgotPassword','profile/mainmenu.php','search/topSearchBand','P/logout.php','mobile_view'];
export const JSB9_UNLOAD_TRACKING = true;
export const RESPONSE_STATUS_MESSAGE = ['No matches found. Please search again with relaxed criteria.'];