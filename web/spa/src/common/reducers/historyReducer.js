import historyObj from "../components/history_js";
import {getCookie,setCookie,removeCookie} from "../components/CookieHelper";
let oneTimeInstance = {};
if(!window.historyObjInstantiated){
  oneTimeInstance = new historyObj();
  window.historyObjInstantiated = true;
  window.addEventListener("unload", function(){
    let presentTime =new Date().getTime();
    let presentUrl = window.location.href.split('?')[0];
    setCookie("jsb9Track",presentTime+"|"+presentUrl,5/60);
  });
}
const historyReducer = (state={
  historyObject : oneTimeInstance
},action) => {

	switch(action.type)
	{
  default:
  break;
	}
	return state;
}

export default historyReducer;
