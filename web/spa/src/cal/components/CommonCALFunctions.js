
import { commonApiCall } from "../../common/components/ApiResponseHandler";


export default function CALCommonCall(url, clickAction,myjsObj,params) {
    console.log('buttonaction');
    if(typeof params !='undefined') url += params;
    return commonApiCall(url).then(()=>{
      if(clickAction=='/')
        myjsObj.setState({calShown:true});
      else window.location.href=clickAction;
       //history,push('/myjs');
  });
}
