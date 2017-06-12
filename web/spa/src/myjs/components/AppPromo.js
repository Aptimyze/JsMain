import React from "react";

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
	        appHref : urlString
	    };
    }

    componentDidMount() {
    	let _this = this;
    	setTimeout(function(){
	       document.getElementById("appPromo").classList.remove("ham_minu20");
	       document.getElementById("mainContent").className +=" ham_b100 ham_plus20";
	    }, 10);
    }

    closeLayer() {
    	let _this = this;
    	document.getElementById("appPromo").classList.add("ham_minu20");
	    document.getElementById("mainContent").classList.remove("ham_plus20");
	    setTimeout(function(){
	       _this.props.removePromoLayer();
	    }, 2000);
    }

    render() {
	    return (
	        <div ref="appPromo" id="appPromo" className = "ham_b20_n ham_minu20 newocbbg1 fullwid" >
	           	<div className = "padAppPromo clearfix">
	           	    <div className = "fl pt20">
	           	    	<div onClick={() => this.closeLayer()} className ="ocbnewimg ocbclose"></div>
	           	    </div>
	           	    <div className = "fl padl5">
	           	    	<div className = "ocbnewimg logoocb"></div>
	           	    </div>
	           	    <div className = "fr pt10">
	           	    	<div className = "newocbbg2 ocbbr1 ocbp1">
	           	    		<a href={this.state.appHref} target="_blank" className = "white fontmed f13">Install</a>
	           	    	</div>
	           	    </div>
	           	    <div className = "fr pt13 padr10">
	           	    	<div className = "f14 fontmed">Jeevansathi App | 3 MB </div>
	           	    	<div className = "ocbnewimg ocbstar fr"></div>
	           	    </div>
	          	</div>
	        </div>
	    );
    }
}
