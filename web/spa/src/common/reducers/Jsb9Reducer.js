const Jsb9Reducer = (state={
},action) => {
	console.log('jsb9',action.type);

	switch(action.type)
	{
			case 'SET_JSB9_REDIRECTION':
		state = {
			...action.payload
		}
		break;
	}
	console.log('statestar');console.log(state);console.log('stateend');
	return state;
}

export default Jsb9Reducer;
