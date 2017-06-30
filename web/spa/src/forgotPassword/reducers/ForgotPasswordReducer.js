const ForgotPasswordReducer = (state={
	forgotData:{}
},action) => {
	switch(action.type)
	{
		case 'SET_AUTHCHECKSUM':
		state = {
			...state,
			forgotData:action.payload
		}
		break;
	}
	return state;
}

export default ForgotPasswordReducer;
