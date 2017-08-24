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
    let e = document.getElementById('msgId');
    //e.scrollTop =  e.scrollHeight;
    document.getElementById("ProfilePage").classList.add("scrollhid");
    let topHeadHgt, bottomBtnHeight,remHgtMSG;
    topHeadHgt = document.getElementById('comm_headerMsg').clientHeight;
    bottomBtnHeight =document.getElementById('parentFootId').clientHeight;
    remHgtMSG = window.innerHeight - (topHeadHgt+bottomBtnHeight);

    e.style.height = remHgtMSG+"px";
    console.log(remHgtMSG);
    console.log(e.scrollHeight);
    if( remHgtMSG < e.scrollHeight)
    {
      e.scrollTop =  e.scrollHeight;
    }
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
    if(nextProps.contactAction.msgInitiated)
      this.props.buttonData.messages = nextProps.contactAction.message.messages.concat(this.props.buttonData.messages);
  }

  sendMessage() {
    this.showLoaderDiv()
    let message = document.getElementById("writeMessageTxtId").value;
    var e = document.getElementById('msgId');
    document.getElementById("writeMessageTxtId").value = "";
    var url = '&profilechecksum='+this.props.profilechecksum+'&draft='+message;
    this.props.sendMessageApi('/api/v2/contacts/postWriteMessage','MESSAGE',url);
    this.setState({
      showLoader:false
    });
    document.getElementById("writeMsgDisplayId").innerHTML += '<div class="txtr com_pad_l fontlig f16 white com_pad1"><div class="fl dispibl writeMsgDisplayTxtId fullwid">'+message+'</div><div class="dispbl f12 color1 white txtr msgStatusTxt" id="msgStatusTxt">Message Sent</div></div>';
    e.scrollTop =  e.scrollHeight;
  }

  showMessagesOnScroll(){
    var url = '&profilechecksum='+this.props.profilechecksum+'&MSGID='+this.props.buttonData.MSGID+'&pagination=1';
    this.props.writeMessageApi('/api/v2/contacts/WriteMessage','WRITE_MESSAGE',url);
  }

  render(){
  var loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="div"></Loader>;
        }
    let WriteMsg_buttonView, WriteMsg_innerView,WriteMsg_topView,WrtieMsg_historydiv,WriteMsg_appendmsg;
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
      if(this.props.buttonData.messages != null)
      {
              if(this.props.buttonData.messages.length <25)
                this.showMessagesOnScroll();                               
              console.log(this.props.buttonData.messages);
              WrtieMsg_historydiv =  this.props.buttonData.messages.map((msg,index)=>{
                                      let msg_class1;
                                      if(msg.mymessage == 'true')
                                      {
                                        msg_class1 = "txtr ce_pad_l";
                                      }
                                      else
                                      {
                                        msg_class1 = "txtl ce_pad_2";
                                      }

                                      return(
                                          <div className={"fontlig f16 white "+ msg_class1} id={"msg_"+index}>
                                            <span>{msg.message}</span>
                                            <span className="dispbl f12 color1 pt5 white">{msg.timeTxt}</span>
                                          </div>
                                      );
                                    });
      }
      else
      {
            WrtieMsg_historydiv = <div className="com_pad1_new fontlig f16 white" id="presetMessageDispId">
              <span id="presetMessageTxtId">Start the conversation by writing a message.</span>
             <span className="dispbl f12 color1 pt5 white" id="presetMessageStatusId"></span>
            </div>
      }
      WriteMsg_appendmsg = <div id="writeMsgDisplayId">
                              <div className="txtr com_pad_l fontlig f16 white com_pad1">
                                <div className="com_pad2 clearfix fl dispibl writeMsgDisplayTxtId"></div>
                                <div className="dispbl f12 color1 pt5 white txtr msgStatusTxt" id="msgStatusTxt">Message Sent</div>
                            </div>
                          </div>;
      WriteMsg_innerView=[WrtieMsg_historydiv,WriteMsg_appendmsg];
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
        },
        writeMessageApi: (api,action,url) => {
          commonApiCall(api,url,action,'POST',dispatch,true);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(WriteMessage)
