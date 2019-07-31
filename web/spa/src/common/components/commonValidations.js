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
  }/*else{
          var nameArr = name_of_user.split(" ");
          if(nameArr.length<2){
                return {responseCode:1,responseMessage:"Please provide your first name along with surname, not just the first name"}
          }else{
               return true;
          }
  }*/
 return true;
};



export function validatePasswords(newPassword, confirmPassword, inputString){

  var errorMessagePasswordValidation = ["Passwords do not match", 
                    "The password you have chosen is not secure", 
                    "Length of New Password should be atleast 8 characters",
                    "New password cannot consist of only numbers."];
  var response = {
    status : "success",
    message : "-"
  };

  newPassword = newPassword.trim();
  confirmPassword = confirmPassword.trim();
  
  if(newPassword.length != confirmPassword.length || (confirmPassword != newPassword)){
    response.status = "failure";
    response.message = errorMessagePasswordValidation[0];
    return response;
  }

  if(newPassword.length < 8){
    response.status = "failure";
    response.message = errorMessagePasswordValidation[2];
    return response;
  }

  let isAllNumericFlag = isAllNumeric(newPassword);
  if( (!isAllNumericFlag)  && checkCommonPassword(newPassword) && checkResetPasswordUserName(newPassword, inputString)){
    response.status = "success";
    response.message = "valid passwords";
    return response;
  }else if(isAllNumericFlag){
    response.status = "failure";
    response.message = errorMessagePasswordValidation[3];
    return response;
  }else{
    response.status = "failure";
    response.message = errorMessagePasswordValidation[1];
    return response;
  }
}
 
function checkCommonPassword(pass)
{
  var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890");
  if (inArray(pass.toLowerCase(),invalidPasswords))
    return false;
  return true;
}

function checkResetPasswordUserName(pass, email)
{
  // var email = $("#emailStr").val();
  var end = email.indexOf('@');
    var username = email.substr(0,end);
    return (String(pass) != String(username) && String(pass) != String(email));
}

function isAllNumeric(password){
  var patt = new RegExp(/^[0-9]+$/);
  return patt.test(password);
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}