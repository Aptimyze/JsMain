require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import Loader from "../../common/components/Loader";

export class WriteMessage extends React.Component{
  constructor(props){
    super();
    this.state = {showLoader: false};
    console.log("writeMessage",props);
  }

  componentDidMount(){
    document.getElementById("ProfilePage").classList.add("scrollhid"); 
  }

  showLoaderDiv() {
        this.setState({
            showLoader:true
        });

  }

  hideMessageLayer() {
    document.getElementById("ProfilePage").classList.remove("scrollhid");
    this.props.closeMessageLayer();
  }

  componentWillReceiveProps(nextProps){

  }
  sendMessage() {
    this.showLoaderDiv()
    let message = document.getElementById("writeMessageTxtId").value;
    document.getElementById("writeMessageTxtId").value = "";
    var url = '&profilechecksum='+this.props.profilechecksum+'&draft='+message;
    this.props.sendMessageApi('/api/v2/contacts/postWriteMessage','MESSAGE',url);
    this.setState({
      showLoader:false
    });
    document.getElementById("writeMsgDisplayId").innerHTML += '<div class="txtr com_pad_l fontlig f16 white com_pad1"><div class="fl dispibl writeMsgDisplayTxtId fullwid">'+message+'</div><div class="dispbl f12 color1 white txtr msgStatusTxt" id="msgStatusTxt">Message Sent</div></div>';
  }

  render(){
    var loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="div"></Loader>;
        }
    var buttonView, innerView;
    if(this.props.buttonData != 'Y') {
      buttonView = <a href="/profile/mem_comparison.php" id="buttons1" className="view_ce fullwid">
              <div className="fullwid bg7 txtc pad5new posrel lh40">
                  <div className="wid60p">
                      <div className="white">View Membership Plans</div>
                  </div>
              </div>
            </a>;
      innerView = <div className="fullwid white dispbl freeMsgDiv posabs" id="freeMsgId">
          Become a paid member to connect further
      </div>;
    } else {
      buttonView = <div className="fullwid clearfix brdr23_contact btmsend txtAr_bg1 posfix btm0" id="comm_footerMsg">
            <div className="fl wid80p com_pad3">
                <textarea id="writeMessageTxtId" className="fullwid lh15 inp_1 white"></textarea>
            </div>
            <div onClick={() => this.sendMessage()} className="fr com_pad4">
                <div className="color2 f16 fontlig">Send</div>
            </div>
        </div>;
        innerView = <div>
          <div className="com_pad1_new fontlig f16 white" id="presetMessageDispId">
            <span id="presetMessageTxtId">Start the conversation by writing a message.</span>
           <span className="dispbl f12 color1 pt5 white" id="presetMessageStatusId"></span>
          </div>
          <div id="writeMsgDisplayId">
              
          </div>
        </div>;
    }
    return(
      <div id="writeMessageOverlay" className="posabs dispbl scrollhid">
        <div className="posabs vpro_tapoverlay">
          <div className="posrel fullwid z105">
            <div className="pad18 brdr4" id="comm_headerMsg">
              <div className="posrel clearfix fontthin hdrHght_con">
                  <div className="posabs com_left1">
                      <img id="imageId" src={this.props.profileThumbNailUrl} className="com_brdr_radsrp wid50 hgt50" />
                  </div>
                  <div className="posabs com_right1" onClick={() => this.hideMessageLayer()} >
                      <i className="mainsp com_cross"></i>
                  </div>
                  <div className="txtc f19 white pt10" id="usernameId">{this.props.username}</div>
                </div>
              </div>
            </div>
            <div className="setmid posfix">
              {loaderView}
            </div>
            {innerView}

            {buttonView}
        </div>
        <img src="https://www.jeevansathi.com/images/jsms/membership_img/revamp_bg1.jpg" className="classimg1 vpro_pos1 posfix z100" />
      </div>

    );
  }
}

const mapStateToProps = (state) => {
    return{
      contactAction: state.contactEngineReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
         sendMessageApi: (api,action,url) => {
          commonApiCall(api,url,action,'POST',dispatch,true);
    }
}
}

export default connect(mapStateToProps,mapDispatchToProps)(WriteMessage)
