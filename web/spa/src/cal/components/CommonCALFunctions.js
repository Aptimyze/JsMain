
import { commonApiCall } from "../../common/components/ApiResponseHandler";


export default function CALCommonCall(url, clickAction,myjsObj,params) {
    if(typeof params !='undefined') url += params;
    return commonApiCall(url).then(()=>{
      if(clickAction=='/')
      {
        if(typeof myjsObj =='function'){
          myjsObj();
        }
      }
      else window.location.href=clickAction;
       //history,push('/myjs');
  });
};
export const skippableCALS = Array('1','2','3','4','5','6','7','9','10','11','12','13','14','15','16','17','19','21','22','26','27');
