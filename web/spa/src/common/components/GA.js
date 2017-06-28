import React from 'react'
import ReactGA from 'react-ga'

export default class GA extends React.Component {
	constructor(props) {
  		super();
  		this.trackJsEventGA = this.trackJsEventGA.bind(this);
  		let j_domain = ".jeevansathi.com";
  		let ucode = "UA-179986-1"
		this.state = {
  			j_domain,
  			ucode,
  			scriptLoaded: false
  		}
  		var scriptElems = document.getElementsByTagName("script");
  		var scriptAdded = false;
  		var _this = this;
  		for (var i = 0; i < scriptElems.length; i++) {
  			if(scriptElems[i].src == "http://www.google-analytics.com/ga.js") {
  				scriptAdded = true;
  			}
  		}
  		if(scriptAdded == false) {
  			let ga = document.createElement('script'); 
	  		ga.type = 'text/javascript'; 
	  		ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			ga.onload = function() {
				_this.state.scriptLoaded = true;
			}
			let s = document.getElementsByTagName('head')[0]; 
			s.appendChild(ga);	
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

    render() {
    	return(
    		<div></div>
    	);
	    
    }
}