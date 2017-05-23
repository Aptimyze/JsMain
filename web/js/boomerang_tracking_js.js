function BOOMRcreateCookie(name,value,min) {
        if (min) {
            var date = new Date();
            date.setTime(date.getTime()+(min*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }
function BOOMRreadCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0)
                        return c.substring(nameEQ.length,c.length);
    }
    return "";
}
function BOOMReraseCookie(name) {
    BOOMRcreateCookie(name,"",-1);
}
var session_id = BOOMRreadCookie("boomerang");
if(!session_id)
	BOOMRcreateCookie("boomerang","1",100);

//pixel denstiy,pixel orientation, screen width
var p_d="";
var p_o="";
var s_w="";
if(typeof($)!='undefined')
{
        p_d=$(document).width();   // returns width of browser viewport
        if(window.orientation)
         p_o=window.orientation;
        s_w=screen.width; // returns width of HTML document
}
else
{
	s_w=screen.width;
	if(document.body && document.body.clientWidth)
	p_d=document.body.clientWidth;
}
var _canvasSupport = false;
var _videoSupport  = false;
var _localstorageSupport = false;
var _webworkersSupport = false;
var _applicationcacheSupport = false;
var _geolocationSupport = false;
var _formdateSupport = false;
var _formplaceholderSupport = false;
var _formsautofocusSupport = false;
var _html5historySupport = false;

if (Modernizr.canvas) {
  _canvasSupport = true;
}

if (Modernizr.video) {
 _videoSupport = true;
}

if (Modernizr.localstorage) {
_localstorageSupport = true;
}

if (Modernizr.webworkers) {
_webworkersSupport = true;
}

if (Modernizr.applicationcache) {
_applicationcacheSupport = true;
}

if (Modernizr.geolocation) {
_geolocationSupport = true;
}

if (Modernizr.inputtypes.date) {
_formdateSupport = true;
}

if (Modernizr.input.placeholder) {
_formplaceholderSupport = true;
}

if (Modernizr.input.autofocus) {
_formsautofocusSupport = true;
}

if (Modernizr.history) {
_html5historySupport = true;
}
/*css3 features*/
var _fontface = false;
var _backgroundsize = false;
var _borderimage = false;
var _borderradius = false;
var _boxshadow = false;
var _flexbox = false;
var _opacity = false;
var _cssanimations = false;
var _cssgradients = false;
var _cssreflections = false;
var _csstransforms = false;
var _csstransitions = false;
var _mediaqueries = false;

if(typeof window.matchMedia == 'function'){
_mediaqueries = true;
}
if (Modernizr.fontface) {
_fontface = true;
}
if (Modernizr.backgroundsize) {
_backgroundsize = true;
}
if (Modernizr.borderimage) {
_borderimage = true;
}
if (Modernizr.borderradius) {
_borderradius = true;
}
if (Modernizr.boxshadow) {
_boxshadow = true;
}
if (Modernizr.flexbox) {
_flexbox = true;
}
if (Modernizr.opacity) {
_opacity = true;
}
if (Modernizr.cssanimations) {
_cssanimations = true;
}
if (Modernizr.cssgradients) {
_cssgradients = true;
}
if (Modernizr.cssreflections) {
_cssreflections = true;
}
if (Modernizr.csstransforms) {
_csstransforms = true;
}
if (Modernizr.csstransitions) {
_csstransitions = true;
}
	if(!session_id)
	{
	BOOMR.init({
		beacon_url: "/beacon.php",
		site_domain: "/",
		user_ip: null,
		log:null,
		BW: {
			enabled: false,
			base_url: "/P/images/mobilejs/boomerang/images/"
		},
		DNS: {
			enabled: false,
			base_url: "/P/images/mobilejs/boomerang/images/"
		}
	});
	}
	var t_bodyend = new Date().getTime();                        
      BOOMR.plugins.RT.setTimer("t_head", t_headend - t_pagestart).
                       setTimer("t_body", t_bodyend - t_headend).  
                       setTimer("t_js", t_bodyend - t_jsstart)
    BOOMR.addVar("hml5canvas", _canvasSupport);
	BOOMR.addVar("hml5video", _videoSupport);
	BOOMR.addVar("hml5localstorage", _localstorageSupport);
	BOOMR.addVar("hml5webworkers", _webworkersSupport);
	BOOMR.addVar("hml5applicationcache", _applicationcacheSupport);
	BOOMR.addVar("hml5geolocation", _geolocationSupport);
	BOOMR.addVar("hml5frmdate", _formdateSupport);
	BOOMR.addVar("hml5frmsautofocus", _formsautofocusSupport);
	BOOMR.addVar("hml5history", _html5historySupport);
	//BOOMR.addVar("ip_address", ip_address);
	BOOMR.addVar("ip_address", 127001);
	BOOMR.addVar("session_id", session_id);
    //BOOMR.addVar("userid", logged_in_userid);
	BOOMR.addVar("user_agent", navigator.userAgent);
	//pixel,orientation,width
	BOOMR.addVar("pd", p_d);
	BOOMR.addVar("po", p_o);
	BOOMR.addVar("sw", s_w);
	//BOOMR.addVar("boomr_pageid", "");

	BOOMR.addVar("mediaqueries", _mediaqueries);
	BOOMR.addVar("fontface", _fontface);
	BOOMR.addVar("backgroundsize", _backgroundsize);
	BOOMR.addVar("borderimage", _borderimage);
	BOOMR.addVar("borderradius", _borderradius);
	BOOMR.addVar("boxshadow", _boxshadow);
	BOOMR.addVar("flexbox", _flexbox);
	BOOMR.addVar("opacity", _opacity);
	BOOMR.addVar("cssanimations", _cssanimations);
	BOOMR.addVar("cssgradients", _cssgradients);
	BOOMR.addVar("cssreflections", _cssreflections);
	BOOMR.addVar("csstransforms", _csstransforms);
	BOOMR.addVar("csstransitions", _csstransitions);
	BOOMR.addVar("isHTML5Site", "1");
