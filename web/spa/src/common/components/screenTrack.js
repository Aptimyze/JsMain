import {commonApiCall} from "./ApiResponseHandler";
import {getCookie} from "./CookieHelper";
const screenTrack = (title,url) =>{
  let aChsum = getCookie('AUTHCHECKSUM');
  let pageUrl = `/api/v1/api/abTrackingForSpecificPages?title=${title}&channel=jsms&url=${url}&time=${(new Date()).getTime()}`;
  if(!aChsum) commonApiCall(pageUrl,'', '', 'GET');
  if(aChsum && title === "loginSuccess") commonApiCall(pageUrl,'', '', 'GET');
};

export default screenTrack

