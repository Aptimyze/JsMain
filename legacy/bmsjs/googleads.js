/*
*	Funtion Name: makeRequest()
*	Description : To request for a connection
*	Input Parameters: url, location id
*/
function makeRequest(url,loc_id) {
	var http_request = false;
        if (window.XMLHttpRequest) { // For Mozilla and other browsers
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
            	}
            } 
	    else if (window.ActiveXObject) { // For Internet Explorer
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                	http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } 
		catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        http_request.onreadystatechange = function() { displayContents(http_request,loc_id); };
        http_request.open('GET', url, true);
        http_request.send(null);

}

/*	Function Name : displayContents()
*  	Description : 
*	This function returns the innerHTML for the parsed and formatted google ads
*/
function displayContents(http_request,loc_id) {
	if (http_request.readyState == 4) {
        	if (http_request.status == 200) {
            		var doc = http_request.responseText;
			if(loc_id == 1) 		//Location Ids: Bottom(1), Right(2)
			{
				var arrayContent=doc.split("|XX|XX|");
				if(document.getElementById("GOOGLEADS_BOT"))
					document.getElementById("GOOGLEADS_BOT").innerHTML=arrayContent[1];
				if(document.getElementById("GOOGLEADS_RT"))
					document.getElementById("GOOGLEADS_RT").innerHTML=arrayContent[0];
			}
			else
			{
				if(document.getElementById("GOOGLERL_RT"))
					document.getElementById("GOOGLERL_RT").innerHTML=doc;
			}
		} 
		else {
//                alert('There was a problem with the request.');
            	}
        }

}

