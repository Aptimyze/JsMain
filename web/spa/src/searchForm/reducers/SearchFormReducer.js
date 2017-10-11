const SearchFormReducer = (state={
	searchData:{}
},action) => {
	switch(action.type)
	{
		case 'GET_SEARCH_DATA':
		state = {
			...state,
			searchData:action.payload
		}
		break;
	}
	return state;
}

export default SearchFormReducer;
