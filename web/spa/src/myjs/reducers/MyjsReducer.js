
const MyjsReducer = (state={
	apiData: '',fetched:false
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
	}
	return state;
}

export default MyjsReducer;
