const ProfileReducer = (state={
	responseMessage: '',
	appPromotion: '',
	aboutInfo: [],
	familyInfo: [],
	dppInfo: [],
	pic: [],
	lifestyle: []
},action) => {
	switch(action.type)
	{
		case "SHOW_INFO":
		state = {
			...state,
			responseMessage: action.payload.responseMessage,
			appPromotion: action.payload.appPromotion,
			aboutInfo: action.payload.about,
			familyInfo: action.payload.family,
			dppInfo: action.payload.dpp,
			pic: action.payload.pic,
			lifestyle: action.payload.lifestyle
		}
		break;
	}
	return state;
}

export default ProfileReducer;