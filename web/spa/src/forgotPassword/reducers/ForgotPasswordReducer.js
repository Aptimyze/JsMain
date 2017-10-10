const ForgotPasswordReducer = (state={
	forgotData:{}
},action) => {
	switch(action.type)
	{
		case 'SEND_FORGOT_LINK':
		state = {
			...state,
			forgotData:action.payload
		}
		break;
	}
	return state;
}

export default ForgotPasswordReducer;
