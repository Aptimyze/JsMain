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
}

export const mtoungueURL = () => {
    let obj ={translateURL:CONSTANTS.HINDI_SITE,linkId:"hindiLink",langText:"In Hindi"};
    if(localStorage.getItem("self_MTONGUE") == 20 && getCookie("AUTHCHECKSUM")){
        obj.translateURL = CONSTANTS.MARATHI_SITE;
        obj.linkId = "hindiLink";
        obj.langText = "In Marathi";
    }
    let url = window.location.href;
    url = url.split(".")[0];
    if(url.indexOf('hindi') != -1 || url.indexOf('marathi') != -1){
        obj.langText = "In English";
        obj.translateURL = CONSTANTS.SITE_URL;
        if(!getCookie("AUTHCHECKSUM")){
            obj.translateURL += "/P/logout.php";
        }
    }
    return obj;
}

export const getListingIdFromParams = (props) =>
{
    if ( typeof props.match.params.listingId =="undefined" )
    {
        return "QuickSearchBand";
    }
    else
    {
        return props.match.params.listingId;
    }
}
export const goBackHistoryFallback = (prevPage,fallbackUrl) =>{
    fallbackUrl = fallbackUrl || '/';

    setTimeout(function(){ 
        if (window.location.href == prevPage) {
            window.location.href = fallbackUrl; 
        }
    }, 1000);
}

function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

export const constructLastSeenData = (last_active_before, online) =>{
    //console.log('inside ms');
    try {
        if (last_active_before!=null) {
          let now = new Date();
          let lastOnline = new Date()
          lastOnline.setTime(now.getTime() - last_active_before);
          let lastOnlineData = '';
          let convertedTime = formatAMPM(lastOnline);
          if (last_active_before == 0 && online == true) {
            lastOnlineData = "Online Now";
          }else if (last_active_before == 0 && online == false) {
            lastOnlineData = '';
          }
          else if (lastOnline.getDate() == now.getDate() && lastOnline.getMonth() == now.getMonth() && lastOnline.getFullYear() == now.getFullYear() ) {
      
            lastOnlineData = "Last seen at "+convertedTime;
          } else {
            let monthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            let date = null;
            if (Math.floor(lastOnline.getDate() / 10) == 0) {
              date = "0"+lastOnline.getDate();
            } else {
              date = lastOnline.getDate();
            }
            let year = lastOnline.getFullYear();
                  lastOnlineData = "Last seen on " +date+"-"+monthArray[lastOnline.getMonth()]+"-"+year.toString().substring(2);
          }
          last_active_before = lastOnlineData;
      
        } else {
          last_active_before = '';
        }
        return last_active_before;
      }catch(e){
        console.log(e);
      }
}
