import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import {push} from 'react-router-redux';


export  function MyjsApi()
{console.log('insdie apicall');
  return dispatch =>
  {
    fetch( CONSTANTS.API_SERVER +'/api/v1/myjs/perform',
      {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      }

    })
    .then(response => response.json())
    .then( (response) => {
      dispatch({
        type:'SET_MYJS_DATA',
        payload: response
      });
    })
    .catch( (error) => {
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  }
}
