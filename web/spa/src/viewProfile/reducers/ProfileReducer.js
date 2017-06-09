const ProfileReducer = (state={
	response: ''
},action) => {
	switch(action.type)
	{
		case "SHOW_ABOUTME":
		state = {
			...state,
			response: action.payload
		}
		break;
	}
	return state;
}

export default ProfileReducer;