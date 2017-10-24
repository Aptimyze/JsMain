var data = require('./ErrorList.json');

export const ErrorConstantsMapping = (type)=>  {
if(!data[type]) {
		return data["Default"];
	}
	else return data[type];
} 