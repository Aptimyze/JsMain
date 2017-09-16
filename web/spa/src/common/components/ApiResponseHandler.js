import * as CONSTANTS from '../../common/constants/apiConstants'
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'
import React from 'react';
import {push} from 'react-router-redux';
import {getCookie,setCookie,removeCookie} from "../../common/components/CookieHelper";
import "babel-polyfill";
import axios from "axios";
import {recordServerResponse, recordDataReceived,setJsb9Key} from "../../common/components/Jsb9CommonTracking";
export  function commonApiCall(callUrl,data,reducer,method,dispatch,trackJsb9,containerObj,tupleID)
{


  let callMethod = method ? method :  'POST';
    let aChsum = getCookie('AUTHCHECKSUM');
    let checkSumURL = '';
    if ( aChsum )
    {

      if ( callUrl.indexOf("?") == -1 )
      {

        if(data){

          checkSumURL = '?AUTHCHECKSUM='+aChsum+data;

        }

        else
        {

            checkSumURL = '?AUTHCHECKSUM='+aChsum;

        }

      }
      else
      {

        if(data){

          checkSumURL = '&AUTHCHECKSUM='+aChsum+data;

        }

        else{

          checkSumURL = '&AUTHCHECKSUM='+aChsum;

        }

      }
    }
    else
    {

      if(Object.keys(data).length!=0){
          checkSumURL = data;
      }
    }
    // console.log("shahjahan dispatch",dispatch);
    // console.log("shahjahan prevDataUrl",localStorage.getItem("prevDataUrl"));
    if(reducer != "SAVE_INFO" && localStorage.getItem("prevDataUrl") == callUrl && localStorage.getItem("prevData") || localStorage.getItem("nextDataUrl") == callUrl &&  localStorage.getItem("nextDataUrl") == callUrl || localStorage.getItem("currentDataUrl") == callUrl &&  localStorage.getItem("currentData")) {
      let data;
      if(localStorage.getItem("prevDataUrl") == callUrl) {
        console.log("shahjahan Getting from prevDataUrl.");
        data = JSON.parse(localStorage.getItem("prevData"));

        localStorage.setItem("nextData", localStorage.getItem("currentData"));
        localStorage.setItem("nextDataUrl",localStorage.getItem("currentDataUrl"));
        
        localStorage.setItem("currentData", localStorage.getItem("prevData"));
        localStorage.setItem("currentDataUrl", localStorage.getItem("prevDataUrl"));
      } else if(localStorage.getItem("nextDataUrl") == callUrl) {
        data = JSON.parse(localStorage.getItem("nextData"));
        // console.log("shahjahan currentData",localStorage.getItem("currentData"))
        // console.log("shahjahan currentDataUrl",localStorage.getItem("currentDataUrl"))
        if( dispatch != "saveLocalNext")
        {
          localStorage.setItem("prevData", localStorage.getItem("currentData"));
          localStorage.setItem("prevDataUrl",localStorage.getItem("currentDataUrl"));

          localStorage.setItem("currentDataUrl", localStorage.getItem("nextDataUrl"));
          localStorage.setItem("currentData", localStorage.getItem("nextData"));

          localStorage.setItem("prevDataUrlForGuna", localStorage.getItem("currentDataUrlForGuna"));
          localStorage.setItem("prevGuna", localStorage.getItem("currentGuna"));

        }
      } else {
        data = JSON.parse(localStorage.getItem("currentData"))
      }
      if(typeof dispatch == 'function')
      {
        dispatch({
          type: reducer,
          payload: data,
          token: tupleID
        });
      }
    } else if(reducer != "SAVE_INFO" && (localStorage.getItem("currentDataUrlForGuna") == callUrl && localStorage.getItem("currentGuna") || localStorage.getItem("prevDataUrlForGuna") == callUrl && localStorage.getItem("prevGuna"))  ) {
      // console.log("shahjahan In guna if block.");
      let dataGuna;
      if ( localStorage.getItem("currentDataUrlForGuna") == callUrl && localStorage.getItem("currentGuna") )
      {
        dataGuna = JSON.parse(localStorage.getItem("currentGuna"));
      }
      else
      {
        dataGuna = JSON.parse(localStorage.getItem("prevGuna"));
        localStorage.setItem("currentGuna", localStorage.getItem("prevGuna"));
        localStorage.setItem("currentDataUrlForGuna", localStorage.getItem("prevDataUrlForGuna"));
      }

      if(typeof dispatch == 'function')
      {
        dispatch({
          type: reducer,
          payload: dataGuna
        });
      }

    }
    else {
      // console.log("shahjahan axios callUrl",callUrl);

      return axios({
        method: callMethod,
        url: API_SERVER_CONSTANTS.API_SERVER +callUrl + checkSumURL + '&fromSPA=1',
        data: {},
        headers: {
          'Accept': 'application/json',
          'withCredentials':true,
          'X-Requested-By': 'jeevansathi',
          'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
        },
      }).then( (response) => {
        switch(response.data.responseStatusCode)
        {
          case "9":
            removeCookie("AUTHCHECKSUM");
            localStorage.clear();
            window.location.href="/login?prevUrl="+window.location.href;
            break;
          case "7":
            window.location.href="/register/newJsmsReg?incompleteUser=1";
            break;
          case "8":
            window.location.href="/phone/jsmsDisplay";
            break;
          case "0":
          case "1":
          case "10":
            break;
          default:
            if ( response.data.responseMessage )
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
        if(typeof trackJsb9 != 'undefined' && typeof containerObj != 'undefined' && trackJsb9===true)
        {
          recordDataReceived(containerObj,new Date().getTime());
          setJsb9Key(containerObj,response.data.jsb9Key);
          recordServerResponse(containerObj,response.data.apiTimeTracking);
        }
        if ( response.data.AUTHCHECKSUM && typeof response.data.AUTHCHECKSUM !== 'undefined'){
          setCookie('AUTHCHECKSUM',response.data.AUTHCHECKSUM);

          if ( response.data.GENDER && response.data.USERNAME )
          {
            localStorage.setItem('GENDER',response.data.GENDER);
            localStorage.setItem('USERNAME',response.data.USERNAME);
          }
        }
        if(typeof dispatch == 'function')
        {
          if(reducer == "SHOW_INFO") {
            localStorage.setItem("currentData", JSON.stringify(response.data));
            localStorage.setItem("currentDataUrl",callUrl)
          } else if(reducer == "SHOW_GUNA") {
            localStorage.setItem("currentGuna", JSON.stringify(response.data));
            localStorage.setItem("currentDataUrlForGuna",callUrl)
          }
          dispatch({
            type: reducer,
            payload: response.data,
            token: tupleID
          });
        } else if(dispatch == "saveLocalNext") {
            localStorage.setItem("nextData", JSON.stringify(response.data));
            localStorage.setItem("nextDataUrl",callUrl)
        } else if(dispatch == "saveLocalPrev") {
            localStorage.setItem("prevData", JSON.stringify(response.data));
            localStorage.setItem("prevDataUrl",callUrl)
        }
        return response.data;
      })
      .catch( (error) => {
        if(typeof dispatch == 'function')
        {
          dispatch({
            type: reducer,
            payload: {},
            token: tupleID
          });
        }
        console.warn('Actions - fetchJobs - recreived error: ', error)
      })
    }
}
