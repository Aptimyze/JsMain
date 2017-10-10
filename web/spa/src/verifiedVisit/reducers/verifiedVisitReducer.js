const verifiedVisitReducer = (state={
	verifiedData: []
},action) => {
	switch(action.type)
	{
		case "SHOW_VERIFIED_INFO":
		state = {
			...state,
			verifiedData: action.payload
		}
		break;
	}
	return state;
}

export default verifiedVisitReducer;