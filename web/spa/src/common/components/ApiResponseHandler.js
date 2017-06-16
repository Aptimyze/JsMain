import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import {push} from 'react-router-redux';
import {getCookie,setCookie} from "../../common/components/CookieHelper";
import axios from "axios";

export  function commonApiCall(callUrl,data,reducer,method)
{
  let callMethod = method ? method :  'POST';

  return dispatch =>
  {
    let aChsum = getCookie('AUTHCHECKSUM');
    let checkSumURL = '';
    if ( aChsum && callUrl.indexOf("?") == -1)
    {
      checkSumURL = '?AUTHCHECKSUM='+aChsum;
    } else {
      checkSumURL = '&AUTHCHECKSUM='+aChsum;
    }
    axios({
    method: callMethod,
    url: CONSTANTS.API_SERVER +callUrl + checkSumURL,
    data: '',
    headers: { 
      'Accept': 'application/json',
      'withCredentials':true
    },
  }).then( (response) => {
      console.log(response);
      if ( response.data.AUTHCHECKSUM ){
        setCookie('AUTHCHECKSUM',response.data.AUTHCHECKSUM);

        if ( response.data.GENDER && response.data.USERNAME )
        {
          localStorage.setItem('GENDER',response.data.GENDER);
          localStorage.setItem('USERNAME',response.data.USERNAME);
        }
      }
      dispatch({
        type: reducer,
        payload: response.data
      });
    })
    .catch( (error) => {
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  }
}
