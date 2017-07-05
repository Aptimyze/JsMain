const Jsb9Reducer = (state={
},action) => {
	console.log('jsb9',action.type);

	switch(action.type)
	{
			case 'SET_JSB9_REDIRECTION':
		state = {
			...action.payLoad
		}
		break;
	}
	return state;
}

export default Jsb9Reducer;
