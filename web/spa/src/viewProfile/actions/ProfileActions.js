import * as CONSTANTS from '../../common/constants/apiConstants'
import React from 'react';
import axios from 'axios';
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');

export  function profileDetail()
{
  return dispatch =>
  {
    axios.get( API_SERVER_CONSTANTS.API_SERVER +'/api/v1/profile/detail?profilechecksum=8a92bb4861888403f0f2569042555ebei136460&AUTHCHECKSUM=034190920f02b936abed587f613814e6e93bd59e86cd3429b43f45e83cc5006708c1f10ee3f172897d328a90f93c840e5a0d51c4a864cb3f4ad244621f44e31a888112338ddb5edcf8c5a7c6d33248dc7436cd96adc6a6f857358bb1585c4c0482bc327998e6b51b380e2ffbd30a4f349fe4751dffdc01aa6b03da1eaa5d69f3967d871ba49d4ea54b58b1e716f8b5db940bbf024399bc00c10c1aa4d96c4e82d2cc33d24fdb55c21ff834983ec9351920770e5d3c53ced6972182a6c9985343',{withCredentials:true}).then( (response) => {
      dispatch({
        type:'SHOW_INFO',
        payload: response.data
      });
    })
    .catch( (error) => {  
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  } 
}
export function historyDetail(profilechecksum) {
 return dispatch =>
  {
    axios.get( API_SERVER_CONSTANTS.API_SERVER +'/api/v1/contacts/history?profilechecksum='+profilechecksum+'&pageNo=1&dataType=json&AUTH&AUTHCHECKSUM=034190920f02b936abed587f613814e6e93bd59e86cd3429b43f45e83cc5006708c1f10ee3f172897d328a90f93c840e5a0d51c4a864cb3f4ad244621f44e31a888112338ddb5edcf8c5a7c6d33248dc7436cd96adc6a6f857358bb1585c4c0482bc327998e6b51b380e2ffbd30a4f349fe4751dffdc01aa6b03da1eaa5d69f3967d871ba49d4ea54b58b1e716f8b5db940bbf024399bc00c10c1aa4d96c4e82d2cc33d24fdb55c21ff834983ec9351920770e5d3c53ced6972182a6c9985343',{withCredentials:true}).then( (response) => {
      dispatch({
        type:'SHOW_HISTORY_INFO',
        payload: response.data
      });
    })
    .catch( (error) => {  
      console.warn('Actions - fetchJobs - recreived error: ', error)
    })
  } 
}