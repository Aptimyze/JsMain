const ProfileReducer = (state={
	responseMessage: '',
	appPromotion: '',
	aboutInfo: [],
	familyInfo: [],
	dppInfo: [],
	pic: [],
	lifestyle: [],
	dpp_Ticks: [],
	historyData: [],
	profileId: ''
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
			lifestyle: action.payload.lifestyle,
			dpp_Ticks:action.payload.dpp_Ticks,
			profileId:action.payload.page_info.profilechecksum
		}
		break;
		case "SHOW_HISTORY_INFO":
		state = {
			...state,
			historyData:action.payload
		}
	}
	return state;
}

export default ProfileReducer;