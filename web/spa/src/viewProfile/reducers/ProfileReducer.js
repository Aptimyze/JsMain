const ProfileReducer = (state={
	responseMessage: ''
},action) => {
	switch(action.type)
	{
		case "SHOW_INFO":
		state = {
			...state,
			responseMessage: action.payload.responseMessage
		}
		break;
	}
	return state;
}

export default ProfileReducer;