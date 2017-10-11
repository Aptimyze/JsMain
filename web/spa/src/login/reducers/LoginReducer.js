import {setCookie} from "../../common/components/CookieHelper";
const LoginReducer = (state={
	MyProfile:{}
},action) => {
	switch(action.type)
	{
		case 'SET_AUTHCHECKSUM':
		state = {
			...state,
			MyProfile:action.payload,
		}
		break;
	}
	return state;
}

export default LoginReducer;
