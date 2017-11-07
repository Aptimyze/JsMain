//import React from "react";

export default class GA{
	constructor(props) {
  		//this.trackJsEventGA = this.trackJsEventGA.bind(this);
      let domainCode = [];
      domainCode[".hindijeevansathi.in"]="UA-20942264-1";
      domainCode[".jeevansathi.co.in"]="UA-20941176-1";
      domainCode[".marathijeevansathi.in"]="UA-20941180-1";
      domainCode[".punjabijeevansathi.com"]="UA-20941670-1";
      domainCode[".punjabijeevansathi.in"]="UA-20941669-1";
      domainCode[".jeevansathi.com"]="UA-179986-1";      

      let host_url="http://"+window.location.host;      
      let j_domain=host_url.match(/:\/\/[\w]{0,10}(.[^/]+)/)[1];      
      j_domain=j_domain.toLowerCase();
      let ucode=domainCode[j_domain];      
      
      if(ucode)
      {
        this.state = {
          j_domain:j_domain,
          ucode:ucode,
          scriptLoaded: false,          
        };
        
        (function() {
          let ga = document.createElement('script'); 
          ga.type = 'text/javascript'; 
          ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          let s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
      }
  	}

  	trackJsEventGA(category, action, label, value)
    {
      if(this.state.ucode){
        var _gaq = window._gaq || _gaq;

        _gaq.push(['_setAccount', this.state.ucode]);
        _gaq.push(['_setDomainName', this.state.j_domain]);
        _gaq.push(['_trackPageview']);
        _gaq.push(['_trackPageLoadTime']);
        if(value){
          _gaq.push(['_trackEvent', category, action, label, value]);
        } else {
          _gaq.push(['_trackEvent', category, action, label]);
        }            
      }
    }

  	/*componentDidMount() {console.log("did mount")
  		var _this = this;      
  		if(this.state.scriptLoaded == true) {
  			this.trackJsEventGA("jsms","new","1");		
  		} else {
  			setTimeout(function(){
  				_this.trackJsEventGA("jsms","new","1");
			},1000); 		
  		}	
  		
    }*/

    /*this function is used to get GENDER of LoggedInProfile to be used for GA.
      Expecting M/F in case of loggedIn and "" in case of LoggoutOut
    */
    getGenderForGA()
    {
      let gender = "M";//localStorage.getItem("Gender");
      if(gender)
      {
        return gender;
      }
      else
      {
        return "Unregistered";
      }
    }

    /*render() {
    	return(
    		<div></div>
    	);
	    
    }*/
}