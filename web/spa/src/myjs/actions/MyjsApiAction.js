import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import {push} from 'react-router-redux';


export  function MyjsApi()
{console.log('insdie apicall');
  return dispatch =>
  {
    fetch( CONSTANTS.API_SERVER +'/api/v1/myjs/perform?AUTHCHECKSUM=1cd970977322debb0749ea03601d20e93e673932da41eb3dd06af06ac29d02aac1e1dd77d45e62a62cf18ce962700e655ac0b3b4fffbdc1c4d03ae307a0feaf76436c894d40d7cd143161d93bd501dc28cc7f7d80ac79f29395e42b8a28132099e7847058cc5afd8c16a838afa940784ade7459d9e66621c870cfa564bb09ac9f59a9353ebc00ff76b3998c912de0005889aa790891dc4daa9a2532d0b00e487d17d10658390c56449aab7c371d6a18a30ae211dac68c2387bfec4649ef63970',
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
