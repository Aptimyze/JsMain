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



export function aadhaarVerificationCheck(str){
 var d = [[0,1,2,3,4,5,6,7,8,9],
         [1,2,3,4,0,6,7,8,9,5],
         [2,3,4,0,1,7,8,9,5,6],
         [3,4,0,1,2,8,9,5,6,7],
         [4,0,1,2,3,9,5,6,7,8],
         [5,9,8,7,6,0,4,3,2,1],
         [6,5,9,8,7,1,0,4,3,2],
         [7,6,5,9,8,2,1,0,4,3],
         [8,7,6,5,9,3,2,1,0,4],
         [9,8,7,6,5,4,3,2,1,0]];
 var p = [[0,1,2,3,4,5,6,7,8,9],
         [1,5,7,6,2,8,3,0,9,4],
         [5,8,0,3,7,9,6,1,4,2],
         [8,9,1,6,0,4,3,5,2,7],
         [9,4,5,3,1,2,6,8,7,0],
         [4,2,8,6,5,7,3,9,0,1],
         [2,7,9,3,8,0,6,4,1,5],
         [7,0,4,6,9,1,3,2,5,8]];
 var j = [0,4,3,2,1,5,6,7,8,9];

 return function(){
     var c = 0;
     str.replace(/\D+/g,"").split("").reverse().join("").replace(/[\d]/g, function(u, i, o){
         c = d[c][p[i&7][parseInt(u,10)]];
     });
     return (c === 0);
 };
};

export function validateNameOfUser(name){
  var name_of_user=name;
  name_of_user = name_of_user.replace(/\./gi, " ");
  name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
  name_of_user = name_of_user.replace(/\,|\'/gi, "");
  name_of_user = name_of_user.replace(/\s+/gi, " ").trim();
  var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
  if(name_of_user.trim()== "" || !allowed_chars.test((name_of_user).trim())){
          return CONSTANTS.ErrorConstantsMapping("invalidName");
  }else{
          var nameArr = name_of_user.split(" ");
          if(nameArr.length<2){
                return {responseCode:1,responseMessage:"Please provide your first name along with surname, not just the first name"}
          }else{
               return true;
          }
  }
 return true;
};
