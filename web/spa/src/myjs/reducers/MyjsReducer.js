const MyjsReducer = (state={
	apiData: '',
	fetched:false,
	showPD:false,
	drFetched:false,
	modFetched:false,
	irFetched:false,
	vaFetched:false,
	ieFetched:false,
	hamFetched:false
},action) => {
	switch(action.type)
	{
			case 'SET_MYJS_DATA':
		state = {
			...state,
			apiData:action.payload,
			fetched : true
		}
		break;
		case 'SET_DR_DATA':
		state = {
			...state,
			apiDataDR:action.payload,
			drFetched : true
		}
		break;
		case 'SET_MOD_DATA':
		state = {
			...state,
			apiDataMOD:action.payload,
			modFetched : true
		}
		break;
		case 'SET_IR_DATA':
		state = {
			...state,
			apiDataIR:action.payload,
			irFetched : true
		}
		break;
		case 'SET_VA_DATA':
		state = {
			...state,
			apiDataVA:action.payload,
			vaFetched : true
		}
		break;
		case 'SET_IE_DATA':
		state = {
			...state,
			apiDataIE:action.payload,
			ieFetched : true
		}
		break;
		case 'SET_HAM_DATA':
		state = {
			...state,
			apiDataHam:action.payload,
			hamFetched : true
		}
		break;
	}
	return state;
}

export default MyjsReducer;
