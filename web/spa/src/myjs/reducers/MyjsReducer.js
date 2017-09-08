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
			calShown : false,
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

		case 'SET_IR_PAGINATION':
			var apiDataIR = state.apiDataIR;
			action.payload.profiles.forEach(function(elem){
				apiDataIR['profiles'].push(elem);
			});
			apiDataIR['paginationHit'] = false;
			apiDataIR['nextpossible'] = action.payload.nextpossible;
			apiDataIR['page_index'] = action.payload.page_index;
			state = {
				...state,
				apiDataIR:apiDataIR,
				irFetched : true
			}
			break;
			case 'SPLICE_MYJS_DATA':
			var arr,key;
			console.log(action.payload);
				switch(action.payload.infotype)
				{
					case 'INTEREST_RECEIVED':
						arr = state.apiDataIR;
						key = 'apiDataIR';
					break;
					case 'MATCH_OF_THE_DAY':
						arr = state.apiDataMOD;
						key = 'apiDataMOD';
					break;
					default:
						arr = state.apiDataDR;
						key = 'apiDataDR';
					break;
				}
				let newArray = arr['profiles'].slice(), oldCount = arr.total;

				newArray.splice(action.payload.index,1);
				state = {
					...state,
					[key] : {
						...arr,
						profiles : newArray,
						total : --oldCount
					}
				}
				break;
				case 'RESET_MYJS_TIMESTAMP':

				let value = action.payload.value ? action.payload.value  : new Date().getTime();
				if(value==-1)
					state = {
						...state,
						fetched :false,
						timeStamp : value
						}
				else
					state = {
						...state,
						timeStamp : value
						}
				break;
				case 'SET_CAL_SHOWN':
					state = {
						...state,
						calShown: true
						}
				break;
	}
	return state;
}

export default MyjsReducer;
