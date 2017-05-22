/*
var parentUsername = "rawat";
var username = "lavesh";
canIShowNext(parentUsername,username);
*/
function canIShowNext(parentUsername,username)
{
    //return false; // ROLLBACK ROLLBACK
 var debug=0;
 if(debug==1) 
	console.log("PARENT-----"+parentUsername);
 if(debug==1)
	console.log("USER----"+username);

	SessionStorageView = new SessionStorage;
	var str = SessionStorageView.getUserData("viewSim4");
	var newString='';
	if(debug==1)
		console.log("STR----"+str);
        if(str && parentUsername)
        {
                if(str.indexOf(',')!='-1')
                {
                        var res = str.split(",");
                        if(debug==1)
				console.log(res[0]+"-----"+res[1]+"-----"+parentUsername);
                        if(res[0]== parentUsername)
                        {
				if(debug==1)
					console.log("33333");
				if(debug==1)
					console.log("here");
                                newString = res[0]+","+username;
                        }
			else
			{
				if(debug==1)
					console.log("44444");
				if(debug==1)
					console.log("there");
				return false;
			}
                }
		else if(str!=username)
		{ 
			if(debug==1)
				console.log("22222");
                	newString = parentUsername+","+username;
		}
        }
        else{
		if(debug==1)
			console.log("111111");
		newString = username;
}
	if(newString)
		SessionStorageView.storeUserData("viewSim4",newString);
	if(debug==1)
		console.log("newString"+newString);
	return true;
}
