
export const validateEmail =(email)=>  {
    var x = email;
    var re = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    return re.test(email);
    }

export const validateInput = (type, value) =>{
	var re;
	switch (type)
	{
		case 'phone':
			re = /^((\+)?[0-9]*(-)?)?[0-9]{7,}$/i;
		break;
	}
	return re.test(value)



}

export const f1 =()=>
{


console.log('palashf1');


}
