const Jsb9Reducer = (state={
},action) => {
	switch(action.type)
	{
		case 'SET_JSB9_REDIRECTION':
			state = {
				...action.payload
			}
		break;
	}
	return state;
}

export default Jsb9Reducer;
