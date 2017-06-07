const LoginReducer = (state={
	profileCheckSum: ''
},action) => {
	switch(action.type)
	{
		case "SET_CHECKSUM":
		state = {
			...state,
			profileCheckSum:action.payload,
		}
		break;
	}
	return state;
}

export default LoginReducer;
