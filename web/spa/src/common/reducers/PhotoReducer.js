const PhotoReducer = (state={
	photoAction : []
},action) => {
	switch(action.type)
	{	case "PHOTO_ACTION":
		state = {
			...state,
			photoAction: action.payload
		}
		break;
	}
	return state;
}

export default PhotoReducer;