const contactEngineReducer = (state={
	contactDone:false,
	acceptDone:false,
	declineDone:false,
	reminderDone:false,
	msgInitiated:false
},action) => {
	switch(action.type)
	{			
		case 'INITIATE':
		state = {
			...state,
			contact:action.payload,
			contactDone : true
		}
		break;
		case 'ACCEPT':
		state = {
			...state,
			accept:action.payload,
			acceptDone : true
		}
		break;
		case 'DECLINE':
		state = {
			...state,
			decline:action.payload,
			declineDone : true
		}
		break;
		case 'REMINDER':
		state = {
			...state,
			reminder:action.payload,
			reminderDone : true
		}
		break;
		case 'WRITE_MESSAGE':
		state = {
			...state,
			message:action.payload,
			msgInitiated : true
		}
		break;
	}
	return state;
}

export default contactEngineReducer;
