require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import {getCookie} from '../../common/components/CookieHelper';
import ReportAbuse from "./ReportAbuse";

export default class ThreeDots extends React.Component{
  constructor(props){
    super();
    this.state = {
      showLayer: false,
      showAbuseLayer: false
    };

  }
  getThreeDotLayer() {
    this.setState({
      showLayer: true
    });
    document.getElementById("ProfilePage").classList.add("scrollhid");
  }
  closeThreeDotLayer() {
    this.setState({
      showLayer: false
    });
    document.getElementById("ProfilePage").classList.remove("scrollhid");
  }
  showAbuseLayer() {
    this.setState({showAbuseLayer: true})
  }
  closeAbuseLayer() {
    this.setState({showAbuseLayer: false})
    document.getElementById("vpro_tapoverlay").classList.remove("dn");
  }

  render(){
    var reportAbuseView;
    if(this.state.showAbuseLayer == true) {
      reportAbuseView = <ReportAbuse closeAbuseLayer={() => this.closeAbuseLayer()} profileThumbNailUrl={this.props.profileThumbNailUrl} />
      document.getElementById("vpro_tapoverlay").classList.add("dn");
    }
    var layerView;
    if(this.state.showLayer == true) {
        layerView = <div id="contactOverlay" className="posabs dispbl scrollhid">
            <div id="vpro_tapoverlay" className="posabs vpro_tapoverlay">
                <div className="threeDotOverlay white fullwid" id="commonOverlayTop">
                    <div id="3DotProPic" className="txtc">
                      <div id="photoIDDiv" className="photoDiv">
                        <img id="ce_photo" className="srp_box2 mr6" src={this.props.profileThumbNailUrl} />
                      </div>
                      <div className="f14 white fontlig opa80 pt10" id="topMsg">Connect with {this.props.username}</div>
                    </div>

                    <div className="wid49p txtc mt45 dispibl" id="INITIATE">
                      <i className="mainsp msg_srp" id="otherimage0"></i>
                      <div className="f14 white fontlig lh30" id="otherlabel0">Send Interest</div>
                    </div>

                    <div className="wid49p txtc mt45 dispibl" id="CONTACT_DETAIL">
                      <i className="mainsp vcontact" id="otherimage1"></i>
                      <div className="f14 white fontlig lh30" id="otherlabel1">View Contacts</div>
                    </div>

                    <div className="wid49p txtc mt45 dispibl" id="SHORTLIST_1">
                      <i className="mainsp srtlist" id="otherimage2"></i>
                      <div className="f14 white fontlig lh30" id="otherlabel2">Shortlist</div>
                    </div>

                    <div className="wid49p txtc mt45 dispibl" id="IGNORE_1">
                      <i className="mainsp ignore"></i>
                      <div className="f14 white fontlig lh30" id="otherlabel3">Block</div>
                    </div>
                    <div onClick={() => this.showAbuseLayer()}  className="wid49p txtc mt45 fl" id="REPORT_ABUSE_1">
                      <i className="reportAbuse mainsp"></i>
                      <div className="f14 white fontlig lh30" id="otherlabel4">Report Abuse</div>
                    </div>
                    <div className="dispibl fullwid mt45">
                      <div onClick={() => this.closeThreeDotLayer()} className="mainsp srp_close1"></div>
                    </div>
                </div>
            </div>
            <img src="https://www.jeevansathi.com/images/jsms/membership_img/revamp_bg1.jpg" className="classimg1 vpro_pos1 posfix z100" />
        </div>
    }
    return(
      <div>
        {layerView}
        {reportAbuseView}
        <div onClick={() => this.getThreeDotLayer()} className="posabs srp_pos2">
          <i className="mainsp threedot1"></i>
        </div>
      </div>
      );
  }
  	
}
