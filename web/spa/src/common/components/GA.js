import React from 'react'
import ReactGA from 'react-ga'

export default class GA extends React.Component {
	constructor(props) {
  		super();
  		this.trackJsEventGA = this.trackJsEventGA.bind(this);  		
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
      this.state = {
        j_domain,
        ucode,
        scriptLoaded: false
      }
      if(ucode)
      {
        console.log(ucode);
        let _gaq = _gaq || [];

        _gaq.push(['_setAccount', ucode]);
        _gaq.push(['_setDomainName', j_domain]);
        _gaq.push(['_trackPageview']);
        _gaq.push(['_trackPageLoadTime']);
        (function() {
          let ga = document.createElement('script'); 
          ga.type = 'text/javascript'; 
          ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          let s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

      //     var scriptElems = document.getElementsByTagName("script");
      //     console.log(scriptElems);
      //     var scriptAdded = false;
      //     var _this = this;
      //     for (var i = 0; i < scriptElems.length; i++) {
      //      if(scriptElems[i].src == "http://www.google-analytics.com/ga.js") {
      //       scriptAdded = true;
      //     }
      //   }
      //   if(scriptAdded == false) 
      //   {
      //    let ga = document.createElement('script'); 
      //    ga.type = 'text/javascript'; 
      //    ga.async = true;
      //    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      //    ga.onload = function() {
      //     _this.state.scriptLoaded = true;
      //   }
      //   let s = document.getElementsByTagName('head')[0]; 
      //   s.appendChild(ga);	
      // }
    }
  	}

  	trackJsEventGA(category, action, label, value){ 
		if(this.state.ucode){
			var _gaq = window._gaq || _gaq;
			_gaq.push(['_setAccount', 'UA-179986-1']);
			_gaq.push(['_setDomainName', '.jeevansathi.com']);
			if(value){
				_gaq.push(['_trackEvent', category, action, label, value]);
			} else {
				_gaq.push(['_trackEvent', category, action, label]);
			}
		}
	}

  	componentDidMount() {
  		var _this = this;
  		if(this.state.scriptLoaded == true) {
  			this.trackJsEventGA("jsms","new","1");		
  		} else {
  			setTimeout(function(){
  				_this.trackJsEventGA("jsms","new","1");
			},3000); 		
  		}	
  		
    }

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

    render() {
    	return(
    		<div></div>
    	);
	    
    }
}