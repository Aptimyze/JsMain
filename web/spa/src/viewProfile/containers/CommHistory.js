require ('../style/profile.css')
import React from "react";
import {connect} from "react-redux";
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';

class CommHistory extends React.Component {

    constructor(props) {
    	super();
    	this.state = {
    		showLoader: true,
        tupleDim : {'width' : window.innerWidth,'height': window.innerHeight},
    	}
    }
    componentDidMount() {
    	window.addEventListener('scroll', (event) => {
    		event.preventDefault();
    	})
    	// document.getElementById("comHistoryOverlay").style.height = window.innerHeight+"px";
    	// document.getElementById("commHistoryScroller").style.height = (window.innerHeight - 110) + "px";
    	// document.getElementById("commHistoryScroller").style.width = (window.innerWidth) + "px";
      //console.log(window.outerHeight - document.getElementById("commHistory_header").clientHeight);
      document.getElementById("commHistoryScroller").style.height = window.outerHeight - document.getElementById("commHistory_header").clientHeight+"px";
        this.props.showHistory(this.props.profileId);
    }
    componentWillReceiveProps(nextProps)
    {
        try
        {
          if(nextProps.historyData.history == null)
          {
  			       document.getElementById("commHistoryScroller").innerHTML += "<div class='disptbl hgtInherit'><div class='dispcell vertmid white txtc'>Your interaction with "+ this.props.username + " will appear here.</div></div>";
      	  }
          else
          {
              let htmlString='';
              let data = nextProps.historyData.history;

      		    for(var i=0; i< data.length; i++)
              {
      			       if(data[i].ismine == true)
                   {
      				           htmlString += "<div id='comm_"+i+"' class='brdr4'><div class='pad3'>";
      				           htmlString += "<div class='fontlig f14 white txtr'>"+data[i].message+"<span class='dispbl f12 pt5'>"+data[i].time+"</span></div></div></div>";
      			       }
  				         else
                   {
  					              htmlString += "<div id='comm_"+i+"' class='brdr4'><div class='pad3'>";
  					              htmlString += "<div class='fontlig f14 white txtl'>"+data[i].message+"<span class='dispbl f12 pt5'>"+data[i].time+"</span></div></div></div>";
  				          }

      		    }
              console.log(htmlString);
      		      document.getElementById("commHistoryScroller").innerHTML += htmlString;
      	  }
      	  this.setState ({
              showLoader : false
          });
        }
        catch(e)
        {
          console.log("1. excpection from communication history: "+ e);
        }
    }
    closeHistory(){
    	this.props.closeHistory();
    }
    getcommHistory_topView()
    {
     return (<div className="posrel clearfix fontthin ce_hgt1">
        <div className="posabs com_left1">
          <img id="imageId" src={this.props.profileThumbNailUrl} className="com_brdr_radsrp ce_dim1"/>
        </div>
        <div className="posabs com_right1">
          <i className="mainsp com_cross"  onClick={() => this.closeHistory()}></i>
        </div>
        <div className="txtc f19 white pt10" id="usernameId">{this.props.username}</div>
      </div>);
  }

    render() {
    	var loaderView;
    	if(this.state.showLoader)
    	{
    		loaderView = <Loader show="page"></Loader>;
    	}
		return(
		    <div id="comHistoryOverlay">
            <div className="posfix ce-bg ce_top1 ce_z101" style={this.state.tupleDim}>
              <div className="posrel">
                <a href="#"  className="ce_overlay ce_z102" > </a>
                <div className="posabs ce_z103 ce_top1 fullwid">

                  <div className="pad18 brdr4" id="commHistory_header">
                    {this.getcommHistory_topView()}
                  </div>

                  <div className="ce_scoll1" id="commHistoryScroller">


                  </div>



                </div>
              </div>
            </div>
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
