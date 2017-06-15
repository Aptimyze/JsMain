import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import {push} from 'react-router-redux';
import {getCookie,setCookie} from "../../common/components/CookieHelper";


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

    fetch( CONSTANTS.API_SERVER +callUrl + checkSumURL, // PLEASE ENSURE THIS DOESNT GO LIVE AS WE CANNOT EXPOSE ACHSUM IN THE URL FIELD, THIS HAS TO GO IN THE COOKIE ITSELF WHICH WILL BE RESOLVED WITH PRODUCTION BUILD AUTOMATICALLY
      {
      method: callMethod,
      headers: {
        'Accept': 'application/json',
      }      

    })
    .then(response => response.json())
    .then( (response) => {
      if ( response.AUTHCHECKSUM ){
        setCookie('AUTHCHECKSUM',response.AUTHCHECKSUM);
      }
      dispatch({
        type: reducer,
        payload: response
      });
    })
    .catch( (error) => {
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  }
}
