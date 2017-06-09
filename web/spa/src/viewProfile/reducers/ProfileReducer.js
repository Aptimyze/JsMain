const ProfileReducer = (state={
	responseMessage: '',
	myInfo: '',
	familyInfo: '',
	dppInfo: ''
},action) => {
	switch(action.type)
	{
		case "SHOW_INFO":
		state = {
			...state,
			responseMessage: action.payload.responseMessage,
			myInfo: action.payload.about.myinfo,
			familyInfo: action.payload.family.myfamily,
			dppInfo: action.payload.dpp.about_partner
		}
		break;
	}
	return state;
}

export default ProfileReducer;