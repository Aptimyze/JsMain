require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import { performAction,cssMap } from "./contactEngine.js";
import * as CONSTANTS from '../../common/constants/apiConstants';
import {getCookie} from '../../common/components/CookieHelper';
import ReportAbuse from "./ReportAbuse";
import BlockPage from "./BlockPage";
import Loader from "../../common/components/Loader";


class ThreeDots extends React.Component{
  constructor(props){
    super(props);
    this.state = {
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

    if(this.state.showIgnoreLayer == true) {
      showIgnoreLayerView = <BlockPage message={this.state.showIgnoreLayerMessage} profileThumbNailUrl={this.props.profileThumbNailUrl} closeBlockPageLayer={() => this.closeBlockPageLayer()} unblock = {() => this.manageThreeDotsButton(this.state.ignoreButton,3)}/>
      document.getElementById("vpro_tapoverlay").classList.add("dn");
    }
      var buttons = this.props.buttondata.buttons;console.log('buttt',buttons);
      var loaderView;
      if(this.state.showLoader)
      {
        loaderView = <Loader show="page"></Loader>;
      }
      console.log('insed threed');
        return (<div id="contactOverlay" className="posabs dispbl scrollhid">
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
                        if(button.action=='DEFAULT')return (<div></div>);
                        let top_id = button.action;
                        let inside_id = "otherimage"+index;
                        let outside_id = "otherlabel"+index;
                        let label = button.label;

                          return (
                            <div key={index} onClick={() => this.props.bindAction(button,index)} className="wid49p txtc mt45 dispibl" id={top_id}>
                              <i className= {cssMap[button.iconid]} id={inside_id}></i>
                              <div className="f14 white fontlig lh30" id={outside_id}>{label}</div>
                            </div>
                          )
                      },this)
                    }

                    <div className="dispibl fullwid mt45">
                      <div onClick={this.props.closeThreeDotLayer} className="mainsp srp_close1"></div>
                    </div>
                </div>
            </div>
            <img src="https://www.jeevansathi.com/images/jsms/membership_img/revamp_bg1.jpg" className="classimg1 vpro_pos1 posfix z100" />
        </div>)

  }

}

const mapDispatchToProps = (dispatch) => {
    return{
    }
}

export default connect(null,mapDispatchToProps)(ThreeDots)
