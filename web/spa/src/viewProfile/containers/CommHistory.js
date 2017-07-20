require ('../style/profile.css')
import React from "react";
import {connect} from "react-redux";
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';

class CommHistory extends React.Component {

    constructor(props) {
    	super();
    	this.state = {
    		showLoader: true
    	} 
    }
    componentDidMount() {
    	window.addEventListener('scroll', (event) => {
    		event.preventDefault();
    	})
    	document.getElementById("comHistoryOverlay").style.height = window.innerHeight+"px";
    	document.getElementById("commHistoryScroller").style.height = (window.innerHeight - 110) + "px";  
    	document.getElementById("commHistoryScroller").style.width = (window.innerWidth) + "px";  
        this.props.showHistory(this.props.profileId); 
    }
    componentWillReceiveProps(nextProps)
    {
        if(nextProps.historyData.history == null) {
			document.getElementById("commHistoryScroller").innerHTML += "<div class='disptbl hgtInherit'><div class='dispcell vertmid white txtc'>Your interaction with "+ this.props.username + " will appear here.</div></div>";
    	} else {
    		let htmlString = "", data = nextProps.historyData.history;
    		for(var i=0; i< data.length; i++) {
    			if(data[i].ismine == true) {
    				htmlString += "<div id='comm_"+i+"' class='vpro_padl'>";
    				htmlString += "<div class='fontlig f14 white txtr padr15'>"+data[i].message+"<span class='dispbl f12 pt5'>"+data[i].time+"</span></div></div>";
    			}
				else {
					htmlString += "<div id='comm_"+i+"' class='vpro_padr'>";
					htmlString += "<div class='fontlig f14 white txtl padl15'>"+data[i].message+"<span class='dispbl f12 pt5'>"+data[i].time+"</span></div></div>";
				}  
				htmlString+= "<div class='vpro_padr'><div class='brdr4'></div></div>"
    		}
    		document.getElementById("commHistoryScroller").innerHTML += htmlString;
    	}
    	this.setState ({
            showLoader : false
        }); 
    }
    closeHistory(){
    	this.props.closeHistory();	
    }
    render() {
    	var loaderView;
    	if(this.state.showLoader) 
    	{
    		loaderView = <Loader show="page"></Loader>;
    	}
		return(
			<div id="comHistoryOverlay" className="posabs dispbl scrollhid">
			{loaderView}
				<div className="posabs vpro_tapoverlay">
					<div className="posrel fullwid z105">
						<div className="pad18 brdr4" id="comm_header">
							<div className="posrel clearfix fontthin">
								<div className="posabs com_left1"> 
									<img src={this.props.profileThumbNailUrl} className="com_brdr_radsrp wid50 hgt50" />
								</div>
								<div className="posabs com_right1"> 
									<i id="comCloseBtn" onClick={() => this.closeHistory()} className="mainsp com_cross cursp"></i>
								</div>
								<div className="txtc f19 white pt10">
									{this.props.username}
								</div>
							</div>
						</div>
						<div id="commHistoryScroller">
							
						</div>
					</div>
				</div>
                <img src="https://www.jeevansathi.com/images/jsms/membership_img/revamp_bg1.jpg" className="classimg1 vpro_pos1 posfix z100" />
			</div>
		);
	}
}
const mapStateToProps = (state) => {
    return{
       historyData: state.ProfileReducer.historyData
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        showHistory: (profilechecksum) => {
            let call_url = "/api/v1/contacts/history?profilechecksum="+profilechecksum+"&pageNo=1&dataType=json";
            commonApiCall(call_url,{},'SHOW_HISTORY_INFO','GET',dispatch,false);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(CommHistory)

