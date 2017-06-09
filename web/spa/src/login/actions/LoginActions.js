import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react'; 

export  function signin(email,password)
{
  return dispatch =>
  {
    fetch( CONSTANTS.API_SERVER +'/api/v1/api/login?email='+email+'&password='+password, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
    })
    .then(response => response.json())
    .then( (response) => {
      dispatch({
        type:'SET_CHECKSUM',
        payload: response
      });
    })
    .catch( (error) => {
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  } 
}
