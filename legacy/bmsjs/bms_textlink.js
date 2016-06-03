		var http_request;
          	function ServeTextLink(zone,profileid,subzone)
	  	{
        		if (window.XMLHttpRequest)  // Mozilla, Safari,...
        		{
            			http_request = new XMLHttpRequest();
            			if (http_request.overrideMimeType)
            			{
                			http_request.overrideMimeType('text/xml');
            			}
        		}
        		else if (window.ActiveXObject)  // IE
        		{
            			try
            			{
                			http_request = new ActiveXObject("Msxml2.XMLHTTP");
            			} catch (e) 
				{
                			try
                			{
                    				http_request = new ActiveXObject("Microsoft.XMLHTTP");
                			} catch (e) {}
            			}
        		}
			if (!http_request)
        		{
            			alert('Giving up :( Cannot create an XMLHTTP instance');
            			return false;
        		}
			http_request.onreadystatechange = ShowTextLink;
			var params = 'zonestr='+zone+'&data='+profileid+'&subzone='+subzone+'&isTextLink=Y';
			//http_request.open('GET', 'http://192.168.2.220/bmsjs/bms_display_final.php?zonestr=12&data=156193&subzone=1', true);

			//http_request.open('GET', 'http://192.168.2.220/bmsjs/bms_display_final.php?'+params, true);
			http_request.open('GET', 'http://www.ieplads.com/bmsjs/bms_display_final.php?'+params, true);
			http_request.send(null);
		}

		function ShowTextLink()
		{	
			if(http_request.readyState == 4)
        		{
				if(http_request.status == 200)
				{
					var response=http_request.responseText;
					var  servetextlink = document.getElementById('textlink');
					servetextlink.innerHTML = response;
				}
                		else
                		{
                        		alert('There was a problem with the request.');
                		}
        		}
		}	
