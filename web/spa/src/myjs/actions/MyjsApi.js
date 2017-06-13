import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import {push} from 'react-router-redux';


export  function MyjsApi()
{console.log('insdie apicall');
  return dispatch =>
  {
    fetch( CONSTANTS.API_SERVER +'/api/v1/myjs/perform?AUTHCHECKSUM=1cd970977322debb0749ea03601d20e93e673932da41eb3dd06af06ac29d02aac1e1dd77d45e62a62cf18ce962700e655ac0b3b4fffbdc1c4d03ae307a0feaf76436c894d40d7cd143161d93bd501dc267a2c8b2b1a90b8185e39a58d056aac53cde0a7ff6d5d1f3be2f125555273195966771106bd2b827b4d314f91304d4e9b9792ccb399a7b12b4cc69e1077c3ff8b70559f42ea2319cb7550af5eeb8b63e0a52230318cfdd376ff596374c7aaa50c542d6d98de71659890be35960489b01',
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
