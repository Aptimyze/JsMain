import * as CONSTANTS from '../../common/constants/apiConstants'
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');
import React from 'react';
import {push} from 'react-router-redux';
import {getCookie,setCookie,removeCookie} from "../../common/components/CookieHelper";
import "babel-polyfill";
import axios from "axios";
import {recordServerResponse, recordDataReceived,setJsb9Key} from "../../common/components/Jsb9CommonTracking";
import {getProfileLocalStorage,setProfileLocalStorage,isPresentInLocalStorage,removeProfileLocalStorage,getProfileKeyLocalStorage,getGunaKeyLocalStorage} from "../../common/components/CacheHelper";
import {RESPONSE_STATUS_MESSAGE_PUSH_MESSAGE} from '../../common/constants/CommonConstants'

let aBCounter = 0;
localStorage.setItem('aBCounter',0);
export  function commonApiCall(callUrl,data,reducer,method,dispatch,trackJsb9,containerObj,headers,listingId,page_number)
{


  let callMethod = method ? method :  'POST';
    let aChsum = getCookie('AUTHCHECKSUM');
    let checkSumURL = '';
    if ( aChsum )
    {

      if ( callUrl.indexOf("?") == -1 )
      {
        checkSumURL = '?AUTHCHECKSUM='+aChsum;
      }
      else
      {
        checkSumURL = '&AUTHCHECKSUM='+aChsum;
      }
    }
    else
    {

      if(Object.keys(data).length!=0){
//          checkSumURL = data;
      }
    }
    if( isPresentInLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,callUrl) !== false   ) {
      let data;
      data = getProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,callUrl);
      if(reducer != "SAVE_INFO")
      {
        return new Promise((resolve,reject)=>resolve(data));
      }
    } else if(reducer == "SHOW_GUNA" && isPresentInLocalStorage(CONSTANTS.GUNA_LOCAL_STORAGE__KEY,getGunaKeyLocalStorage(callUrl)) !== false  ) {
      let dataGuna=getProfileLocalStorage(CONSTANTS.GUNA_LOCAL_STORAGE__KEY,getGunaKeyLocalStorage(callUrl));

        return new Promise((resolve,reject)=>resolve(dataGuna));

    }
    else {
      let params2 = typeof data=='object' ? (Object.keys(data).map((i) => i+'='+encodeURIComponent(data[i])).join('&'))  : '';
      if(data instanceof FormData) params2 = data;
      let newCallUrl;
      if ( CONSTANTS.AGGREGAT0R_PROFILE_FLAG &&  callUrl.indexOf(CONSTANTS.AGGREGAT0R_PROFILE_URL) != -1)
      {
        newCallUrl =  CONSTANTS.AGGREGAT0R_V0_SERVER+callUrl;
      }
      else
      {
        newCallUrl = API_SERVER_CONSTANTS.API_SERVER +callUrl;
      }
       newCallUrl += checkSumURL + '&fromSPA=1';
      return axios({
        method: callMethod,
        url: newCallUrl,
        data: params2,
        headers: {
          'Accept': 'application/json',
          'withCredentials':true,
          'X-Requested-By': 'jeevansathi',
          'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
          ...headers
        },
      }).then( (response) => {
        try{
          if(response.data.showAndBeyond){
            let url = "//ht-jeevansindia.native.andbeyond.media/js/abm_jeevansaathiindia.js";

            if(!isJsLoaded(url)){
                let andbeyond = document.createElement("script");
                andbeyond.src = url;
                andbeyond.async = true;
                document.head.appendChild(andbeyond);
              }
          }
        }
        catch(e){}

        switch(response.data.responseStatusCode)
        {
          case "9":
            removeCookie("AUTHCHECKSUM");
            removeCookie("AUTHCHECKSUM", "www.jeevansathi.com");
            // localStorage.clear();
            window.location.href = "/login?prevUrl=" + window.location.href;
            break;
          case "7":
            aBCounter = localStorage.getItem('aBCounter');
            if(response && response.data){
              if(aBCounter == 0){
                callAngular(response.data.selfUsername, response.data.isAngular);
              }
              if(response.data.isAngular){
                window.location.href = "/register/newJsmsReg?incompleteUser=1";
              }
              else {
                let data = response['data'];
                localStorage.setItem('staticData', JSON.stringify(data));
                window.location.href = "/register/newjsmsreg?incompleteUser=1&s=7";
              }
            }
            break;
          case "8":
            window.location.href = "/phone/jsmsDisplay" + window.location.search;
            break;
          case "0":
            //successful case.
            break;
          case "5":
            window.location.href="/phone/ConsentMessage";
            break;
          default:
            if ( response.data.responseMessage && RESPONSE_STATUS_MESSAGE_PUSH_MESSAGE.indexOf(response.data.responseMessage) != -1 )
            {
              let message = response.data.responseMessage;
              let parent = document.createElement("div");
              parent.id = "ApiResponseHeaderTopError";

              let child = document.createElement("div");
              child.id = "TopError";
              child.innerHTML = "<div class = 'fullwid top0 posfix' style='height: 10px;top:0px;z-index:101;'><div class = 'pad12_e white f15 op1'>"+response.data.responseMessage+"</div></div>";
              parent.appendChild(child);

              if ( document.getElementById("ApiResponseHeaderTopError") != null)
              {
                document.getElementById("ApiResponseHeaderTopError").classList.remove("dn");
              }
              else
              {
                document.body.insertBefore(parent,document.body.childNodes[0]);
              }


              setTimeout(function () {
                document.getElementById("ApiResponseHeaderTopError").className += " dn";
              },2000)
            }

            break;

        }
        if(typeof trackJsb9 != 'undefined'  && trackJsb9===true)
        {
    //      recordDataReceived(containerObj,new Date().getTime());
    //      setJsb9Key(containerObj,response.data.jsb9Key);
      //    recordServerResponse(containerObj,response.data.apiTimeTracking);
        }
        if ( response.data.AUTHCHECKSUM && typeof response.data.AUTHCHECKSUM !== 'undefined'){
          //setCookie('AUTHCHECKSUM',response.data.AUTHCHECKSUM);

          if (response.data.GENDER)
          {
            localStorage.setItem('GENDER',response.data.GENDER);
          }
          else if (response.data.selfGender){
            localStorage.setItem('GENDER',response.data.selfGender);
          }

          if(response.data.selfMtongue)
          {
              localStorage.setItem('self_MTONGUE',response.data.selfMtongue);
          }

          if(response.data.USERNAME)
          {
            localStorage.setItem('USERNAME',response.data.USERNAME);
          }
          else if (response.data.selfUsername)
          {
            localStorage.setItem('USERNAME',response.data.selfUsername);
          }
        }
        else{

          if (response.data.selfGender){
            localStorage.setItem('GENDER',response.data.selfGender);
          }

          if (response.data.selfUsername)
          {
            localStorage.setItem('USERNAME',response.data.selfUsername);
          }

          if(response.data.selfMtongue)
          {
              localStorage.setItem('self_MTONGUE',response.data.selfMtongue);
          }
        }

        if ( getCookie('AUTHCHECKSUM') )
        {
          
          if(reducer == "SHOW_INFO")
          {
              setProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,callUrl,response.data);

            } else if(reducer == "SHOW_GUNA") {

              setProfileLocalStorage(CONSTANTS.GUNA_LOCAL_STORAGE__KEY,getGunaKeyLocalStorage(callUrl),response.data);
          } else if(dispatch == "saveLocalNext") {
              setProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,callUrl,response.data);
          } else if(dispatch == "saveLocalPrev") {
              setProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,callUrl,response.data);
          }
        }

        if(typeof dispatch=='function')
          dispatch({
            type: reducer,
            payload: response.data,
            listingId:listingId,
            index:data['index'],
            page_number:page_number,
            hitTime:new Date().getTime()

          });

        return response.data;

      })
      .catch( (error) => {
        console.warn('Actions - fetchJobs - error in ApiResponseHandler: ', error);
        if(typeof dispatch == 'function')
        {
          dispatch({
            type: reducer,
            payload: {},
          });
        }
        return error.response;
      })
    }
}

function callAngular(pid,isAng) {
  let ang = isAng ? "A" : "R";
  let abLandApi = `/register/aBLandStore?pid=${pid}&isAng=${ang}`;
  commonApiCall(abLandApi, {}, '', 'POST', '', false).then((response) => {
    aBCounter = 1;
    localStorage.setItem('aBCounter',aBCounter);
   // console.log(pid,isAng);
  });

}

function isJsLoaded(url){
  let scripts = document.getElementsByTagName('script');
  for (let i = scripts.length; i--;) {
    if (scripts[i].src.indexOf(url) != -1){
      return true;
    }
  }
  return false;
}