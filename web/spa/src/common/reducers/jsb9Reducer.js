const jsb9Reducer = (state={
},action) => {
	console.log('jsb9',action.type);

	switch(action.type)
	{
			case 'SET_TIME':
		state = {
			...state,
			...action.payLoad
		}
		break;
	}
	return state;
}

export default jsb9Reducer;
