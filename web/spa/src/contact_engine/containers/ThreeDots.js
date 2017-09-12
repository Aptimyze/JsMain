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

class Btn3dotprint extends React.Component{
  render(){
    return(<div className="fullwid" id="buttonsOverlay">
            {
                        this.props.buttonprint.map(function(button,index)
                        {
                          if(button.action=='DEFAULT')return (<div></div>);
                          let top_id = button.action;
                          let inside_id = "otherimage"+index;
                          let outside_id = "otherlabel"+index;
                          let label = button.label;

                            return (
                              <div key={index} onClick={() => this.props.bindAction(button,index)} className={"wid49p txtc mt45 dispibl " + (button.enable ? "" : " opa50") } id={top_id}>
                                <i className={cssMap[button.iconid]} id={inside_id}></i>
                                <div className="f14 white fontlig lh30" id={outside_id}>{label}</div>
                              </div>
                            )
                        },this)
                      }
                    </div>

    );
  }
}


class ThreeDots extends React.Component{
  constructor(props){
    super(props);
    this.state = {
      showLoader:false,
      showIgnoreLayer:false,
      showIgnoreLayerMessage:'',
      ignoreButton:{},
      tupleDim : {'width' : window.innerWidth,'height': window.innerHeight}
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
      case 'CONTACT_DETAIL':

      break;
      case 'IGNORE':
      console.log('3 dot callback');
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
    document.getElementById("vpro_tapoverlay").classList.remove("dn");
  }

  closeBlockPageLayer()
  {
    this.setState({showIgnoreLayer: false});
    this.closeThreeDotLayer()
    document.getElementById("vpro_tapoverlay").classList.remove("dn");
  }

  componentDidMount(){
    //console.log('did mount');
    //console.log(document.getElementById("commonOverlayTop").offsetHeight);
    if(document.getElementById("commonOverlayTop").offsetHeight> window.innerWidth)
    {
      document.getElementById("overlaysecond_threedot").style.overflow = "auto";
      document.getElementById("commonOverlayTop").style.margin = "20px 0";
    }
  }


  componentWillReceiveProps(nextProps)
  {
    this.hideLoaderDiv();
  }

  render(){

    var reportAbuseView;
    var showIgnoreLayerView;

    let image3dot = <div id="3DotProPic" className="txtc">
                      <div id="photoIDDiv" className="photoDiv">
                        <img id="ce_photo" className="srp_box2 mr6" src={this.props.profileThumbNailUrl} />
                      </div>
                      <div className="f14 white fontlig opa80 pt10" id="topMsg">Connect with {this.props.username}</div>
                    </div>;


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

        return (<div className="posabs ce-bg ce_top1 ce_z101 scrollhid" id="overlayove_threedot" style={this.state.tupleDim}>
                  <a href="#"  className="ce_overlay ce_z102" > </a>
                    <div className="posabs ce_z103 ce_top1 fullwid" id="overlaysecond_threedot" style={this.state.tupleDim}>

                      <div className="threeDotOverlay white fullwid" id="commonOverlayTop">
                        {image3dot}
                        <Btn3dotprint bindAction={this.props.bindAction} buttonprint={buttons}/>
                        <div className="dispibl fullwid mt45">
                          <div onClick={this.props.closeThreeDotLayer} className="mainsp srp_close1"></div>
                        </div>
                      </div>


                    </div>
                </div>



        )

  }

}

const mapDispatchToProps = (dispatch) => {
    return{
    }
}

export default connect(null,mapDispatchToProps)(ThreeDots)
