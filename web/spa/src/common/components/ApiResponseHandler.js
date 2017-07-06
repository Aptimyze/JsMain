import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import {push} from 'react-router-redux';
import {getCookie,setCookie} from "../../common/components/CookieHelper";
import axios from "axios";
import {recordServerResponse, recordDataReceived,setJsb9Key} from "../../common/components/Jsb9CommonTracking";
export  function commonApiCall(callUrl,data,reducer,method,dispatch,trackJsb9,containerObj)
{
  let callMethod = method ? method :  'POST';
    let aChsum = getCookie('AUTHCHECKSUM');
    let checkSumURL = '';
    if ( aChsum )
    {

      if ( callUrl.indexOf("?") == -1 )
      {
        checkSumURL = '?AUTHCHECKSUM='+aChsum+data;
      } 
      else 
      {
        checkSumURL = '&AUTHCHECKSUM='+aChsum+data;
      }
    }
    return axios({
    method: callMethod,
    url: CONSTANTS.API_SERVER +callUrl + checkSumURL,
    data: '',
    headers: {
      'Accept': 'application/json',
      'withCredentials':true
    },
  }).then( (response) => {
      if(typeof trackJsb9 != undefined && typeof containerObj != 'undefined' && trackJsb9===true)
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
        dispatch({
          type: reducer,
          payload: response.data
        });
      }
      return response.data;
    })
    .catch( (error) => {
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })

}
