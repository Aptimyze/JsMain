const contactEngineReducer = (state={
	contactDone:false,
	acceptDone:false,
	declineDone:false,
	tupleID: null
},action) => {
	switch(action.type)
	{			
		case 'CONTACT_ACTION':
		state = {
			...state,
			contact:action.payload,
			contactDone : true,
			tupleID : action.token
		}
		break;
		case 'ACCEPT':
		state = {
			...state,
			accept:action.payload,
			acceptDone : true,
			tupleID : action.token
		}
		break;
		case 'DECLINE':
		state = {
			...state,
			decline:action.payload,
			declineDone : true,
			tupleID : action.token
		}
		break;
		case 'REMINDER':
		state = {
			...state,
			reminder:action.payload,
			reminderDone : true,
			tupleID : action.token
		}
		break;
	}
	return state;
}

export default contactEngineReducer;
