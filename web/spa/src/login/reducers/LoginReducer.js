import {setCookie} from "../../common/components/CookieHelper";
const LoginReducer = (state={
	AUTHCHECKSUM: '',
	responseMessage: ''
},action) => {
	switch(action.type)
	{
		case 'SET_AUTHCHECKSUM':
		if ( action.payload.AUTHCHECKSUM )
		{
        	setCookie('AUTHCHECKSUM',action.payload.AUTHCHECKSUM);
		}
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
