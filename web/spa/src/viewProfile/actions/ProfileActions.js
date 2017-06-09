import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';

export  function profileDetail()
{
  return dispatch =>
  {
    fetch( CONSTANTS.API_SERVER +'/api/v1/profile/detail?checksum=f4d39b4b20a6571c557ba0a4bac68667i99408443&profilechecksum=f4d39b4b20a6571c557ba0a4bac68667i99408443', {
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