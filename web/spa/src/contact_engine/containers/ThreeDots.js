require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import { performAction } from "./contactEngine.js";
import * as CONSTANTS from '../../common/constants/apiConstants';
import {getCookie} from '../../common/components/CookieHelper';
import ReportAbuse from "./ReportAbuse";
import BlockPage from "./BlockPage";
import Loader from "../../common/components/Loader";


class ThreeDots extends React.Component{
  constructor(props){
    super(props);
    this.state = {
      showLayer: false,
      showAbuseLayer: false,
      showLoader:false,
      showIgnoreLayer:false,
      showIgnoreLayerMessage:'',
      ignoreButton:{}
    };
    console.log(props,'---------');

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

  showIgnoreLayer(message,button) {
    this.setState({showIgnoreLayer: true,showIgnoreLayerMessage:message,ignoreButton:button})
  }

  showLoaderDiv() {
      this.setState({
          showLoader:true
      });
  }
  hideLoaderDiv() {
      this.setState({
            showLoader:false
      });
    }

  callBackFunctionThreeDots(jsonOb){
    switch(jsonOb.button.action)
    {
      case 'CONTACT_DETAIL':
      break;
      case 'IGNORE':
        if ( jsonOb.button.params.indexOf("&ignore=0") !== -1)
        {
          this.props.changeButton({'button':jsonOb.response.button_after_action.buttons.others[jsonOb.index]},jsonOb.index);
          this.closeThreeDotLayer();
        }
        else
        {
        this.props.changeButton({'button':jsonOb.response.button_after_action.buttons.primary[0]},jsonOb.index);
          this.showIgnoreLayer(jsonOb.response.message,jsonOb.response.button_after_action.buttons.primary[0]);
        }

        break;
      default:
       this.props.changeButton(jsonOb.response.buttondetails,jsonOb.index);
      break;
    }

  }


  manageThreeDotsButton(button,index){
    this.showLoaderDiv();
    switch(button.action)
    {

      default:
      performAction(this.props.profilechecksum,(response)=>this.callBackFunctionThreeDots({index:index,button:button,response:response}),button);
        break;
    }
  }


  closeAbuseLayer() {
    this.setState({showAbuseLayer: false})
    document.getElementById("vpro_tapoverlay").classList.remove("dn");
  }

  closeBlockPageLayer()
  {
    this.setState({showIgnoreLayer: false});
    this.closeThreeDotLayer()
    document.getElementById("vpro_tapoverlay").classList.remove("dn");
  }


  componentWillReceiveProps(nextProps)
  {
    this.hideLoaderDiv();
  }

  render(){

    var reportAbuseView;
    var showIgnoreLayerView;
    if(this.state.showAbuseLayer == true) {
      reportAbuseView = <ReportAbuse username={this.props.username} profilechecksum={this.props.profilechecksum} closeAbuseLayer={() => this.closeAbuseLayer()} profileThumbNailUrl={this.props.profileThumbNailUrl} />
      document.getElementById("vpro_tapoverlay").classList.add("dn");
    }

    if(this.state.showIgnoreLayer == true) {
      showIgnoreLayerView = <BlockPage message={this.state.showIgnoreLayerMessage}profileThumbNailUrl={this.props.profileThumbNailUrl} closeBlockPageLayer={() => this.closeBlockPageLayer()} unblock = {() => this.manageThreeDotsButton(this.state.ignoreButton,3)}/>
      document.getElementById("vpro_tapoverlay").classList.add("dn");
    }
    var layerView;
    if(this.state.showLayer == true) {
      var buttons = (this.props.buttondata.buttons.others);
      var loaderView;
      if(this.state.showLoader)
      {
        loaderView = <Loader show="page"></Loader>;
      }

      var imageList = {'INITIATE':'mainsp msg_srp','CONTACTDETAIL':'mainsp vcontact','SHORTLIST':'mainsp srtlist','IGNORE':'mainsp ignore','REMINDER':'ot_sprtie ot_bell','CANCEL_INTEREST':'deleteDecline'};
        layerView = <div id="contactOverlay" className="posabs dispbl scrollhid">
        {loaderView}
            <div id="vpro_tapoverlay" className="posabs vpro_tapoverlay">
                <div className="threeDotOverlay white fullwid" id="commonOverlayTop">
                    <div id="3DotProPic" className="txtc">
                      <div id="photoIDDiv" className="photoDiv">
                        <img id="ce_photo" className="srp_box2 mr6" src={this.props.profileThumbNailUrl} />
                      </div>
                      <div className="f14 white fontlig opa80 pt10" id="topMsg">Connect with {this.props.username}</div>
                    </div>
                    {
                      buttons.map(function(button,index)
                      {

                        let top_id = button.action;
                        let inside_id = "otherimage"+index;
                        let outside_id = "otherlabel"+index;
                        let label = button.label;

                        if ( button.action == "SHORTLIST" && (button.params.indexOf("true") !== -1 ))
                        {
                          imageList[button.action] = "mainsp shortlisted";
                        }


                          return (
                            <div onClick={() => this.manageThreeDotsButton(button,index)} className="wid49p txtc mt45 dispibl" id={top_id}>
                              <i className= {imageList[button.action]} id={inside_id}></i>
                              <div className="f14 white fontlig lh30" id={outside_id}>{label}</div>
                            </div>
                          )
                      },this)
                    }

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
        {showIgnoreLayerView}
        <div onClick={() => this.getThreeDotLayer()} className="posabs srp_pos2">
          <i className="mainsp threedot1"></i>
        </div>
      </div>
      );
  }

}

const mapDispatchToProps = (dispatch) => {
    return{
        changeButton: (button,index) => {
          dispatch({
            type: 'REPLACE_BUTTON',
            payload: {button:button,index:index}
          });
        }
    }
}

export default connect(null,mapDispatchToProps)(ThreeDots)
