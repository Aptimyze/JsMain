import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';

export  function profileDetail()
{
  return dispatch =>
  {
    fetch( CONSTANTS.API_SERVER +'/api/v1/profile/detail?profilechecksum=0b61993c873f90e1c62ad22c120152e8i9397643', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
    })
    .then(response => response.json())
    .then( (response) => {
      dispatch({
        type:'SHOW_INFO',
        payload: response
      });
    })
    .catch( (error) => {  
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  } 
}