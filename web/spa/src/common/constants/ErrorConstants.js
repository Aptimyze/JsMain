export const ErrorConstant = (type)=>  {
   switch (type) {
	    case "ValidEmail":
	        return 'Provide a valid email ID';
	    case "LoginDetails":
	        return "Provide your login details";
	    case "EnterEmail":
	        return "Provide your email ID";
	    case "EnterPass":
	       return "Provide your password";
	    default:
	        return "Something went wrong";
	}
} 