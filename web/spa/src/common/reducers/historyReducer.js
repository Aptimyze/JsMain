import historyObj from "../components/history_js";
let oneTimeInstance = {};
if(!window.historyObjInstantiated){
  oneTimeInstance = new historyObj();
  window.historyObjInstantiated = true;
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
