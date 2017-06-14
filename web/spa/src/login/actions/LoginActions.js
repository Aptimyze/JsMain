import * as CONSTANTS from '../../common/constants/apiConstants';
import commonApiCall from '../../common/components/ApiResponseHandler.js';
import React from 'react';
import {push} from 'react-router-redux';  
import Cookies from 'universal-cookie';

export function siginFromCookie(response)
{
  return dispatch =>
  {
    dispatch({
        type:'SET_AUTHCHECKSUM',
        payload: response,
    });
  }

}
