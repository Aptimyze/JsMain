
const LoginReducer = (state={
	AUTHCHECKSUM: '',
	responseMessage: ''
},action) => {
	switch(action.type)
	{
		case 'SET_AUTHCHECKSUM':
		state = {
			...state,
			AUTHCHECKSUM:action.payload.AUTHCHECKSUM,
			responseMessage: action.payload.responseMessage
		}
		break;
	}
	return state;
}

export default LoginReducer;
