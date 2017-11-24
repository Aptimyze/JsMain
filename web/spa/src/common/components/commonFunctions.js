import * as CONSTANTS from '../../common/constants/apiConstants'
import {getCookie,setCookie,removeCookie} from '../../common/components/CookieHelper';

export const getIosVersion = (inputUa) => {
    let ua = inputUa || navigator.userAgent;
    let match = ua.match(/(iPhone);/i);
    let OsVersion = ua.match(/OS\s[0-9.]*/i);
    if(OsVersion != null) {
        if(OsVersion[0].substring(3,5)>=7) {
            return true;
        }
    }
    return false;
}

export const getAndroidVersion = (inputUa) => {
    let ua = inputUa || navigator.userAgent;
    let android = ua.indexOf("Android");
    let match = ua.match(/Android\s([0-9\.]*)/);
    let mobile = ua.indexOf("Mobile");
    let operaMini = ua.indexOf("Opera Mini");
    if(android != -1) {
        if(typeof(parseFloat(match[1]))=='number') {
            let androidVersion=match[1].substring(0,3);
            if(androidVersion>2.3) {
                return true;
            }
        } else if(match == null) {
            return true;
        }
    }
    return false;
}


export const removeClass = (ele,className) => {
    var array=[];
    if(Object.prototype.toString.call( ele ) !== '[object Array]')
        array[0] = ele;
    else array = ele;
    var re = new RegExp(" "+className+" ","g");
    for(var i=0;i<array.length;i++){
        array[i].className = (' '+array[i].className+' ').replace(re,' ');
    }
}
export const addClass = (ele,className) => {
    var array=[];
    if(Object.prototype.toString.call( someVar ) !== '[object Array]')
        array[0] = ele;
    else array = ele;
    for(var i=0;i<array.length;i++){
        array[i].className = array[i].className + " "+className;
    }
}

export const $i = (id) => {

    return document.getElementById(id);

}


export const $c = (className) => {

    return document.getElementsByClassName(className);

}

export const translateSite = (translateURL) => {
    if(translateURL.indexOf('hindi')!==-1){
        setCookie("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
        setCookie("jeevansathi_marathi_site_new","N",100,".jeevansathi.com");
    } else if(translateURL.indexOf('marathi')!==-1){
        setCookie("jeevansathi_marathi_site_new","Y",100,".jeevansathi.com");
        setCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
    }else {
        setCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
        setCookie("jeevansathi_marathi_site_new","N",100,".jeevansathi.com");
    }
    window.location.href = translateURL;
}

export const mtoungueURL = () => {
    let obj ={translateURL:CONSTANTS.HINDI_SITE,linkId:"hindiLink",langText:"Hindi Version"};
    if(localStorage.getItem("self_MTONGUE") === 20 && false){
        obj.translateURL = CONSTANTS.MARATHI_SITE;
        obj.linkId = "marathiLink";
        obj.langText = "Marathi Version";
    }
    let url = window.location.href;
    url = url.split(".")[0];
    if(url.indexOf('hindi') !== -1 || url.indexOf('marathi') !== -1){
        obj.langText = "In English";
        obj.translateURL = CONSTANTS.SITE_URL;
        if(!getCookie("AUTHCHECKSUM")){
            obj.translateURL += "/P/logout.php";
        }
    }
    return obj;
}
