const AlbumReducer = (state={
	photoAlbumData : [],
	photoAction : []
},action) => {
	switch(action.type)
	{	case "GET_GALLERY":
		state = {
			...state,
			photoAlbumData: action.payload
		}
		break;
		case "PHOTO_ACTION":
		state = {
			...state,
			photoAction: action.payload
		}
		break;
	}
	return state;
}

export default AlbumReducer;