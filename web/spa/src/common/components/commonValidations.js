import * as CONSTANTS from '../constants/ErrorConstantsMapping';

export const validateInput = (type, value) =>{
	var re;
	switch (type)
	{
		case 'phone':
			re = /^((\+)?[0-9]*(-)?)?[0-9]{7,}$/i;
		break;

    case 'email':
			re = validateEmail(value);
      return re;
		break;

	}
	return re.test(value);
}

export const validateEmail = (email) => {
  var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
  var email = email.trim();
  var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
  var start = email.indexOf('@');
  var end = email.lastIndexOf('.');
  var diff = end-start-1;
  var user = email.substr(0,start);
  var len = user.length;
  var domain = email.substr(start+1,diff).toLowerCase();
  var emailVerified ={};
  switch(true)
  {
  case (invalidDomainArr.indexOf(domain.toLowerCase()) !=  -1):
  case (domain == 'gmail' && !(len >= 6 && len <=30)):
  case ((domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' ) && !(len >= 4 && len <=32)):
  case (domain == 'rediff' && !(len >= 4 && len <=30)):
  case (domain == 'sify' && !(len >= 3 && len <=16) ) :
  case (email=="") :
  case (!email_regex.test(email)):
        emailVerified = CONSTANTS.ErrorConstantsMapping('InvalidEmail');
        return emailVerified;
  break;

  default :
          emailVerified.responseCode = 0;
          emailVerified.errorMessage = "A link has been sent to your email id "+email+" click on the link to verify your email.";
          return emailVerified;
  break;
  }
}
