const LoginReducer = (state={
	profileCheckSum: '',
	responseMessage: ''
},action) => {
	switch(action.type)
	{
		case "SET_CHECKSUM":
		state = {
			...state,
			profileCheckSum:action.payload.AUTHCHECKSUM,
			responseMessage: action.payload.responseMessage
		}
		break;
	}
	return state;
}

export default LoginReducer;
