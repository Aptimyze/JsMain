require ('../style/profile.css')
import React from "react";
import {connect} from "react-redux";
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import * as CONSTANTS from '../../common/constants/apiConstants';

export default class CommHistory extends React.Component {

    constructor(props) {
    	super();
    	this.state = {
    		showLoader: true,
        tupleDim : {'width' : window.innerWidth,'height': window.innerHeight},
        getRes: null,
        pageN: 1,
        messages:[]
    	}
      this.ComHistScrollEvent = this.ComHistScrollEvent.bind(this);

    }
    componentDidMount() {

      document.getElementById("ProfilePage").classList.add("scrollhid");

      document.getElementById("commHistoryScroller").style.height = window.innerHeight - document.getElementById("commHistory_header").clientHeight+"px";


      this.callapiComHist();


    }
    componentDidUpdate(){

      let e = document.getElementById('commHistoryScroller');

      if(this.state.pageN==2)
      {
        e.scrollTop =  e.scrollHeight;
      }
      else
      {
        e.scrollTop = e.scrollHeight-(this.scrollTop);
      }
    }
    componentWillUnmount(){

      document.getElementById("ProfilePage").classList.remove("scrollhid");

    }
    callapiComHist()
    {
      let _this = this,pchecksum = this.props.profileId,newN;
      let call_url = CONSTANTS.COMM_HISTORY+"?profilechecksum="+pchecksum+"&pageNo="+this.state.pageN+"&dataType=json";
      commonApiCall(call_url,{},'','POST').then(function(response){
          if(response.history!=null)
          {
            let recRes = response.history.reverse();
            if(_this.state.messages.length==0)
            {
              _this.state.messages = recRes;
            }
            else
            {
              _this.scrollTop = document.getElementById('commHistoryScroller').scrollHeight;
              _this.state.messages = recRes.concat(_this.state.messages);

            }
            newN =   _this.state.messages;
            let pageCount=_this.state.pageN;
            pageCount++;
              _this.setState({
                showLoader: false,
                getRes: response,
                pageN : pageCount,
                messages : newN
              })
          }
          else
          {
            _this.setState({
              showLoader: false,
              getRes: response
            })
          }


        });
    }
    ComHistScrollEvent()
    {
        let e = document.getElementById("commHistoryScroller");
        if(e.scrollTop==0)
        {
         this.showMessagesOnScroll(e);
        }
     }
     showMessagesOnScroll(e)
     {

       let _this =this;
       if(this.state.getRes.nextPage!="false")
       {
         _this.setState({
           showLoader: true,
         })
           _this.callapiComHist();
       }
       else return;
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
  getcommHistory_listing()
  {
      let data='';
      if(this.state.getRes!=null)
      {

        if(this.state.getRes.history==null)
        {
          data = <div className="disptbl hgtInherit">
                    <div className="dispcell vertmid white txtc">
                      Your interaction with {this.props.username} will appear here.
                    </div>
                 </div>
        }
        else
        {
          data = this.state.messages.map((historyList,index) => {
            let alignT;
            if(historyList.ismine==true)
            {
               alignT = "txtr";
            }
            else
            {
              alignT= "txtl";
            }
            return <div id={"comHist"+index} className="brdr4">
                      <div className={"pad3 "+ alignT}>
                        <div className='fontlig f14 white'>
                          {historyList.message}
                        </div>
                        <div className="dispbl color1 f12 pt5">
                            <span className="dispibl">{historyList.header}</span>
                            <span className="dispibl padl5">{historyList.time}</span>
                        </div>
                      </div>
                  </div>;
          })
        }


      }



      return data;

  }

    render() {
    	let loaderView;
    	if(this.state.showLoader)
    	{
    		loaderView = <Loader show="writeMessageComp"  loaderStyles={{width: '100%',top: window.outerHeight/2 - 35 +'px'}}></Loader>;
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
                  {loaderView}

                  <div className="ce_scoll1" id="commHistoryScroller" onScroll={this.ComHistScrollEvent}>
                    {this.getcommHistory_listing()}
                  </div>



                </div>
              </div>
            </div>
        </div>
		);
	}
}
