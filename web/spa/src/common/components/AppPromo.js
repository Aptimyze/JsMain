import React from "react";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions"
import {getCookie,setCookie} from "../../common/components/CookieHelper";

export default class AppPromo extends React.Component {	
	constructor(props) {
  		super();
  		let urlString = "";
  		if(getAndroidVersion()) {
  			urlString = "https://jeevansathi.com/static/appredirect?type=androidLayer";
  		} else if(getIosVersion()) {
  			urlString = "https://jeevansathi.com/static/appredirect?type=iosLayer";
  		}
	    this.state = {
	        appHref : urlString,
	        parentComp: props.parentComp
	    };
    }

    componentDidMount() {
    	let _this = this;
    	if(this.state.parentComp == "LoginPage") {
    		let AppPromo = true;
    		if(getCookie("AppPromo")) {
    			AppPromo = false;
    		} 
    		if(AppPromo == true) {
    			setTimeout(function(){ 
			       document.getElementById("AppPromo").classList.remove("ham_minu20");
			       document.getElementById("mainContent").className +=" ham_b100 ham_plus20";
	    		}, 10);
	    		setCookie("AppPromo","jeevansathi",3); 
    		}
    	} else if(this.state.parentComp == "others" && getCookie("AppPromo") == false) {
    		setTimeout(function(){ 
			    document.getElementById("AppPromo").classList.remove("ham_minu20");
			    document.getElementById("mainContent").className +=" ham_b100 ham_plus20";
	    	}, 10);
	    	setCookie("AppPromo","jeevansathi",3); 
    	}
    }	

    closeLayer() {
    	let _this = this;
    	document.getElementById("AppPromo").classList.add("ham_minu20");
	    document.getElementById("mainContent").classList.remove("ham_plus20");
	    setTimeout(function(){ 
	       _this.props.removePromoLayer();
	    }, 2000);  
    }
  
    render() {
	    return (
	        <div id="AppPromo" className="ham_b20_n ham_minu20 newocbbg1 fullwid">   	            	
	           	<div className = "padAppPromo clearfix">
	           	    <div className = "fl pt20">            	
	           	    	<div id="closePromoBtn" onClick={() => this.closeLayer()} className ="ocbnewimg ocbclose"></div>            
	           	    </div>        	
	           	    <div className = "fl padlAppPromo">            	
	           	    	<div className = "ocbnewimg logoocb"></div>            
	           	    </div>            
	           	    <div className = "fr pt10">            	
	           	    	<div id="installApp" className = "newocbbg2 ocbbr1 ocbp1">                	
	           	    		<a href={this.state.appHref} target="_blank" className = "white fontmed f13">Install</a>                
	           	    	</div>            
	           	    </div>             
	           	    <div className = "fr pt13 padr10">            	
	           	    	<div className = "fontSizeAppPromo fontmed">Jeevansathi App | 3 MB </div>                
	           	    	<div className = "ocbnewimg ocbstar fr"></div>            
	           	    </div>        
	          	</div>    
	        </div>
	    );
    } 
}





















