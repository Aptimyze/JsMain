const MyjsReducer = (state={
	apiData: '',fetched:false, showPD:false
},action) => {
	switch(action.type)
	{
			case 'SET_MYJS_DATA':
		state = {
			...state,
			apiData:action.payload,
			fetched : true,
			jsb9Track : true
		}
		break;
	}
	return state;
}

export default MyjsReducer;
