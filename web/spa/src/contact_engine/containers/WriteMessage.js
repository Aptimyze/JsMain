require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import Loader from "../../common/components/Loader";

export class WriteMessage extends React.Component{
  constructor(props){
    super();
    this.state = {
        showLoader: false,
        tupleDim : {'width' : window.innerWidth,'height': window.innerHeight}
    };

  }

  componentDidMount(){
    document.getElementById("ProfilePage").classList.add("scrollhid");
    let topHeadHgt, bottomBtnHeight;
    topHeadHgt = document.getElementById('comm_headerMsg').clientHeight;
    bottomBtnHeight =document.getElementById('parentFootId').clientHeight;
    document.getElementById('msgId').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
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
    let WriteMsg_buttonView, WriteMsg_innerView,WriteMsg_topView;
  console.log('in 22');
  console.log(this.props);
    WriteMsg_topView =   <div className="posrel clearfix fontthin ce_hgt1">
        <div className="posabs com_left1">
          <img id="imageId" src={this.props.buttonData.viewed} className="com_brdr_radsrp ce_dim1"/>
        </div>
        <div className="posabs com_right1">
          <i className="mainsp com_cross" onClick={this.props.closeWriteMsgLayer}></i>
        </div>
        <div className="txtc f19 white pt10" id="usernameId">{this.props.username}</div>
      </div>;

    if(this.props.buttonData.cansend == 'false')
    {
      WriteMsg_innerView = <div className="fullwid white dispbl freeMsgDiv ce_pt1" id="freeMsgId">
          Become a paid member to connect further
      </div>;

      let offertextHTML='',buttonHTML='';

      if(this.props.buttonData.button.text!=null)
      {
         offertextHTML = (
                          <div className="white color2 ce_hgt2 brdr23_contact" key="PD_offer_text" id="CEmembershipMessage2">
                          {this.props.buttonData.button.text}
                         </div>
                        );
      }
      buttonHTML = <a href="/profile/mem_comparison.php" id="memTxtId" key="PD_mem_label" className="fullwid">
              <div className="fullwid bg7 txtc pad5new posrel lh40">
                  <div className="wid60p">
                      <div className="white">  {this.props.buttonData.button.label}</div>
                  </div>
              </div>
            </a>

      WriteMsg_buttonView = [offertextHTML,buttonHTML];
    }
    else
    {
      WriteMsg_buttonView = <div className="fullwid clearfix brdr23_contact btmsend txtAr_bg1  btm0" id="comm_footerMsg">
            <div className="fl wid80p com_pad3">
                <textarea id="writeMessageTxtId" className="fullwid lh15 inp_1 white"></textarea>
            </div>
            <div onClick={() => this.sendMessage()} className="fr com_pad4">
                <div className="color2 f16 fontlig">Send</div>
            </div>
        </div>;
        WriteMsg_innerView = <div>
          <div className="com_pad1_new fontlig f16 white" id="presetMessageDispId">
            <span id="presetMessageTxtId">Start the conversation by writing a message.</span>
           <span className="dispbl f12 color1 pt5 white" id="presetMessageStatusId"></span>
          </div>
          <div id="writeMsgDisplayId">

          </div>
        </div>;
    }
    return(
      <div className="posabs ce-bg ce_top1 ce_z101" style={this.state.tupleDim}>
        <a href="#"  className="ce_overlay ce_z102" > </a>
        <div className="posabs ce_z103 ce_top1 fullwid">

            <div className="pad18 brdr4" id="comm_headerMsg">
              {WriteMsg_topView}
            </div>

            <div className="message_con ce_scoll1" id="msgId">
              {WriteMsg_innerView}
            </div>

            <div id="parentFootId">
              {WriteMsg_buttonView}
            </div>

        </div>
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
