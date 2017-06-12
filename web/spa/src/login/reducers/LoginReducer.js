import {setCookie} from "../../common/components/CookieHelper";
const LoginReducer = (state={
	AUTHCHECKSUM: '',
	responseMessage: ''
},action) => {
	console.log("Setting authchecksum.");
		console.log(action.type);
	switch(action.type)
	{
		case 'SET_AUTHCHECKSUM':
		console.log();
		if ( action.payload.AUTHCHECKSUM )
		{
        	setCookie('AUTHCHECKSUM',action.payload.AUTHCHECKSUM);
		}
		console.log("Setting authchecksum.");
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
