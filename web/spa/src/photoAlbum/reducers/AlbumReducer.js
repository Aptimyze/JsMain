const AlbumReducer = (state={
	photoAlbumData : []
},action) => {
	switch(action.type)
	{	case "GET_GALLERY":
		state = {
			...state,
			photoAlbumData: action.payload
		}
		break;
	}
	return state;
}

export default AlbumReducer;