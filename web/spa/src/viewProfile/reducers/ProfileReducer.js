const ProfileReducer = (state={
	responseStatusCode: '',
	responseMessage: '',
	appPromotion: '',
	aboutInfo: [],
	familyInfo: [],
	dppInfo: [],
	pic: [],
	lifestyle: [],
	dpp_Ticks: [],
	historyData: [],
	profileId: '',
	show_gunascore: "",
	gunaScore: [],
	pageInfo: [],
	fetchedProfilechecksum: false,
	buttonDetails: []
},action) => {
		// console.log("action.payload",action.payload);
	switch(action.type)
	{
		case "SHOW_INFO":
		state = {
			...state,
			responseStatusCode: action.payload.responseStatusCode,
			responseMessage: action.payload.responseMessage,
			appPromotion: action.payload.appPromotion,
			aboutInfo: action.payload.about,
			familyInfo: action.payload.family,
			dppInfo: action.payload.dpp,
			pic: action.payload.pic,
			lifestyle: action.payload.lifestyle,
			dpp_Ticks:action.payload.dpp_Ticks,
			profileId:action.payload.page_info.profilechecksum,
			show_gunascore: action.payload.show_gunascore,
			pageInfo: action.payload.page_info,
			fetchedProfilechecksum: action.payload.about.username,
			buttonDetails: action.payload.buttonDetails
		}
		break;
		case "SHOW_HISTORY_INFO":
		state = {
			...state,
			historyData:action.payload
		}
		break;
		case "SHOW_GUNA":
		state = {
			...state,
			gunaScore:action.payload
		}
		break;
		case "REPLACE_BUTTON":
		let bD = {...state.buttonDetails};
		bD.buttons.others[action.payload.index] = action.payload.button.button;
		state = {
			...state,
			buttonDetails:bD
		}
		break;
	}
	return state;
}

export default ProfileReducer;
