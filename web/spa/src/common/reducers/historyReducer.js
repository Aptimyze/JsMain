import historyObj from "../components/history_js";

const historyReducer = (state={
  historyObject : new historyObj()
},action) => {
	switch(action.type)
	{
  default:
  break;
	}
	return state;
}

export default historyReducer;
